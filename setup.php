<?php
/**
 * Setup script: Creates all database tables, seeds content strings and ensures admin user exists.
 * Run once via browser: http://localhost/seed/setup.php
 */
require_once __DIR__ . '/includes/db.php';

$messages = [];

// ── 0. Create all required tables if they don't exist ──

$table_queries = [
    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "CREATE TABLE IF NOT EXISTS content (
        id INT AUTO_INCREMENT PRIMARY KEY,
        page VARCHAR(100) NOT NULL,
        content_key VARCHAR(255) NOT NULL,
        content_value TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_page_key (page, content_key)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name_en VARCHAR(255) NOT NULL,
        slug VARCHAR(255) NOT NULL UNIQUE,
        description_en TEXT,
        image VARCHAR(500),
        is_active TINYINT(1) DEFAULT 1,
        sort_order INT DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category_id INT NOT NULL,
        name_en VARCHAR(255) NOT NULL,
        slug VARCHAR(255) NOT NULL UNIQUE,
        crop_name_en VARCHAR(255),
        seed_type VARCHAR(100),
        image_url VARCHAR(500),
        price_label VARCHAR(100),
        yield_potential VARCHAR(255),
        growing_season_en VARCHAR(255),
        climate_suitability_en VARCHAR(255),
        disease_resistance_en VARCHAR(255),
        description_en TEXT,
        features_en TEXT,
        usage_instructions_en TEXT,
        is_active TINYINT(1) DEFAULT 1,
        sort_order INT DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "CREATE TABLE IF NOT EXISTS farming_guides (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title_en VARCHAR(255) NOT NULL,
        slug VARCHAR(255) NOT NULL UNIQUE,
        crop_en VARCHAR(255),
        summary_en TEXT,
        content_en LONGTEXT,
        media_path VARCHAR(500),
        download_file VARCHAR(500),
        download_label VARCHAR(255) DEFAULT 'Download PDF',
        is_published TINYINT(1) DEFAULT 0,
        sort_order INT DEFAULT 0,
        published_at DATETIME,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "CREATE TABLE IF NOT EXISTS success_stories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        farmer_name VARCHAR(255) NOT NULL,
        region VARCHAR(255),
        crop_type VARCHAR(255),
        yield_increase_percent INT DEFAULT 0,
        testimonial_en TEXT,
        title_en VARCHAR(255),
        description_en TEXT,
        image_url VARCHAR(500),
        is_active TINYINT(1) DEFAULT 1,
        sort_order INT DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "CREATE TABLE IF NOT EXISTS crop_calendars (
        id INT AUTO_INCREMENT PRIMARY KEY,
        region VARCHAR(255) NOT NULL,
        crop_name_en VARCHAR(255) NOT NULL,
        sowing_start_month TINYINT NOT NULL,
        sowing_end_month TINYINT NOT NULL,
        harvest_start_month TINYINT NOT NULL,
        harvest_end_month TINYINT NOT NULL,
        notes_en TEXT,
        is_active TINYINT(1) DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "CREATE TABLE IF NOT EXISTS inquiries (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        phone VARCHAR(50),
        email VARCHAR(255),
        message TEXT,
        crop VARCHAR(255),
        source VARCHAR(100) DEFAULT 'contact_form',
        status VARCHAR(50) DEFAULT 'new',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
];

$tables_created = 0;
foreach ($table_queries as $sql) {
    $pdo->exec($sql);
    $tables_created++;
}
$messages[] = "Verified/created {$tables_created} database tables.";

// ── 0b. Remove any JSON CHECK constraints on products table (from Laravel migrations) ──
try {
    $constraints = $pdo->query("
        SELECT CONSTRAINT_NAME FROM information_schema.CHECK_CONSTRAINTS 
        WHERE CONSTRAINT_SCHEMA = 'khaliqia' AND TABLE_NAME = 'products'
    ")->fetchAll();
    if (!empty($constraints)) {
        $pdo->exec("ALTER TABLE products 
            MODIFY COLUMN gallery_images longtext DEFAULT NULL,
            MODIFY COLUMN packaging_sizes longtext DEFAULT NULL,
            MODIFY COLUMN features_en longtext DEFAULT NULL,
            MODIFY COLUMN features_local longtext DEFAULT NULL");
        $messages[] = "Removed JSON CHECK constraints from products table.";
    }
} catch (PDOException $e) {
    // Columns may not exist on fresh install — safe to ignore
}

// ── 1. Ensure admin user exists with known credentials ──
$stmt = $pdo->query("SELECT COUNT(*) FROM users");
$userCount = $stmt->fetchColumn();

if ($userCount == 0) {
    $hash = password_hash('admin123', PASSWORD_BCRYPT);
    $pdo->prepare("INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())")
        ->execute(['Admin', 'admin@khaliqia.com', $hash]);
    $messages[] = "Created admin user: admin@khaliqia.com / admin123";
} else {
    // Update existing admin password so login works
    $hash = password_hash('admin123', PASSWORD_BCRYPT);
    $pdo->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE email = ?")
        ->execute([$hash, 'admin@khaliqia.com']);
    $messages[] = "Reset admin password: admin@khaliqia.com / admin123";
}

// 2. Seed all content strings needed across the site
$content_seeds = [
    // SEO
    ['seo', 'default_title', 'Khaliqia Seed Corporation'],
    ['seo', 'default_description', 'Quality seeds for sustainable farming.'],
    
    // Site-wide
    ['site', 'name', 'Khaliqia'],
    ['site', 'tagline', 'Growing better harvests.'],
    ['site', 'copyright', '&copy; 2026 Khaliqia Seed Corporation.'],
    
    // Header
    ['header', 'cta_text', 'Get Quote'],
    ['header', 'nav_home', 'Home'],
    ['header', 'nav_about', 'About'],
    ['header', 'nav_products', 'Products'],
    ['header', 'nav_guides', 'Guides'],
    ['header', 'nav_stories', 'Stories'],
    ['header', 'nav_calendar', 'Calendar'],
    ['header', 'nav_contact', 'Contact'],
    
    // Footer
    ['footer', 'quick_links_title', 'Quick Links'],
    ['footer', 'contact_title', 'Contact'],
    ['footer', 'description', 'Khaliqia Seed Corporation has been providing premium quality seeds to farmers for over 25 years. Growing better harvests together.'],
    ['footer', 'link_home', 'Home'],
    ['footer', 'link_about', 'About Us'],
    ['footer', 'link_products', 'Products'],
    ['footer', 'link_guides', 'Farming Guides'],
    ['footer', 'link_stories', 'Success Stories'],
    ['footer', 'address', '123 Agriculture St, Green City'],
    ['footer', 'hours_title', 'Business Hours'],
    ['footer', 'hours_weekday', 'Mon - Fri: 9:00 AM - 6:00 PM'],
    ['footer', 'hours_weekend', 'Sat: 9:00 AM - 2:00 PM'],
    ['footer', 'hours_closed', 'Sunday: Closed'],
    
    // Home page
    ['home', 'hero_title', 'Empowering Agriculture with Quality Seeds'],
    ['home', 'hero_description', 'We provide premium hybrid and organic seeds to help farmers achieve maximum yields and sustainable growth.'],
    ['home', 'hero_badge', 'Trusted by 10,000+ Farmers Across Pakistan'],
    ['home', 'hero_cta_secondary', 'Talk to Expert'],
    
    // Home - Experience section
    ['home', 'experience_title', 'Decades of Agricultural Excellence'],
    ['home', 'experience_subtitle', 'For over two decades, Khaliqia Seed Corporation has been at the forefront of agricultural innovation, providing certified seeds to thousands of farmers.'],
    ['home', 'stat_1_value', '25+'],
    ['home', 'stat_1_label', 'Years Experience'],
    ['home', 'stat_2_value', '10,000+'],
    ['home', 'stat_2_label', 'Farmers Served'],
    ['home', 'stat_3_value', '150+'],
    ['home', 'stat_3_label', 'Seed Varieties'],
    ['home', 'stat_4_value', '50+'],
    ['home', 'stat_4_label', 'Regions Covered'],
    ['home', 'experience_block_title', 'Why Farmers Trust Khaliqia'],
    ['home', 'experience_block_text', 'Our seeds undergo rigorous quality testing and are certified by national agricultural authorities. We work directly with farming communities to understand their unique challenges and provide tailored seed solutions.'],
    ['home', 'feature_1_title', 'Lab Tested'],
    ['home', 'feature_1_text', 'Every batch certified for germination'],
    ['home', 'feature_2_title', 'Climate Adaptive'],
    ['home', 'feature_2_text', 'Bred for local weather conditions'],
    ['home', 'feature_3_title', 'Expert Support'],
    ['home', 'feature_3_text', 'Agronomists available 24/7'],
    ['home', 'feature_4_title', 'Fast Delivery'],
    ['home', 'feature_4_text', 'Doorstep delivery nationwide'],
    
    // Home - Quality / Certifications section
    ['home', 'quality_title', 'Quality You Can Trust'],
    ['home', 'quality_subtitle', 'Our commitment to excellence is backed by internationally recognized certifications and quality standards.'],
    ['home', 'quality_placeholder', 'Certifications will be listed here.'],
    
    // UI strings
    ['ui', 'explore_seeds', 'Explore Products'],
    ['ui', 'categories_heading', 'Our Seed Categories'],
    ['ui', 'categories_subtitle', 'Explore our diverse range of premium seeds.'],
    ['ui', 'products_title', 'Our Seed Collection'],
    ['ui', 'products_subtitle', 'Premium quality seeds for every farming need.'],
    ['ui', 'guides_title', 'Expert Farming Guides'],
    ['ui', 'guides_subtitle', 'Knowledge is the most important seed. Learn how to maximize your harvest with our expert resources.'],
    ['ui', 'success_stories_title', 'Success Stories from the Field'],
    ['ui', 'success_stories_subtitle', 'Real results from real farmers who chose Khaliqia seeds.'],
    ['ui', 'calendar_title', 'Regional Sowing & Harvest Calendar'],
    ['ui', 'calendar_subtitle', 'Stay ahead of the seasons with our optimized agricultural timeline for various regions.'],
    
    // About page
    ['about', 'title', 'About Khaliqia Seed Corporation'],
    ['about', 'description', 'Khaliqia Seed Corporation is a leader in the agricultural sector, dedicated to providing high-quality seeds to farmers.'],
    ['about', 'vision_title', 'Our Vision'],
    ['about', 'vision_text', 'To revolutionize agriculture through innovation and sustainable practices.'],
    ['about', 'mission_title', 'Our Mission'],
    ['about', 'mission_text', 'To empower every farmer with the best seeds and knowledge to grow better harvests.'],
    ['about', 'image_url', 'https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?auto=format&fit=crop&q=80&w=800'],
    
    // About - CEO / Leadership
    ['about', 'leadership_title', 'Our Leadership'],
    ['about', 'leadership_subtitle', 'Meet the visionary behind Khaliqia Seed Corporation.'],
    ['about', 'ceo_name', 'Muhammad Khaliq'],
    ['about', 'ceo_role', 'Chief Executive Officer'],
    ['about', 'ceo_tagline', 'Founder & CEO — Leading agricultural innovation since 2000'],
    ['about', 'ceo_bio', 'With over 25 years of experience in the agricultural sector, our CEO has dedicated his life to improving farming outcomes across Pakistan. Under his leadership, Khaliqia Seed Corporation has grown from a small local seed supplier to a nationally recognized name in agricultural excellence. His vision of empowering every farmer with quality seeds continues to drive the company forward.'],
    ['about', 'ceo_image', ''],
    ['about', 'ceo_stat_1_value', '25+'],
    ['about', 'ceo_stat_1_label', 'Years in Agriculture'],
    ['about', 'ceo_stat_2_value', '5'],
    ['about', 'ceo_stat_2_label', 'Industry Awards'],
    ['about', 'ceo_stat_3_value', '50+'],
    ['about', 'ceo_stat_3_label', 'R&D Projects Led'],
    
    // Contact page
    ['contact', 'title', 'Contact Us'],
    ['contact', 'subtitle', 'Have questions? We are here to help.'],
    ['contact', 'form_heading', 'Send us a message'],
    ['contact', 'address', '123 Agriculture St, Green City'],
    ['contact', 'phone', '+92 300 1234567'],
    ['contact', 'email', 'info@khaliqiaseeds.com'],
    ['contact', 'whatsapp', '+923001234567'],
    ['contact', 'office_title', 'Our Office'],
    ['contact', 'phone_title', 'Call Us'],
    ['contact', 'whatsapp_title', 'WhatsApp'],
    ['contact', 'whatsapp_cta', 'Chat with an expert'],
    ['contact', 'hours_weekday', '9:00 AM – 6:00 PM'],
    ['contact', 'hours_saturday', '9:00 AM – 2:00 PM'],
    ['contact', 'hours_sunday', 'Closed'],
    
    // Calendar page CTA section
    ['calendar', 'cta_title', 'Need a Custom Planting Schedule?'],
    ['calendar', 'cta_description', 'Our agricultural specialists can provide personalized calendars based on your specific soil and micro-climate conditions.'],
    ['calendar', 'cta_button', 'Speak to an Expert'],
    ['calendar', 'stat_1_value', '100%'],
    ['calendar', 'stat_1_label', 'Accuracy'],
    ['calendar', 'stat_2_value', '50+'],
    ['calendar', 'stat_2_label', 'Regions'],
    ['calendar', 'stat_3_value', '24/7'],
    ['calendar', 'stat_3_label', 'Support'],
    ['calendar', 'stat_4_value', 'Live'],
    ['calendar', 'stat_4_label', 'Updates'],
    
    // Success stories CTA
    ['success', 'cta_title', 'Ready to Write Your Own Success Story?'],
    ['success', 'cta_description', 'Join the community of flourishing farmers and see the difference quality seeds can make.'],
    ['success', 'cta_button', 'Consult Our Experts'],
];

$insert_stmt = $pdo->prepare("INSERT INTO content (page, content_key, content_value) VALUES (?, ?, ?)");
$check_stmt = $pdo->prepare("SELECT COUNT(*) FROM content WHERE page = ? AND content_key = ?");

$inserted = 0;
$skipped = 0;
foreach ($content_seeds as $seed) {
    $check_stmt->execute([$seed[0], $seed[1]]);
    if ($check_stmt->fetchColumn() == 0) {
        $insert_stmt->execute($seed);
        $inserted++;
    } else {
        $skipped++;
    }
}
$messages[] = "Content strings: {$inserted} inserted, {$skipped} already existed.";

// Output results
?>
<!DOCTYPE html>
<html>
<head><title>Setup Complete</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
<div class="bg-white rounded-xl shadow-lg p-10 max-w-lg w-full">
    <h1 class="text-2xl font-bold text-green-700 mb-6">&#10004; Setup Complete</h1>
    <?php foreach ($messages as $m): ?>
        <p class="text-gray-700 mb-2">&#8226; <?php echo $m; ?></p>
    <?php endforeach; ?>
    <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
        <p class="font-bold text-yellow-800">Admin Login Credentials:</p>
        <p class="text-yellow-700">Email: <code>admin@khaliqia.com</code></p>
        <p class="text-yellow-700">Password: <code>admin123</code></p>
    </div>
    <div class="mt-6 flex gap-4">
        <a href="index.php" class="bg-green-600 text-white px-6 py-2 rounded-lg font-bold">Visit Site</a>
        <a href="admin/login.php" class="bg-gray-800 text-white px-6 py-2 rounded-lg font-bold">Admin Panel</a>
    </div>
</div>
</body>
</html>

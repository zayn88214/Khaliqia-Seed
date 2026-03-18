<?php
require_once 'includes/site_header.php';
require_once 'includes/db.php';
?>

<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-br from-green-50 via-white to-amber-50 agricultural-pattern py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 bg-green-600/10 text-green-700 text-sm font-semibold px-4 py-2 rounded-full mb-6">
                <span>🌾</span> <?php echo h(get_content('home', 'hero_badge', 'Trusted by 10,000+ Farmers Across Pakistan')); ?>
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-stone-900 leading-tight">
                <?php echo h(get_content('home', 'hero_title', 'Empowering Agriculture with Quality Seeds')); ?>
            </h1>
            <p class="mt-6 text-lg text-stone-600 leading-relaxed">
                <?php echo h(get_content('home', 'hero_description', 'We provide premium hybrid and organic seeds to help farmers achieve maximum yields and sustainable growth.')); ?>
            </p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="products.php" class="btn-primary rounded-lg px-8 py-3.5 font-semibold shadow-lg inline-flex items-center gap-2">
                    <?php echo h(get_content('ui', 'explore_seeds', 'Explore Products')); ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </a>
                <a href="contact.php" class="btn-secondary rounded-lg px-8 py-3.5 font-semibold inline-flex items-center gap-2">
                    <?php echo h(get_content('home', 'hero_cta_secondary', 'Talk to Expert')); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Company Experience Section -->
<section class="py-16 bg-white border-y border-stone-100">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-stone-900 mb-4"><?php echo h(get_content('home', 'experience_title', 'Decades of Agricultural Excellence')); ?></h2>
            <p class="text-stone-600 max-w-2xl mx-auto"><?php echo h(get_content('home', 'experience_subtitle', 'For over two decades, Khaliqia Seed Corporation has been at the forefront of agricultural innovation, providing certified seeds to thousands of farmers.')); ?></p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="stat-card rounded-2xl p-8 text-center">
                <div class="text-4xl font-bold text-green-600 mb-2"><?php echo h(get_content('home', 'stat_1_value', '25+')); ?></div>
                <div class="text-sm font-semibold text-stone-600 uppercase tracking-wider"><?php echo h(get_content('home', 'stat_1_label', 'Years Experience')); ?></div>
            </div>
            <div class="stat-card rounded-2xl p-8 text-center">
                <div class="text-4xl font-bold text-green-600 mb-2"><?php echo h(get_content('home', 'stat_2_value', '10,000+')); ?></div>
                <div class="text-sm font-semibold text-stone-600 uppercase tracking-wider"><?php echo h(get_content('home', 'stat_2_label', 'Farmers Served')); ?></div>
            </div>
            <div class="stat-card rounded-2xl p-8 text-center">
                <div class="text-4xl font-bold text-green-600 mb-2"><?php echo h(get_content('home', 'stat_3_value', '150+')); ?></div>
                <div class="text-sm font-semibold text-stone-600 uppercase tracking-wider"><?php echo h(get_content('home', 'stat_3_label', 'Seed Varieties')); ?></div>
            </div>
            <div class="stat-card rounded-2xl p-8 text-center">
                <div class="text-4xl font-bold text-green-600 mb-2"><?php echo h(get_content('home', 'stat_4_value', '50+')); ?></div>
                <div class="text-sm font-semibold text-stone-600 uppercase tracking-wider"><?php echo h(get_content('home', 'stat_4_label', 'Regions Covered')); ?></div>
            </div>
        </div>
        <div class="mt-12 bg-gradient-to-r from-green-50 via-amber-50/50 to-green-50 rounded-3xl p-8 md:p-12 border border-green-100">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <h3 class="text-2xl font-bold text-stone-900 mb-4"><?php echo h(get_content('home', 'experience_block_title', 'Why Farmers Trust Khaliqia')); ?></h3>
                    <p class="text-stone-600 leading-relaxed mb-6"><?php echo h(get_content('home', 'experience_block_text', 'Our seeds undergo rigorous quality testing and are certified by national agricultural authorities. We work directly with farming communities to understand their unique challenges and provide tailored seed solutions.')); ?></p>
                    <a href="about.php" class="text-green-600 font-bold inline-flex items-center gap-2 hover:gap-3 transition-all">
                        Learn Our Story <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white rounded-xl p-5 shadow-sm border border-stone-100">
                        <div class="text-2xl mb-2">🧪</div>
                        <h4 class="font-bold text-stone-900 text-sm"><?php echo h(get_content('home', 'feature_1_title', 'Lab Tested')); ?></h4>
                        <p class="text-stone-500 text-xs mt-1"><?php echo h(get_content('home', 'feature_1_text', 'Every batch certified for germination')); ?></p>
                    </div>
                    <div class="bg-white rounded-xl p-5 shadow-sm border border-stone-100">
                        <div class="text-2xl mb-2">🌿</div>
                        <h4 class="font-bold text-stone-900 text-sm"><?php echo h(get_content('home', 'feature_2_title', 'Climate Adaptive')); ?></h4>
                        <p class="text-stone-500 text-xs mt-1"><?php echo h(get_content('home', 'feature_2_text', 'Bred for local weather conditions')); ?></p>
                    </div>
                    <div class="bg-white rounded-xl p-5 shadow-sm border border-stone-100">
                        <div class="text-2xl mb-2">📞</div>
                        <h4 class="font-bold text-stone-900 text-sm"><?php echo h(get_content('home', 'feature_3_title', 'Expert Support')); ?></h4>
                        <p class="text-stone-500 text-xs mt-1"><?php echo h(get_content('home', 'feature_3_text', 'Agronomists available 24/7')); ?></p>
                    </div>
                    <div class="bg-white rounded-xl p-5 shadow-sm border border-stone-100">
                        <div class="text-2xl mb-2">🚚</div>
                        <h4 class="font-bold text-stone-900 text-sm"><?php echo h(get_content('home', 'feature_4_title', 'Fast Delivery')); ?></h4>
                        <p class="text-stone-500 text-xs mt-1"><?php echo h(get_content('home', 'feature_4_text', 'Doorstep delivery nationwide')); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold text-stone-900 mb-4"><?php echo h(get_content('ui', 'categories_heading', 'Our Seed Categories')); ?></h2>
        <p class="text-stone-600 max-w-2xl mx-auto"><?php echo h(get_content('ui', 'categories_subtitle', 'Explore our diverse range of premium seeds.')); ?></p>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 max-w-4xl mx-auto">
        <?php
        $cat_stmt = $pdo->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order ASC, name_en ASC");
        $db_categories = $cat_stmt->fetchAll();
        $icons = ['🧬', '🌿', '🥬', '🌾', '🌻', '🌽', '🍅', '🫘'];
        foreach ($db_categories as $i => $cat):
        ?>
        <a href="products.php?category=<?php echo urlencode($cat['slug']); ?>" class="bg-white p-8 border border-stone-200 rounded-2xl hover:shadow-lg hover:border-green-200 transition-all group block">
            <div class="text-4xl mb-5"><?php echo $icons[$i % count($icons)]; ?></div>
            <h3 class="font-bold text-xl text-stone-900 mb-2"><?php echo h($cat['name_en']); ?></h3>
            <p class="text-sm text-stone-500 leading-relaxed mb-4"><?php echo h($cat['description_en'] ?: 'Quality seed category tailored for sustainable farming.'); ?></p>
            <span class="text-green-600 font-semibold text-sm inline-flex items-center gap-1 group-hover:gap-2 transition-all">
                Explore Seeds
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </span>
        </a>
        <?php endforeach; ?>
    </div>

    <div class="text-center mt-10">
        <a href="products.php" class="text-green-600 font-semibold inline-flex items-center gap-2 hover:gap-3 transition-all text-lg">
            View All Products
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
        </a>
    </div>
</section>

<!-- Quality / Certifications Section -->
<section class="py-20 bg-gradient-to-b from-white to-green-50/40">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-stone-900 mb-4"><?php echo h(get_content('home', 'quality_title', 'Quality You Can Trust')); ?></h2>
            <p class="text-stone-600 max-w-2xl mx-auto"><?php echo h(get_content('home', 'quality_subtitle', 'Our commitment to excellence is backed by internationally recognized certifications and quality standards.')); ?></p>
        </div>
        <div class="max-w-4xl mx-auto bg-gradient-to-br from-green-50/60 to-amber-50/40 rounded-3xl p-12 md:p-16 border border-stone-200/60">
            <p class="text-stone-400 text-center font-medium"><?php echo h(get_content('home', 'quality_placeholder', 'Certifications will be listed here.')); ?></p>
        </div>
    </div>
</section>

<?php
require_once 'includes/site_footer.php';
?>

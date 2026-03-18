<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo h(get_content('seo', 'default_title', 'Khaliqia Seed Corporation')); ?></title>
    <meta name="description" content="<?php echo h(get_content('seo', 'default_description', 'Quality seeds for sustainable farming.')); ?>">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-body: #fafaf9;
            --text-body: #1c1917;
            --bg-card: #ffffff;
            --bg-header: #ffffff;
            --border-card: rgba(214, 211, 209, 0.5);
            --bg-footer: #1c1917;
            --text-footer: #d6d3d1;
            --bg-section-alt: #f5f5f4;
        }
        body { font-family: 'Poppins', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: var(--bg-body); color: var(--text-body); }
        
        /* Glass */
        .glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(34, 197, 94, 0.1);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 0.5rem;
            font-weight: 600;
        }
        .btn-primary:hover { box-shadow: 0 8px 20px rgba(22, 163, 74, 0.3); transform: translateY(-2px); }
        
        .btn-secondary {
            background: white;
            color: #16a34a;
            border: 2px solid #16a34a;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 0.5rem;
            font-weight: 600;
        }
        .btn-secondary:hover { background: #f0fdf4; transform: translateY(-2px); }
        
        .btn-whatsapp {
            background: #25D366;
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 0.5rem;
            font-weight: 600;
        }
        .btn-whatsapp:hover { background: #20BA5A; box-shadow: 0 8px 20px rgba(37, 211, 102, 0.3); transform: translateY(-2px); }

        /* Navigation */
        .nav-link { position: relative; font-weight: 500; color: #57534e; transition: color 0.3s; }
        .nav-link:hover { color: #16a34a; }
        .nav-link::after { content: ''; position: absolute; bottom: -4px; left: 0; width: 0; height: 2px; background: linear-gradient(90deg, #16a34a, #15803d); transition: width 0.3s ease; }
        .nav-link:hover::after { width: 100%; }
        
        /* Cards */
        .card-hover {
            background: var(--bg-card);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .card-hover:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(22, 163, 74, 0.15); }

        /* Section title bar */
        .section-title { position: relative; padding-bottom: 1rem; }
        .section-title::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #16a34a, #15803d);
            border-radius: 2px;
        }
        
        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #16a34a, #15803d);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Agricultural pattern */
        .agricultural-pattern {
            background-image:
                radial-gradient(circle at 20px 20px, rgba(22, 163, 74, 0.03) 1px, transparent 1px),
                radial-gradient(circle at 80px 80px, rgba(22, 163, 74, 0.03) 1px, transparent 1px);
            background-size: 100px 100px;
        }
        
        /* Stat card */
        .stat-card { background: var(--bg-card); border: 1px solid var(--border-card); border-radius: 1rem; }
        
        /* Animations */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes bounce-in { 0% { transform: scale(0.3); opacity: 0; } 50% { transform: scale(1.05); } 70% { transform: scale(0.9); } 100% { transform: scale(1); opacity: 1; } }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
        @keyframes topToBottomReveal { from { transform: translateY(-26px) scale(0.985); filter: blur(6px); opacity: 0; } to { transform: none; filter: none; opacity: 1; } }
        
        .scroll-reveal { opacity: 0; transform: translateY(60px); transition: all 0.8s ease-out; }
        .scroll-reveal.revealed { opacity: 1; transform: translateY(0); }
        .stagger-1 { animation-delay: 0.1s; } .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; } .stagger-4 { animation-delay: 0.4s; }
        
        /* Lazy images */
        img[loading="lazy"] { opacity: 0; transition: opacity 0.4s ease; }
        img.loaded, img[complete] { opacity: 1; }
        
        /* WhatsApp float */
        .whatsapp-float {
            position: fixed; bottom: 90px; right: 24px; z-index: 50;
            width: 60px; height: 60px; border-radius: 50%;
            background: #25D366; color: white;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
            animation: bounce-in 0.6s 1s both;
            transition: all 0.3s;
        }
        .whatsapp-float:hover { transform: scale(1.1) translateY(-4px); box-shadow: 0 12px 30px rgba(37, 211, 102, 0.5); }
        
        /* Mobile CTA bar */
        @media (max-width: 767px) {
            .mobile-cta-bar { position: fixed; bottom: 0; left: 0; right: 0; z-index: 40; padding: 0.75rem 1rem; background: white; border-top: 1px solid #e7e5e4; box-shadow: 0 -4px 12px rgba(0,0,0,0.08); }
            body { padding-bottom: 5rem; }
            .whatsapp-float { bottom: 80px; right: 16px; width: 52px; height: 52px; }
        }
    </style>
</head>
<body>
    <header class="sticky top-0 z-50 bg-white border-b border-stone-200 shadow-sm">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8 py-4">
            <a href="index.php" class="flex items-center gap-3 text-xl font-bold tracking-tight">
                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center text-white shadow-md">🌱</div>
                <span class="text-stone-900"><?php echo h(get_content('site', 'name', 'Khaliqia')); ?></span>
            </a>
            
            <nav class="hidden md:flex gap-8 items-center">
                <a href="index.php" class="nav-link"><?php echo h(get_content('header', 'nav_home', 'Home')); ?></a>
                <a href="about.php" class="nav-link"><?php echo h(get_content('header', 'nav_about', 'About')); ?></a>
                <a href="products.php" class="nav-link"><?php echo h(get_content('header', 'nav_products', 'Products')); ?></a>
                <a href="guides.php" class="nav-link"><?php echo h(get_content('header', 'nav_guides', 'Guides')); ?></a>
                <a href="success-stories.php" class="nav-link"><?php echo h(get_content('header', 'nav_stories', 'Stories')); ?></a>
                <a href="calendar.php" class="nav-link"><?php echo h(get_content('header', 'nav_calendar', 'Calendar')); ?></a>
                <a href="contact.php" class="nav-link"><?php echo h(get_content('header', 'nav_contact', 'Contact')); ?></a>
            </nav>

            <div class="flex items-center gap-4">
                <a href="contact.php" class="hidden sm:inline-flex btn-primary px-6 py-2.5 font-semibold text-sm shadow-lg">
                    <?php echo h(get_content('header', 'cta_text', 'Get Quote')); ?>
                </a>
                <button class="md:hidden p-2 text-stone-600" onclick="document.getElementById('mobileNav').classList.toggle('hidden')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>
            </div>
        </div>
        <!-- Mobile Nav -->
        <div id="mobileNav" class="hidden md:hidden border-t border-stone-100 bg-white px-4 pb-4">
            <nav class="flex flex-col gap-1 pt-2">
                <a href="index.php" class="py-2.5 px-3 rounded-lg text-stone-700 hover:bg-green-50 hover:text-green-700 font-medium">Home</a>
                <a href="about.php" class="py-2.5 px-3 rounded-lg text-stone-700 hover:bg-green-50 hover:text-green-700 font-medium">About</a>
                <a href="products.php" class="py-2.5 px-3 rounded-lg text-stone-700 hover:bg-green-50 hover:text-green-700 font-medium">Products</a>
                <a href="guides.php" class="py-2.5 px-3 rounded-lg text-stone-700 hover:bg-green-50 hover:text-green-700 font-medium">Guides</a>
                <a href="success-stories.php" class="py-2.5 px-3 rounded-lg text-stone-700 hover:bg-green-50 hover:text-green-700 font-medium">Stories</a>
                <a href="calendar.php" class="py-2.5 px-3 rounded-lg text-stone-700 hover:bg-green-50 hover:text-green-700 font-medium">Calendar</a>
                <a href="contact.php" class="py-2.5 px-3 rounded-lg text-stone-700 hover:bg-green-50 hover:text-green-700 font-medium">Contact</a>
                <a href="contact.php" class="mt-2 btn-primary text-center py-2.5 font-semibold text-sm">Get Quote</a>
            </nav>
        </div>
    </header>
    <main>

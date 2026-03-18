    </main>

    <!-- WhatsApp Float -->
    <a href="https://wa.me/<?php echo preg_replace('/\D+/', '', get_content('contact', 'whatsapp', '+923001234567')); ?>" target="_blank" class="whatsapp-float" title="Chat on WhatsApp">
        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    </a>

    <!-- Mobile CTA Bar -->
    <div class="mobile-cta-bar hidden max-md:grid grid-cols-2 gap-2">
        <a href="tel:<?php echo preg_replace('/\D+/', '', get_content('contact', 'phone', '+923001234567')); ?>" class="btn-primary text-center py-3 text-sm font-semibold">Call Now</a>
        <a href="https://wa.me/<?php echo preg_replace('/\D+/', '', get_content('contact', 'whatsapp', '+923001234567')); ?>" target="_blank" class="btn-whatsapp text-center py-3 text-sm font-semibold">WhatsApp</a>
    </div>

    <footer class="bg-stone-900 text-stone-300 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center text-white shadow-md">🌱</div>
                        <h3 class="text-white font-bold text-lg"><?php echo h(get_content('site', 'name', 'Khaliqia Seeds')); ?></h3>
                    </div>
                    <p class="text-sm text-stone-400 leading-relaxed mb-6 max-w-md"><?php echo h(get_content('footer', 'description', 'Khaliqia Seed Corporation has been providing premium quality seeds to farmers for over 25 years. Growing better harvests together.')); ?></p>
                    <div class="flex flex-wrap gap-2">
                        <span class="bg-green-900/30 text-green-400 text-xs px-3 py-1 rounded-full font-medium">Certified Seeds</span>
                        <span class="bg-green-900/30 text-green-400 text-xs px-3 py-1 rounded-full font-medium">Lab Tested</span>
                        <span class="bg-green-900/30 text-green-400 text-xs px-3 py-1 rounded-full font-medium">25+ Years</span>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4"><?php echo h(get_content('footer', 'quick_links_title', 'Quick Links')); ?></h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="index.php" class="text-stone-400 hover:text-green-400 transition"><?php echo h(get_content('footer', 'link_home', 'Home')); ?></a></li>
                        <li><a href="about.php" class="text-stone-400 hover:text-green-400 transition"><?php echo h(get_content('footer', 'link_about', 'About Us')); ?></a></li>
                        <li><a href="products.php" class="text-stone-400 hover:text-green-400 transition"><?php echo h(get_content('footer', 'link_products', 'Products')); ?></a></li>
                        <li><a href="guides.php" class="text-stone-400 hover:text-green-400 transition"><?php echo h(get_content('footer', 'link_guides', 'Farming Guides')); ?></a></li>
                        <li><a href="success-stories.php" class="text-stone-400 hover:text-green-400 transition"><?php echo h(get_content('footer', 'link_stories', 'Success Stories')); ?></a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4"><?php echo h(get_content('footer', 'contact_title', 'Contact')); ?></h4>
                    <ul class="space-y-2.5 text-sm text-stone-400">
                        <li class="flex items-start gap-2"><span>📍</span> <?php echo h(get_content('footer', 'address', '123 Agriculture St, Green City')); ?></li>
                        <li class="flex items-start gap-2"><span>📞</span> <?php echo h(get_content('contact', 'phone', '+92 300 0000000')); ?></li>
                        <li class="flex items-start gap-2"><span>✉️</span> <?php echo h(get_content('contact', 'email', 'info@khaliqiaseeds.com')); ?></li>
                    </ul>
                    <div class="mt-6">
                        <h4 class="text-white font-bold mb-3 text-sm"><?php echo h(get_content('footer', 'hours_title', 'Business Hours')); ?></h4>
                        <ul class="space-y-1.5 text-xs text-stone-500">
                            <li><?php echo h(get_content('footer', 'hours_weekday', 'Mon - Fri: 9:00 AM - 6:00 PM')); ?></li>
                            <li><?php echo h(get_content('footer', 'hours_weekend', 'Sat: 9:00 AM - 2:00 PM')); ?></li>
                            <li><?php echo h(get_content('footer', 'hours_closed', 'Sunday: Closed')); ?></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-stone-800 text-center text-xs text-stone-500">
                <p><?php echo get_content('site', 'copyright', '&copy; 2026 Khaliqia Seed Corporation. All rights reserved.'); ?></p>
            </div>
        </div>
    </footer>

    <script>
    // Scroll reveal
    document.addEventListener('DOMContentLoaded', function() {
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (!prefersReducedMotion) {
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) { entry.target.classList.add('revealed'); observer.unobserve(entry.target); }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -100px 0px' });
            document.querySelectorAll('.scroll-reveal').forEach(function(el) { observer.observe(el); });
        } else {
            document.querySelectorAll('.scroll-reveal').forEach(function(el) { el.classList.add('revealed'); });
        }
        // Lazy images
        document.querySelectorAll('img[loading="lazy"]').forEach(function(img) {
            if (img.complete) img.classList.add('loaded');
            else img.addEventListener('load', function() { img.classList.add('loaded'); });
        });
        // Active nav link
        var currentPage = window.location.pathname.split('/').pop() || 'index.php';
        document.querySelectorAll('.nav-link').forEach(function(link) {
            if (link.getAttribute('href') === currentPage) link.style.color = '#16a34a';
        });
    });
    </script>
</body>
</html>

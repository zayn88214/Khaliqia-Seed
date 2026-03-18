<?php
require_once 'includes/site_header.php';
require_once 'includes/db.php';

$stmt = $pdo->query("SELECT * FROM farming_guides WHERE is_published = 1 ORDER BY sort_order ASC, published_at DESC");
$guides = $stmt->fetchAll();
?>

<section class="py-20 bg-gradient-to-br from-green-50 via-white to-amber-50 agricultural-pattern">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-16">
            <div class="max-w-2xl">
                <h1 class="text-4xl font-bold text-stone-900 mb-4"><?php echo h(get_content('ui', 'guides_title', 'Expert Farming Guides')); ?></h1>
                <p class="text-stone-600"><?php echo h(get_content('ui', 'guides_subtitle', 'Knowledge is the most important seed. Learn how to maximize your harvest with our expert resources.')); ?></p>
            </div>
            <div class="flex gap-4">
                <div class="relative">
                    <input type="text" placeholder="Search guides..." class="bg-white border border-stone-200 rounded-xl px-4 py-3 pl-10 focus:outline-none focus:ring-2 focus:ring-green-500/10 min-w-[250px]">
                    <svg class="w-5 h-5 absolute left-3 top-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($guides as $guide): ?>
            <div class="bg-white rounded-3xl overflow-hidden border border-stone-100 group">
                <div class="relative h-56 bg-stone-200 overflow-hidden">
                    <?php if ($guide['media_path']): ?>
                        <img src="<?php echo h($guide['media_path']); ?>" alt="<?php echo h($guide['title_en']); ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-5xl opacity-20">📖</div>
                    <?php endif; ?>
                    <div class="absolute top-4 right-4 bg-green-600 text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full">
                        <?php echo h($guide['crop_en'] ?: 'General'); ?>
                    </div>
                </div>
                <div class="p-8">
                    <h3 class="text-xl font-bold text-stone-900 mb-4 leading-tight group-hover:text-green-600 transition"><?php echo h($guide['title_en']); ?></h3>
                    <p class="text-stone-500 text-sm mb-6 line-clamp-3"><?php echo h($guide['summary_en']); ?></p>
                    <div class="flex items-center justify-between">
                        <span class="text-stone-400 text-xs font-semibold"><?php echo date('M j, Y', strtotime($guide['published_at'] ?: $guide['created_at'])); ?></span>
                        <a href="guide-details.php?slug=<?php echo $guide['slug']; ?>" class="text-green-600 font-bold flex items-center gap-2 group/link">
                            Read Full Guide
                            <svg class="w-4 h-4 group-hover/link:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
require_once 'includes/site_footer.php';
?>

<?php
require_once 'includes/site_header.php';
require_once 'includes/db.php';

$slug = $_GET['slug'] ?? '';
if (!$slug) { header('Location: guides.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM farming_guides WHERE slug = ? AND is_published = 1");
$stmt->execute([$slug]);
$guide = $stmt->fetch();

if (!$guide) { header('Location: guides.php'); exit; }
?>

<section class="py-20 bg-white">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <div class="mb-12">
            <a href="guides.php" class="text-stone-400 hover:text-stone-900 transition flex items-center gap-2 mb-8">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Guides
            </a>
            <div class="flex items-center gap-3 mb-6">
                <span class="bg-green-100 text-green-700 font-bold px-3 py-1 rounded-lg text-xs tracking-widest uppercase"><?php echo h($guide['crop_en']); ?></span>
                <span class="text-stone-300">•</span>
                <span class="text-stone-400 text-sm font-medium"><?php echo date('F j, Y', strtotime($guide['published_at'] ?: $guide['created_at'])); ?></span>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-stone-900 mb-8 leading-tight"><?php echo h($guide['title_en']); ?></h1>
        </div>

        <?php if ($guide['media_path']): ?>
            <div class="mb-12 rounded-3xl overflow-hidden shadow-2xl">
                <img src="<?php echo h($guide['media_path']); ?>" alt="<?php echo h($guide['title_en']); ?>" class="w-full">
            </div>
        <?php endif; ?>

        <div class="prose prose-lg prose-stone max-w-none text-stone-700 leading-relaxed space-y-6">
            <?php echo $guide['content_en']; ?>
        </div>

        <?php if ($guide['download_file']): ?>
            <div class="mt-16 bg-stone-50 p-10 rounded-3xl border border-stone-100 flex flex-col md:flex-row items-center justify-between gap-8">
                <div>
                    <h3 class="text-2xl font-bold text-stone-900 mb-2">Detailed Tech Sheet</h3>
                    <p class="text-stone-600">Download the full technical guide for offline reading.</p>
                </div>
                <a href="<?php echo h($guide['download_file']); ?>" class="btn-primary rounded-xl px-10 py-4 font-bold shadow-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <?php echo h($guide['download_label'] ?: 'Download PDF'); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
require_once 'includes/site_footer.php';
?>

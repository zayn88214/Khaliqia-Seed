<?php
require_once 'includes/site_header.php';
require_once 'includes/db.php';

$stmt = $pdo->query("SELECT * FROM success_stories WHERE is_active = 1 ORDER BY sort_order ASC, created_at DESC");
$stories = $stmt->fetchAll();
?>

<section class="py-20 bg-gradient-to-br from-green-50 via-white to-amber-50 agricultural-pattern">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-stone-900 mb-4"><?php echo h(get_content('ui', 'success_stories_title', 'Success Stories from the Field')); ?></h1>
            <p class="text-stone-600 max-w-2xl mx-auto"><?php echo h(get_content('ui', 'success_stories_subtitle', 'Real results from real farmers who chose Khaliqia seeds.')); ?></p>
        </div>

        <div class="grid gap-12 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($stories as $story): ?>
            <div class="bg-white rounded-3xl p-8 border border-stone-100 shadow-sm hover:shadow-xl transition duration-500 flex flex-col">
                <div class="mb-8">
                    <div class="text-5xl text-green-500/20 mb-4">“</div>
                    <p class="text-stone-700 italic leading-relaxed text-lg">
                        <?php echo h($story['testimonial_en']); ?>
                    </p>
                </div>
                
                <div class="mt-auto flex items-center gap-4 pt-8 border-t border-stone-50">
                    <div class="w-16 h-16 rounded-2xl bg-stone-100 overflow-hidden shadow-inner">
                        <?php if ($story['image_url']): ?>
                            <img src="<?php echo h($story['image_url']); ?>" alt="<?php echo h($story['farmer_name']); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-2xl opacity-20">👨‍🌾</div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h4 class="font-bold text-stone-900"><?php echo h($story['farmer_name']); ?></h4>
                        <p class="text-stone-400 text-xs font-bold uppercase tracking-widest"><?php echo h($story['region']); ?></p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-green-600 text-xs font-bold bg-green-50 px-2 py-0.5 rounded-full border border-green-100">+<?php echo h($story['yield_increase_percent']); ?>% Yield</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-20 glass p-12 rounded-3xl text-center">
            <h2 class="text-3xl font-bold text-stone-900 mb-4"><?php echo h(get_content('success', 'cta_title', 'Ready to Write Your Own Success Story?')); ?></h2>
            <p class="text-stone-600 mb-8 max-w-xl mx-auto"><?php echo h(get_content('success', 'cta_description', 'Join the community of flourishing farmers and see the difference quality seeds can make.')); ?></p>
            <a href="contact.php" class="btn-primary px-10 py-4 rounded-xl font-bold font-semibold shadow-lg inline-block"><?php echo h(get_content('success', 'cta_button', 'Consult Our Experts')); ?></a>
        </div>
    </div>
</section>

<?php
require_once 'includes/site_footer.php';
?>

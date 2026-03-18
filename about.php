<?php
require_once 'includes/site_header.php';
?>

<section class="py-20 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center mb-16">
            <h1 class="text-4xl font-bold text-stone-900 mb-6"><?php echo h(get_content('about', 'title', 'About Khaliqia Seed Corporation')); ?></h1>
            <p class="text-lg text-stone-600 leading-relaxed">
                <?php echo h(get_content('about', 'description', 'Khaliqia Seed Corporation is a leader in the agricultural sector, dedicated to providing high-quality seeds to farmers.')); ?>
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="rounded-3xl overflow-hidden shadow-2xl">
                <img src="<?php echo h(get_content('about', 'image_url', 'https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?auto=format&fit=crop&q=80&w=800')); ?>" alt="Farming" class="w-full h-full object-cover">
            </div>
            <div class="space-y-6">
                <h2 class="text-3xl font-bold text-stone-900"><?php echo h(get_content('about', 'vision_title', 'Our Vision')); ?></h2>
                <p class="text-stone-600 leading-relaxed">
                    <?php echo h(get_content('about', 'vision_text', 'To revolutionize agriculture through innovation and sustainable practices.')); ?>
                </p>
                <h2 class="text-3xl font-bold text-stone-900"><?php echo h(get_content('about', 'mission_title', 'Our Mission')); ?></h2>
                <p class="text-stone-600 leading-relaxed">
                    <?php echo h(get_content('about', 'mission_text', 'To empower every farmer with the best seeds and knowledge to grow better harvests.')); ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CEO / Leadership Section -->
<section class="py-20 bg-gradient-to-br from-green-50 via-white to-amber-50 agricultural-pattern">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-stone-900 mb-4"><?php echo h(get_content('about', 'leadership_title', 'Our Leadership')); ?></h2>
            <p class="text-stone-600 max-w-2xl mx-auto"><?php echo h(get_content('about', 'leadership_subtitle', 'Meet the visionary behind Khaliqia Seed Corporation.')); ?></p>
        </div>
        
        <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-xl overflow-hidden border border-stone-100">
            <div class="grid md:grid-cols-5 gap-0">
                <div class="md:col-span-2 bg-gradient-to-br from-green-800 to-green-950 flex items-center justify-center p-8">
                    <?php $ceo_image = get_content('about', 'ceo_image', ''); ?>
                    <?php if ($ceo_image): ?>
                        <img src="<?php echo h($ceo_image); ?>" alt="<?php echo h(get_content('about', 'ceo_name', 'CEO')); ?>" class="w-48 h-48 rounded-2xl object-cover shadow-2xl border-4 border-white/20">
                    <?php else: ?>
                        <div class="w-48 h-48 rounded-2xl bg-white/10 flex items-center justify-center border-4 border-white/20">
                            <span class="text-6xl">👤</span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="md:col-span-3 p-8 md:p-12 flex flex-col justify-center">
                    <div class="inline-flex items-center gap-2 bg-green-50 text-green-700 text-xs font-bold px-3 py-1.5 rounded-full mb-4 w-fit uppercase tracking-wider">
                        🏆 <?php echo h(get_content('about', 'ceo_role', 'Chief Executive Officer')); ?>
                    </div>
                    <h3 class="text-2xl md:text-3xl font-bold text-stone-900 mb-2"><?php echo h(get_content('about', 'ceo_name', 'Muhammad Khaliq')); ?></h3>
                    <p class="text-green-600 font-semibold mb-6"><?php echo h(get_content('about', 'ceo_tagline', 'Founder & CEO — Leading agricultural innovation since 2000')); ?></p>
                    <p class="text-stone-600 leading-relaxed mb-6">
                        <?php echo h(get_content('about', 'ceo_bio', 'With over 25 years of experience in the agricultural sector, our CEO has dedicated his life to improving farming outcomes across Pakistan. Under his leadership, Khaliqia Seed Corporation has grown from a small local seed supplier to a nationally recognized name in agricultural excellence. His vision of empowering every farmer with quality seeds continues to drive the company forward.')); ?>
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <div class="bg-green-50 rounded-xl px-5 py-3 border border-green-100">
                            <div class="text-xl font-bold text-green-600"><?php echo h(get_content('about', 'ceo_stat_1_value', '25+')); ?></div>
                            <div class="text-xs text-stone-500 font-semibold"><?php echo h(get_content('about', 'ceo_stat_1_label', 'Years in Agriculture')); ?></div>
                        </div>
                        <div class="bg-amber-50 rounded-xl px-5 py-3 border border-amber-100">
                            <div class="text-xl font-bold text-amber-700"><?php echo h(get_content('about', 'ceo_stat_2_value', '5')); ?></div>
                            <div class="text-xs text-stone-500 font-semibold"><?php echo h(get_content('about', 'ceo_stat_2_label', 'Industry Awards')); ?></div>
                        </div>
                        <div class="bg-lime-50 rounded-xl px-5 py-3 border border-lime-100">
                            <div class="text-xl font-bold text-lime-700"><?php echo h(get_content('about', 'ceo_stat_3_value', '50+')); ?></div>
                            <div class="text-xs text-stone-500 font-semibold"><?php echo h(get_content('about', 'ceo_stat_3_label', 'R&D Projects Led')); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once 'includes/site_footer.php';
?>

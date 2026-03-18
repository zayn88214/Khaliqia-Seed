<?php
require_once 'includes/site_header.php';
require_once 'includes/db.php';

$stmt = $pdo->query("SELECT * FROM crop_calendars WHERE is_active = 1 ORDER BY sowing_start_month ASC");
$calendars = $stmt->fetchAll();

function get_month_name($num) {
    return date("F", mktime(0, 0, 0, $num, 10));
}
?>

<section class="py-20 bg-gradient-to-br from-green-50 via-white to-amber-50 agricultural-pattern">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-stone-900 mb-4"><?php echo h(get_content('ui', 'calendar_title', 'Regional Sowing & Harvest Calendar')); ?></h1>
            <p class="text-stone-600 max-w-2xl mx-auto"><?php echo h(get_content('ui', 'calendar_subtitle', 'Stay ahead of the seasons with our optimized agricultural timeline for various regions.')); ?></p>
        </div>

        <div class="grid gap-8">
            <div class="bg-white rounded-[40px] shadow-2xl overflow-hidden border border-white">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-green-600 text-white">
                                <th class="p-8 font-bold">Region</th>
                                <th class="p-8 font-bold">Crop</th>
                                <th class="p-8 font-bold">Sowing Period</th>
                                <th class="p-8 font-bold">Harvesting Period</th>
                                <th class="p-8 font-bold">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            <?php foreach ($calendars as $cal): ?>
                            <tr class="hover:bg-green-50/50 transition duration-300">
                                <td class="p-8">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                        <span class="font-bold text-stone-900"><?php echo h($cal['region']); ?></span>
                                    </div>
                                </td>
                                <td class="p-8">
                                    <span class="bg-stone-100 text-stone-700 px-4 py-1.5 rounded-full text-sm font-bold shadow-sm">
                                        <?php echo h($cal['crop_name_en']); ?>
                                    </span>
                                </td>
                                <td class="p-8">
                                    <div class="flex items-center gap-2">
                                        <span class="text-stone-400">📅</span>
                                        <span class="text-stone-600 font-medium"><?php echo get_month_name($cal['sowing_start_month']); ?> - <?php echo get_month_name($cal['sowing_end_month']); ?></span>
                                    </div>
                                </td>
                                <td class="p-8">
                                    <div class="flex items-center gap-2">
                                        <span class="text-amber-500">🌾</span>
                                        <span class="text-stone-600 font-medium"><?php echo get_month_name($cal['harvest_start_month']); ?> - <?php echo get_month_name($cal['harvest_end_month']); ?></span>
                                    </div>
                                </td>
                                <td class="p-8">
                                    <a href="contact.php?interest=<?php echo urlencode($cal['crop_name_en']); ?>" class="text-green-600 font-bold hover:underline">Inquire</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-20 grid md:grid-cols-2 gap-12 items-center bg-gradient-to-br from-green-50 to-lime-50 border border-green-200 rounded-[40px] p-12 md:p-20 text-stone-900 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-64 h-64 bg-green-500/10 rounded-full -mr-32 -mt-32 blur-3xl"></div>
            <div>
                <h2 class="text-4xl font-bold mb-6"><?php echo h(get_content('calendar', 'cta_title', 'Need a Custom Planting Schedule?')); ?></h2>
                <p class="text-stone-600 text-lg mb-10"><?php echo h(get_content('calendar', 'cta_description', 'Our agricultural specialists can provide personalized calendars based on your specific soil and micro-climate conditions.')); ?></p>
                <div class="flex gap-4">
                    <a href="contact.php" class="bg-green-600 text-white rounded-xl px-8 py-4 font-bold hover:bg-green-700 transition shadow-xl"><?php echo h(get_content('calendar', 'cta_button', 'Speak to an Expert')); ?></a>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white p-8 rounded-3xl border border-slate-200 text-center shadow-sm">
                    <div class="text-3xl font-bold text-green-600 mb-1"><?php echo h(get_content('calendar', 'stat_1_value', '100%')); ?></div>
                    <div class="text-xs text-stone-500 uppercase font-bold tracking-widest"><?php echo h(get_content('calendar', 'stat_1_label', 'Accuracy')); ?></div>
                </div>
                <div class="bg-white p-8 rounded-3xl border border-slate-200 text-center shadow-sm">
                    <div class="text-3xl font-bold text-green-600 mb-1"><?php echo h(get_content('calendar', 'stat_2_value', '50+')); ?></div>
                    <div class="text-xs text-stone-500 uppercase font-bold tracking-widest"><?php echo h(get_content('calendar', 'stat_2_label', 'Regions')); ?></div>
                </div>
                <div class="bg-white p-8 rounded-3xl border border-slate-200 text-center shadow-sm">
                    <div class="text-3xl font-bold text-green-600 mb-1"><?php echo h(get_content('calendar', 'stat_3_value', '24/7')); ?></div>
                    <div class="text-xs text-stone-500 uppercase font-bold tracking-widest"><?php echo h(get_content('calendar', 'stat_3_label', 'Support')); ?></div>
                </div>
                <div class="bg-white p-8 rounded-3xl border border-slate-200 text-center shadow-sm">
                    <div class="text-3xl font-bold text-green-600 mb-1"><?php echo h(get_content('calendar', 'stat_4_value', 'Live')); ?></div>
                    <div class="text-xs text-stone-500 uppercase font-bold tracking-widest"><?php echo h(get_content('calendar', 'stat_4_label', 'Updates')); ?></div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once 'includes/site_footer.php';
?>

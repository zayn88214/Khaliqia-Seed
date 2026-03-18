<?php
include 'includes/header.php';
require_once __DIR__ . '/../includes/db.php';

function month_name($n) { return date("F", mktime(0,0,0,$n,10)); }

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: manage-calendars.php'); exit; }

if (isset($_POST['update'])) {
    $fields = [
        'region' => trim($_POST['region']),
        'crop_name_en' => trim($_POST['crop_name_en']),
        'sowing_start_month' => (int)$_POST['sowing_start_month'],
        'sowing_end_month' => (int)$_POST['sowing_end_month'],
        'harvest_start_month' => (int)$_POST['harvest_start_month'],
        'harvest_end_month' => (int)$_POST['harvest_end_month'],
        'notes_en' => trim($_POST['notes_en']),
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
    ];
    $set = implode(', ', array_map(fn($k) => "$k = ?", array_keys($fields)));
    $pdo->prepare("UPDATE crop_calendars SET {$set}, updated_at = NOW() WHERE id = ?")->execute([...array_values($fields), $id]);
    $success = "Calendar entry updated!";
}

$stmt = $pdo->prepare("SELECT * FROM crop_calendars WHERE id = ?");
$stmt->execute([$id]);
$cal = $stmt->fetch();
if (!$cal) { header('Location: manage-calendars.php'); exit; }
?>

<div class="mb-8">
    <a href="manage-calendars.php" class="text-slate-500 hover:text-slate-900 flex items-center gap-2 mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Calendar
    </a>
    <h2 class="text-2xl font-bold text-slate-900">Edit Calendar Entry</h2>
</div>

<?php if (isset($success)): ?>
    <div class="alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<div class="admin-card max-w-2xl p-8">
    <form method="POST" class="space-y-6">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Region</label>
                <input type="text" name="region" value="<?php echo h($cal['region']); ?>" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Crop Name</label>
                <input type="text" name="crop_name_en" value="<?php echo h($cal['crop_name_en']); ?>" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Sowing Start Month</label>
                <select name="sowing_start_month" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                    <?php for ($m=1;$m<=12;$m++): ?><option value="<?php echo $m; ?>" <?php echo $cal['sowing_start_month']==$m?'selected':''; ?>><?php echo month_name($m); ?></option><?php endfor; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Sowing End Month</label>
                <select name="sowing_end_month" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                    <?php for ($m=1;$m<=12;$m++): ?><option value="<?php echo $m; ?>" <?php echo $cal['sowing_end_month']==$m?'selected':''; ?>><?php echo month_name($m); ?></option><?php endfor; ?>
                </select>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Harvest Start Month</label>
                <select name="harvest_start_month" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                    <?php for ($m=1;$m<=12;$m++): ?><option value="<?php echo $m; ?>" <?php echo $cal['harvest_start_month']==$m?'selected':''; ?>><?php echo month_name($m); ?></option><?php endfor; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Harvest End Month</label>
                <select name="harvest_end_month" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                    <?php for ($m=1;$m<=12;$m++): ?><option value="<?php echo $m; ?>" <?php echo $cal['harvest_end_month']==$m?'selected':''; ?>><?php echo month_name($m); ?></option><?php endfor; ?>
                </select>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-2">Notes</label>
            <textarea name="notes_en" rows="3" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500"><?php echo h($cal['notes_en']); ?></textarea>
        </div>
        <div class="flex items-center">
            <label class="flex items-center gap-2 text-slate-700 cursor-pointer">
                <input type="checkbox" name="is_active" <?php echo $cal['is_active'] ? 'checked' : ''; ?> class="w-5 h-5 rounded bg-white border-slate-300 text-green-500 focus:ring-green-500/30">
                Active
            </label>
        </div>
        <div class="flex gap-4">
            <button type="submit" name="update" class="flex-1 btn-green text-white font-bold py-3.5 rounded-xl shadow-lg">Update Entry</button>
            <a href="manage-calendars.php" class="flex-1 bg-slate-100 text-center text-slate-600 font-bold py-3.5 rounded-xl hover:bg-slate-200 transition">Cancel</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

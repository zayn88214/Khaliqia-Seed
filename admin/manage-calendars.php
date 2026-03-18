<?php
include 'includes/header.php';
require_once __DIR__ . '/../includes/db.php';

function month_name($n) { return date("F", mktime(0,0,0,$n,10)); }

if (isset($_POST['add_calendar'])) {
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
    try {
        $cols = implode(', ', array_keys($fields));
        $placeholders = implode(', ', array_fill(0, count($fields), '?'));
        $pdo->prepare("INSERT INTO crop_calendars ({$cols}, created_at, updated_at) VALUES ({$placeholders}, NOW(), NOW())")->execute(array_values($fields));
        $success = "Calendar entry added!";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM crop_calendars WHERE id = ?")->execute([(int)$_GET['delete']]);
    header('Location: manage-calendars.php?msg=deleted');
    exit;
}
if (isset($_GET['msg']) && $_GET['msg'] === 'deleted') $success = "Entry deleted!";

$calendars = $pdo->query("SELECT * FROM crop_calendars ORDER BY sowing_start_month ASC")->fetchAll();
?>

<div class="flex justify-between items-center mb-8">
    <h2 class="text-2xl font-bold text-slate-900">Manage Crop Calendar</h2>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="btn-green text-white px-6 py-2.5 rounded-xl font-bold flex items-center gap-2">
        <span>+</span> Add Entry
    </button>
</div>

<?php if (isset($success)): ?>
    <div class="alert-success"><?php echo $success; ?></div>
<?php endif; ?>
<?php if (isset($error)): ?>
    <div class="alert-error"><?php echo $error; ?></div>
<?php endif; ?>

<div class="admin-card overflow-hidden">
    <table class="w-full text-left">
        <thead>
            <tr class="text-slate-500 text-sm border-b border-slate-100 bg-slate-50">
                <th class="p-4 font-medium">Region</th>
                <th class="p-4 font-medium">Crop</th>
                <th class="p-4 font-medium">Sowing</th>
                <th class="p-4 font-medium">Harvest</th>
                <th class="p-4 font-medium">Status</th>
                <th class="p-4 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($calendars as $row): ?>
            <tr class="text-slate-600 hover:bg-slate-50 transition">
                <td class="p-4 font-medium text-slate-900"><?php echo h($row['region']); ?></td>
                <td class="p-4"><?php echo h($row['crop_name_en']); ?></td>
                <td class="p-4 text-sm"><?php echo month_name($row['sowing_start_month']); ?> - <?php echo month_name($row['sowing_end_month']); ?></td>
                <td class="p-4 text-sm"><?php echo month_name($row['harvest_start_month']); ?> - <?php echo month_name($row['harvest_end_month']); ?></td>
                <td class="p-4">
                    <span class="px-2 py-1 rounded-full text-xs font-bold <?php echo $row['is_active'] ? 'bg-green-50 text-green-600 border border-green-200' : 'bg-red-50 text-red-600 border border-red-200'; ?>">
                        <?php echo $row['is_active'] ? 'Active' : 'Inactive'; ?>
                    </span>
                </td>
                <td class="p-4">
                    <a href="edit-calendar.php?id=<?php echo $row['id']; ?>" class="text-blue-600 hover:text-blue-800 mr-4">Edit</a>
                    <a href="manage-calendars.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete?')" class="text-red-500 hover:text-red-700">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($calendars)): ?>
            <tr><td colspan="6" class="p-10 text-center text-slate-500">No entries found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50 overflow-y-auto">
    <div class="bg-white max-w-lg w-full p-8 rounded-3xl shadow-2xl border border-slate-200 my-8">
        <h3 class="text-2xl font-bold text-slate-900 mb-6">Add Calendar Entry</h3>
        <form method="POST" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Region</label>
                    <input type="text" name="region" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Crop Name</label>
                    <input type="text" name="crop_name_en" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Sowing Start Month</label>
                    <select name="sowing_start_month" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                        <?php for ($m=1;$m<=12;$m++): ?><option value="<?php echo $m; ?>"><?php echo month_name($m); ?></option><?php endfor; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Sowing End Month</label>
                    <select name="sowing_end_month" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                        <?php for ($m=1;$m<=12;$m++): ?><option value="<?php echo $m; ?>"><?php echo month_name($m); ?></option><?php endfor; ?>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Harvest Start Month</label>
                    <select name="harvest_start_month" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                        <?php for ($m=1;$m<=12;$m++): ?><option value="<?php echo $m; ?>"><?php echo month_name($m); ?></option><?php endfor; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Harvest End Month</label>
                    <select name="harvest_end_month" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                        <?php for ($m=1;$m<=12;$m++): ?><option value="<?php echo $m; ?>"><?php echo month_name($m); ?></option><?php endfor; ?>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Notes</label>
                <textarea name="notes_en" rows="3" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500"></textarea>
            </div>
            <div class="flex items-center">
                <label class="flex items-center gap-2 text-slate-700 cursor-pointer">
                    <input type="checkbox" name="is_active" checked class="w-5 h-5 rounded bg-white border-slate-300 text-green-500 focus:ring-green-500/30">
                    Active
                </label>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="flex-1 bg-slate-100 text-slate-600 font-bold py-3 rounded-xl hover:bg-slate-200 transition">Cancel</button>
                <button type="submit" name="add_calendar" class="flex-1 btn-green text-white font-bold py-3 rounded-xl shadow-lg">Save Entry</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

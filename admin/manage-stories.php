<?php
include 'includes/header.php';
require_once __DIR__ . '/../includes/db.php';

if (isset($_POST['add_story'])) {
    $fields = [
        'farmer_name' => trim($_POST['farmer_name']),
        'region' => trim($_POST['region']),
        'crop_type' => trim($_POST['crop_type']),
        'yield_increase_percent' => (int)$_POST['yield_increase_percent'],
        'testimonial_en' => trim($_POST['testimonial_en']),
        'title_en' => trim($_POST['title_en']),
        'description_en' => trim($_POST['description_en']),
        'image_url' => trim($_POST['image_url']),
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
        'sort_order' => (int)($_POST['sort_order'] ?? 0),
    ];
    try {
        $cols = implode(', ', array_keys($fields));
        $placeholders = implode(', ', array_fill(0, count($fields), '?'));
        $pdo->prepare("INSERT INTO success_stories ({$cols}, created_at, updated_at) VALUES ({$placeholders}, NOW(), NOW())")->execute(array_values($fields));
        $success = "Story added!";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

if (isset($_GET['delete'])) {
    try {
        $pdo->prepare("DELETE FROM success_stories WHERE id = ?")->execute([(int)$_GET['delete']]);
        header('Location: manage-stories.php?msg=deleted');
        exit;
    } catch (PDOException $e) {
        $error = "Delete failed: " . $e->getMessage();
    }
}
if (isset($_GET['msg']) && $_GET['msg'] === 'deleted') $success = "Story deleted!";

$stories = $pdo->query("SELECT * FROM success_stories ORDER BY sort_order ASC, created_at DESC")->fetchAll();
?>

<div class="flex justify-between items-center mb-8">
    <h2 class="text-2xl font-bold text-slate-900">Manage Success Stories</h2>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="btn-green text-white px-6 py-2.5 rounded-xl font-bold flex items-center gap-2">
        <span>+</span> Add Story
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
                <th class="p-4 font-medium">Farmer</th>
                <th class="p-4 font-medium">Region</th>
                <th class="p-4 font-medium">Crop</th>
                <th class="p-4 font-medium">Yield +%</th>
                <th class="p-4 font-medium">Status</th>
                <th class="p-4 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($stories as $row): ?>
            <tr class="text-slate-600 hover:bg-slate-50 transition">
                <td class="p-4 font-medium text-slate-900"><?php echo h($row['farmer_name']); ?></td>
                <td class="p-4"><?php echo h($row['region']); ?></td>
                <td class="p-4"><?php echo h($row['crop_type']); ?></td>
                <td class="p-4"><span class="text-green-600 font-bold">+<?php echo $row['yield_increase_percent']; ?>%</span></td>
                <td class="p-4">
                    <span class="px-2 py-1 rounded-full text-xs font-bold <?php echo $row['is_active'] ? 'bg-green-50 text-green-600 border border-green-200' : 'bg-red-50 text-red-600 border border-red-200'; ?>">
                        <?php echo $row['is_active'] ? 'Active' : 'Inactive'; ?>
                    </span>
                </td>
                <td class="p-4">
                    <a href="edit-story.php?id=<?php echo $row['id']; ?>" class="text-blue-600 hover:text-blue-800 mr-4">Edit</a>
                    <a href="manage-stories.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete?')" class="text-red-500 hover:text-red-700">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($stories)): ?>
            <tr><td colspan="6" class="p-10 text-center text-slate-500">No stories found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50 overflow-y-auto">
    <div class="bg-white max-w-2xl w-full p-8 rounded-3xl shadow-2xl border border-slate-200 my-8">
        <h3 class="text-2xl font-bold text-slate-900 mb-6">Add Success Story</h3>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Title</label>
                <input type="text" name="title_en" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Farmer Name</label>
                    <input type="text" name="farmer_name" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Region</label>
                    <input type="text" name="region" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Crop Type</label>
                    <input type="text" name="crop_type" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Yield Increase %</label>
                    <input type="number" name="yield_increase_percent" value="0" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Testimonial</label>
                <textarea name="testimonial_en" rows="4" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Description</label>
                <textarea name="description_en" rows="3" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Farmer Image URL</label>
                <input type="text" name="image_url" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="0" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                </div>
                <div class="flex items-end pb-1">
                    <label class="flex items-center gap-2 text-slate-700 cursor-pointer">
                        <input type="checkbox" name="is_active" checked class="w-5 h-5 rounded bg-white border-slate-300 text-green-500 focus:ring-green-500/30">
                        Active
                    </label>
                </div>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="flex-1 bg-slate-100 text-slate-600 font-bold py-3 rounded-xl hover:bg-slate-200 transition">Cancel</button>
                <button type="submit" name="add_story" class="flex-1 btn-green text-white font-bold py-3 rounded-xl shadow-lg">Save Story</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

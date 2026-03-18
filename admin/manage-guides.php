<?php
include 'includes/header.php';
require_once __DIR__ . '/../includes/db.php';

if (isset($_POST['add_guide'])) {
    $fields = [
        'title_en' => trim($_POST['title_en']),
        'slug' => trim($_POST['slug']) ?: preg_replace('/[^a-z0-9]+/', '-', strtolower(trim($_POST['title_en']))),
        'crop_en' => trim($_POST['crop_en']),
        'summary_en' => trim($_POST['summary_en']),
        'content_en' => $_POST['content_en'],
        'media_path' => trim($_POST['media_path']),
        'download_file' => trim($_POST['download_file']),
        'download_label' => trim($_POST['download_label']),
        'is_published' => isset($_POST['is_published']) ? 1 : 0,
        'sort_order' => (int)($_POST['sort_order'] ?? 0),
        'published_at' => date('Y-m-d H:i:s'),
    ];
    try {
        $cols = implode(', ', array_keys($fields));
        $placeholders = implode(', ', array_fill(0, count($fields), '?'));
        $pdo->prepare("INSERT INTO farming_guides ({$cols}, created_at, updated_at) VALUES ({$placeholders}, NOW(), NOW())")->execute(array_values($fields));
        $success = "Guide added!";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

if (isset($_GET['delete'])) {
    try {
        $pdo->prepare("DELETE FROM farming_guides WHERE id = ?")->execute([(int)$_GET['delete']]);
        header('Location: manage-guides.php?msg=deleted');
        exit;
    } catch (PDOException $e) {
        $error = "Delete failed: " . $e->getMessage();
    }
}
if (isset($_GET['msg']) && $_GET['msg'] === 'deleted') $success = "Guide deleted!";

$guides = $pdo->query("SELECT * FROM farming_guides ORDER BY sort_order ASC, published_at DESC")->fetchAll();
?>

<div class="flex justify-between items-center mb-8">
    <h2 class="text-2xl font-bold text-slate-900">Manage Farming Guides</h2>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="btn-green text-white px-6 py-2.5 rounded-xl font-bold flex items-center gap-2">
        <span>+</span> Add Guide
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
                <th class="p-4 font-medium">Title</th>
                <th class="p-4 font-medium">Crop</th>
                <th class="p-4 font-medium">Published</th>
                <th class="p-4 font-medium">Status</th>
                <th class="p-4 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($guides as $row): ?>
            <tr class="text-slate-600 hover:bg-slate-50 transition">
                <td class="p-4 font-medium text-slate-900"><?php echo h($row['title_en']); ?></td>
                <td class="p-4"><span class="bg-slate-100 text-slate-700 text-xs px-2 py-1 rounded-md"><?php echo h($row['crop_en'] ?: 'General'); ?></span></td>
                <td class="p-4 text-sm"><?php echo $row['published_at'] ? date('M j, Y', strtotime($row['published_at'])) : '-'; ?></td>
                <td class="p-4">
                    <span class="px-2 py-1 rounded-full text-xs font-bold <?php echo $row['is_published'] ? 'bg-green-50 text-green-600 border border-green-200' : 'bg-yellow-50 text-yellow-600 border border-yellow-200'; ?>">
                        <?php echo $row['is_published'] ? 'Published' : 'Draft'; ?>
                    </span>
                </td>
                <td class="p-4">
                    <a href="edit-guide.php?id=<?php echo $row['id']; ?>" class="text-blue-600 hover:text-blue-800 mr-4">Edit</a>
                    <a href="manage-guides.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete?')" class="text-red-500 hover:text-red-700">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($guides)): ?>
            <tr><td colspan="5" class="p-10 text-center text-slate-500">No guides found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50 overflow-y-auto">
    <div class="bg-white max-w-2xl w-full p-8 rounded-3xl shadow-2xl border border-slate-200 my-8">
        <h3 class="text-2xl font-bold text-slate-900 mb-6">Add Guide</h3>
        <form method="POST" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Title</label>
                    <input type="text" name="title_en" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Slug (auto if empty)</label>
                    <input type="text" name="slug" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500 font-mono">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Crop</label>
                <input type="text" name="crop_en" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Summary</label>
                <textarea name="summary_en" rows="2" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Content (HTML allowed)</label>
                <textarea name="content_en" rows="8" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Image URL</label>
                    <input type="text" name="media_path" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Download File URL</label>
                    <input type="text" name="download_file" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Download Label</label>
                    <input type="text" name="download_label" value="Download PDF" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="0" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                </div>
                <div class="flex items-end pb-1">
                    <label class="flex items-center gap-2 text-slate-700 cursor-pointer">
                        <input type="checkbox" name="is_published" checked class="w-5 h-5 rounded bg-white border-slate-300 text-green-500 focus:ring-green-500/30">
                        Published
                    </label>
                </div>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="flex-1 bg-slate-100 text-slate-600 font-bold py-3 rounded-xl hover:bg-slate-200 transition">Cancel</button>
                <button type="submit" name="add_guide" class="flex-1 btn-green text-white font-bold py-3 rounded-xl shadow-lg">Save Guide</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

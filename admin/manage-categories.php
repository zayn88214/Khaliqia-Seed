<?php
include 'includes/header.php';
require_once __DIR__ . '/../includes/db.php';

// Handle add
if (isset($_POST['add_category'])) {
    $name_en = trim($_POST['name_en']);
    $slug = trim($_POST['slug']) ?: preg_replace('/[^a-z0-9]+/', '-', strtolower($name_en));
    $description_en = trim($_POST['description_en']);
    $image = trim($_POST['image']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $sort_order = (int)($_POST['sort_order'] ?? 0);

    try {
        $stmt = $pdo->prepare("INSERT INTO categories (name_en, slug, description_en, image, is_active, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([$name_en, $slug, $description_en, $image, $is_active, $sort_order]);
        $success = "Category added successfully!";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
    header('Location: manage-categories.php?msg=deleted');
    exit;
}

if (isset($_GET['msg']) && $_GET['msg'] === 'deleted') $success = "Category deleted!";

$categories = $pdo->query("SELECT * FROM categories ORDER BY sort_order ASC, name_en ASC")->fetchAll();
?>

<div class="flex justify-between items-center mb-8">
    <h2 class="text-2xl font-bold text-slate-900">Manage Categories</h2>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="btn-green text-white px-6 py-2.5 rounded-xl font-bold flex items-center gap-2">
        <span>+</span> Add Category
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
                <th class="p-4 font-medium">Name</th>
                <th class="p-4 font-medium">Slug</th>
                <th class="p-4 font-medium">Order</th>
                <th class="p-4 font-medium">Status</th>
                <th class="p-4 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($categories as $row): ?>
            <tr class="text-slate-600 hover:bg-slate-50 transition">
                <td class="p-4 font-medium text-slate-900"><?php echo h($row['name_en']); ?></td>
                <td class="p-4 font-mono text-xs"><?php echo h($row['slug']); ?></td>
                <td class="p-4"><?php echo $row['sort_order']; ?></td>
                <td class="p-4">
                    <span class="px-2 py-1 rounded-full text-xs font-bold <?php echo $row['is_active'] ? 'bg-green-50 text-green-600 border border-green-200' : 'bg-red-50 text-red-600 border border-red-200'; ?>">
                        <?php echo $row['is_active'] ? 'Active' : 'Inactive'; ?>
                    </span>
                </td>
                <td class="p-4">
                    <a href="edit-category.php?id=<?php echo $row['id']; ?>" class="text-blue-600 hover:text-blue-800 mr-4">Edit</a>
                    <a href="manage-categories.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this category?')" class="text-red-500 hover:text-red-700">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($categories)): ?>
            <tr><td colspan="5" class="p-10 text-center text-slate-500">No categories found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50">
    <div class="bg-white max-w-lg w-full p-8 rounded-3xl shadow-2xl border border-slate-200">
        <h3 class="text-2xl font-bold text-slate-900 mb-6">Add Category</h3>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Name (English)</label>
                <input type="text" name="name_en" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Slug (auto-generated if empty)</label>
                <input type="text" name="slug" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500 font-mono">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Description</label>
                <textarea name="description_en" rows="3" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Image URL</label>
                <input type="text" name="image" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
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
                <button type="submit" name="add_category" class="flex-1 btn-green text-white font-bold py-3 rounded-xl shadow-lg">Save</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

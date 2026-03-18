<?php
include 'includes/header.php';
require_once __DIR__ . '/../includes/db.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: manage-categories.php'); exit; }

if (isset($_POST['update'])) {
    $name_en = trim($_POST['name_en']);
    $slug = trim($_POST['slug']) ?: preg_replace('/[^a-z0-9]+/', '-', strtolower($name_en));
    $description_en = trim($_POST['description_en']);
    $image = trim($_POST['image']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $sort_order = (int)($_POST['sort_order'] ?? 0);

    $stmt = $pdo->prepare("UPDATE categories SET name_en=?, slug=?, description_en=?, image=?, is_active=?, sort_order=?, updated_at=NOW() WHERE id=?");
    $stmt->execute([$name_en, $slug, $description_en, $image, $is_active, $sort_order, $id]);
    $success = "Category updated!";
}

$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$id]);
$cat = $stmt->fetch();
if (!$cat) { header('Location: manage-categories.php'); exit; }
?>

<div class="mb-8">
    <a href="manage-categories.php" class="text-slate-500 hover:text-slate-900 flex items-center gap-2 mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Categories
    </a>
    <h2 class="text-2xl font-bold text-slate-900">Edit Category</h2>
</div>

<?php if (isset($success)): ?>
    <div class="alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<div class="admin-card max-w-2xl p-8">
    <form method="POST" class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-2">Name (English)</label>
            <input type="text" name="name_en" value="<?php echo h($cat['name_en']); ?>" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-2">Slug</label>
            <input type="text" name="slug" value="<?php echo h($cat['slug']); ?>" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500 font-mono">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-2">Description</label>
            <textarea name="description_en" rows="3" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500"><?php echo h($cat['description_en']); ?></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-2">Image URL</label>
            <input type="text" name="image" value="<?php echo h($cat['image']); ?>" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Sort Order</label>
                <input type="number" name="sort_order" value="<?php echo $cat['sort_order']; ?>" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
            <div class="flex items-end pb-1">
                <label class="flex items-center gap-2 text-slate-700 cursor-pointer">
                    <input type="checkbox" name="is_active" <?php echo $cat['is_active'] ? 'checked' : ''; ?> class="w-5 h-5 rounded bg-white border-slate-300 text-green-500 focus:ring-green-500/30">
                    Active
                </label>
            </div>
        </div>
        <div class="flex gap-4">
            <button type="submit" name="update" class="flex-1 btn-green text-white font-bold py-3.5 rounded-xl shadow-lg">Update Category</button>
            <a href="manage-categories.php" class="flex-1 bg-slate-100 text-center text-slate-600 font-bold py-3.5 rounded-xl hover:bg-slate-200 transition">Cancel</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

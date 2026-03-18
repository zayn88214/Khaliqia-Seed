<?php
include 'includes/header.php';
require_once __DIR__ . '/../includes/db.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: manage-guides.php'); exit; }

if (isset($_POST['update'])) {
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
    ];
    $set = implode(', ', array_map(fn($k) => "$k = ?", array_keys($fields)));
    $pdo->prepare("UPDATE farming_guides SET {$set}, updated_at = NOW() WHERE id = ?")->execute([...array_values($fields), $id]);
    $success = "Guide updated!";
}

$stmt = $pdo->prepare("SELECT * FROM farming_guides WHERE id = ?");
$stmt->execute([$id]);
$guide = $stmt->fetch();
if (!$guide) { header('Location: manage-guides.php'); exit; }
?>

<div class="mb-8">
    <a href="manage-guides.php" class="text-slate-500 hover:text-slate-900 flex items-center gap-2 mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Guides
    </a>
    <h2 class="text-2xl font-bold text-slate-900">Edit Guide</h2>
</div>

<?php if (isset($success)): ?>
    <div class="alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<div class="admin-card max-w-3xl p-8">
    <form method="POST" class="space-y-6">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Title</label>
                <input type="text" name="title_en" value="<?php echo h($guide['title_en']); ?>" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Slug</label>
                <input type="text" name="slug" value="<?php echo h($guide['slug']); ?>" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500 font-mono">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-2">Crop</label>
            <input type="text" name="crop_en" value="<?php echo h($guide['crop_en']); ?>" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-2">Summary</label>
            <textarea name="summary_en" rows="3" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500"><?php echo h($guide['summary_en']); ?></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-2">Content (HTML allowed)</label>
            <textarea name="content_en" rows="12" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500"><?php echo h($guide['content_en']); ?></textarea>
        </div>
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Image URL</label>
                <input type="text" name="media_path" value="<?php echo h($guide['media_path']); ?>" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Download File URL</label>
                <input type="text" name="download_file" value="<?php echo h($guide['download_file']); ?>" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
        </div>
        <div class="grid grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Download Label</label>
                <input type="text" name="download_label" value="<?php echo h($guide['download_label']); ?>" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Sort Order</label>
                <input type="number" name="sort_order" value="<?php echo $guide['sort_order']; ?>" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
            <div class="flex items-end pb-1">
                <label class="flex items-center gap-2 text-slate-700 cursor-pointer">
                    <input type="checkbox" name="is_published" <?php echo $guide['is_published'] ? 'checked' : ''; ?> class="w-5 h-5 rounded bg-white border-slate-300 text-green-500 focus:ring-green-500/30">
                    Published
                </label>
            </div>
        </div>
        <div class="flex gap-4">
            <button type="submit" name="update" class="flex-1 btn-green text-white font-bold py-3.5 rounded-xl shadow-lg">Update Guide</button>
            <a href="manage-guides.php" class="flex-1 bg-slate-100 text-center text-slate-600 font-bold py-3.5 rounded-xl hover:bg-slate-200 transition">Cancel</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

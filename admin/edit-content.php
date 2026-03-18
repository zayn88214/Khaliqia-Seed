<?php
include 'includes/header.php';
require_once __DIR__ . '/../includes/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: manage-content.php');
    exit;
}

// Handle update
if (isset($_POST['update_string'])) {
    $page = $_POST['page'];
    $key = $_POST['key'];
    $value = $_POST['value'];
    
    try {
        $stmt = $pdo->prepare("UPDATE content SET page = ?, content_key = ?, content_value = ? WHERE id = ?");
        $stmt->execute([$page, $key, $value, $id]);
        $success = "String updated successfully!";
    } catch (PDOException $e) {
        $error = "Error updating string: " . $e->getMessage();
    }
}

$stmt = $pdo->prepare("SELECT * FROM content WHERE id = ?");
$stmt->execute([$id]);
$string = $stmt->fetch();

if (!$string) {
    header('Location: manage-content.php');
    exit;
}
?>

<div class="mb-8">
    <a href="manage-content.php" class="text-slate-500 hover:text-slate-900 flex items-center gap-2 mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Page Content
    </a>
    <h2 class="text-2xl font-bold text-slate-900">Edit Content Field</h2>
</div>

<?php if (isset($success)): ?>
    <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl mb-6">
        <?php echo $success; ?>
    </div>
<?php endif; ?>
<?php if (isset($error)): ?>
    <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-xl mb-6">
        <?php echo $error; ?>
    </div>
<?php endif; ?>

<div class="card max-w-2xl p-8">
    <form method="POST" class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-2">Page Name</label>
            <input type="text" name="page" value="<?php echo h($string['page']); ?>" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-2">Field Key</label>
            <input type="text" name="key" value="<?php echo h($string['content_key']); ?>" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500 font-mono">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-2">Content Value</label>
            <textarea name="value" rows="10" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500"><?php echo h($string['content_value']); ?></textarea>
        </div>
        <div class="flex gap-4">
            <button type="submit" name="update_string" class="flex-1 btn-green text-white font-bold py-3.5 rounded-xl shadow-lg">Update Content</button>
            <a href="manage-content.php" class="flex-1 bg-slate-100 text-center text-slate-600 font-bold py-3.5 rounded-xl hover:bg-slate-200 transition">Cancel</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

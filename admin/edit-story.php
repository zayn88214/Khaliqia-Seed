<?php
include 'includes/header.php';
require_once __DIR__ . '/../includes/db.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: manage-stories.php'); exit; }

if (isset($_POST['update'])) {
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
    $set = implode(', ', array_map(fn($k) => "$k = ?", array_keys($fields)));
    $pdo->prepare("UPDATE success_stories SET {$set}, updated_at = NOW() WHERE id = ?")->execute([...array_values($fields), $id]);
    $success = "Story updated!";
}

$stmt = $pdo->prepare("SELECT * FROM success_stories WHERE id = ?");
$stmt->execute([$id]);
$story = $stmt->fetch();
if (!$story) { header('Location: manage-stories.php'); exit; }
?>

<div class="mb-8">
    <a href="manage-stories.php" class="text-slate-500 hover:text-slate-900 flex items-center gap-2 mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Stories
    </a>
    <h2 class="text-2xl font-bold text-slate-900">Edit Success Story</h2>
</div>

<?php if (isset($success)): ?>
    <div class="alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<div class="admin-card max-w-3xl p-8">
    <form method="POST" class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-2">Title</label>
            <input type="text" name="title_en" value="<?php echo h($story['title_en']); ?>" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
        </div>
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Farmer Name</label>
                <input type="text" name="farmer_name" value="<?php echo h($story['farmer_name']); ?>" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Region</label>
                <input type="text" name="region" value="<?php echo h($story['region']); ?>" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Crop Type</label>
                <input type="text" name="crop_type" value="<?php echo h($story['crop_type']); ?>" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Yield Increase %</label>
                <input type="number" name="yield_increase_percent" value="<?php echo $story['yield_increase_percent']; ?>" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-2">Testimonial</label>
            <textarea name="testimonial_en" rows="5" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500"><?php echo h($story['testimonial_en']); ?></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-2">Description</label>
            <textarea name="description_en" rows="3" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500"><?php echo h($story['description_en']); ?></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-2">Image URL</label>
            <input type="text" name="image_url" value="<?php echo h($story['image_url']); ?>" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
        </div>
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Sort Order</label>
                <input type="number" name="sort_order" value="<?php echo $story['sort_order']; ?>" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
            <div class="flex items-end pb-1">
                <label class="flex items-center gap-2 text-slate-700 cursor-pointer">
                    <input type="checkbox" name="is_active" <?php echo $story['is_active'] ? 'checked' : ''; ?> class="w-5 h-5 rounded bg-white border-slate-300 text-green-500 focus:ring-green-500/30">
                    Active
                </label>
            </div>
        </div>
        <div class="flex gap-4">
            <button type="submit" name="update" class="flex-1 btn-green text-white font-bold py-3.5 rounded-xl shadow-lg">Update Story</button>
            <a href="manage-stories.php" class="flex-1 bg-slate-100 text-center text-slate-600 font-bold py-3.5 rounded-xl hover:bg-slate-200 transition">Cancel</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

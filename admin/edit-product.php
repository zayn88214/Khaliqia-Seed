<?php
include 'includes/header.php';
require_once __DIR__ . '/../includes/db.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: manage-products.php'); exit; }

if (isset($_POST['update'])) {
    $fields = [
        'category_id' => (int)$_POST['category_id'],
        'name_en' => trim($_POST['name_en']),
        'slug' => trim($_POST['slug']) ?: preg_replace('/[^a-z0-9]+/', '-', strtolower(trim($_POST['name_en']))),
        'crop_name_en' => trim($_POST['crop_name_en']),
        'seed_type' => trim($_POST['seed_type']),
        'image_url' => trim($_POST['image_url']),
        'price_label' => trim($_POST['price_label']),
        'yield_potential' => trim($_POST['yield_potential']),
        'growing_season_en' => trim($_POST['growing_season_en']),
        'climate_suitability_en' => trim($_POST['climate_suitability_en']),
        'disease_resistance_en' => trim($_POST['disease_resistance_en']),
        'description_en' => $_POST['description_en'],
        'features_en' => $_POST['features_en'],
        'usage_instructions_en' => $_POST['usage_instructions_en'],
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
        'sort_order' => (int)($_POST['sort_order'] ?? 0),
    ];

    $set = implode(', ', array_map(fn($k) => "$k = ?", array_keys($fields)));
    $stmt = $pdo->prepare("UPDATE products SET {$set}, updated_at = NOW() WHERE id = ?");
    $stmt->execute([...array_values($fields), $id]);
    $success = "Product updated!";
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) { header('Location: manage-products.php'); exit; }

$categories = $pdo->query("SELECT * FROM categories ORDER BY name_en")->fetchAll();
?>

<div class="mb-8">
    <a href="manage-products.php" class="text-slate-500 hover:text-slate-900 flex items-center gap-2 mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Products
    </a>
    <h2 class="text-2xl font-bold text-slate-900">Edit Product: <?php echo h($product['name_en']); ?></h2>
</div>

<?php if (isset($success)): ?>
    <div class="alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<div class="admin-card max-w-3xl p-8">
    <form method="POST" class="space-y-6">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Product Name</label>
                <input type="text" name="name_en" value="<?php echo h($product['name_en']); ?>" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Slug</label>
                <input type="text" name="slug" value="<?php echo h($product['slug']); ?>" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500 font-mono">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Category</label>
                <select name="category_id" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                    <?php foreach ($categories as $c): ?>
                        <option value="<?php echo $c['id']; ?>" <?php echo $c['id'] == $product['category_id'] ? 'selected' : ''; ?>><?php echo h($c['name_en']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Crop Name</label>
                <input type="text" name="crop_name_en" value="<?php echo h($product['crop_name_en']); ?>" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
        </div>
        <div class="grid grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Seed Type</label>
                <input type="text" name="seed_type" value="<?php echo h($product['seed_type']); ?>" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Price Label</label>
                <input type="text" name="price_label" value="<?php echo h($product['price_label']); ?>" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Yield Potential</label>
                <input type="text" name="yield_potential" value="<?php echo h($product['yield_potential']); ?>" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
        </div>
        <div class="grid grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Growing Season</label>
                <input type="text" name="growing_season_en" value="<?php echo h($product['growing_season_en']); ?>" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Climate Suitability</label>
                <input type="text" name="climate_suitability_en" value="<?php echo h($product['climate_suitability_en']); ?>" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Disease Resistance</label>
                <input type="text" name="disease_resistance_en" value="<?php echo h($product['disease_resistance_en']); ?>" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-2">Image URL</label>
            <input type="text" name="image_url" value="<?php echo h($product['image_url']); ?>" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-2">Description (HTML allowed)</label>
            <textarea name="description_en" rows="6" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500"><?php echo h($product['description_en']); ?></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-2">Features (HTML allowed)</label>
            <textarea name="features_en" rows="4" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500"><?php echo h($product['features_en']); ?></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-2">Usage Instructions (HTML allowed)</label>
            <textarea name="usage_instructions_en" rows="4" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500"><?php echo h($product['usage_instructions_en']); ?></textarea>
        </div>
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Sort Order</label>
                <input type="number" name="sort_order" value="<?php echo $product['sort_order']; ?>" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
            <div class="flex items-end pb-1">
                <label class="flex items-center gap-2 text-slate-700 cursor-pointer">
                    <input type="checkbox" name="is_active" <?php echo $product['is_active'] ? 'checked' : ''; ?> class="w-5 h-5 rounded bg-white border-slate-300 text-green-500 focus:ring-green-500/30">
                    Active
                </label>
            </div>
        </div>
        <div class="flex gap-4">
            <button type="submit" name="update" class="flex-1 btn-green text-white font-bold py-3.5 rounded-xl shadow-lg">Update Product</button>
            <a href="manage-products.php" class="flex-1 bg-slate-100 text-center text-slate-600 font-bold py-3.5 rounded-xl hover:bg-slate-200 transition">Cancel</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

<?php
include 'includes/header.php';
require_once __DIR__ . '/../includes/db.php';

// Handle add
if (isset($_POST['add_product'])) {
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
        'description_en' => $_POST['description_en'] ?: null,
        'features_en' => $_POST['features_en'] ?: null,
        'usage_instructions_en' => $_POST['usage_instructions_en'] ?: null,
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
        'sort_order' => (int)($_POST['sort_order'] ?? 0),
    ];

    try {
        $cols = implode(', ', array_keys($fields));
        $placeholders = implode(', ', array_fill(0, count($fields), '?'));
        $stmt = $pdo->prepare("INSERT INTO products ({$cols}, created_at, updated_at) VALUES ({$placeholders}, NOW(), NOW())");
        $stmt->execute(array_values($fields));
        $success = "Product added successfully!";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    try {
        $id = (int)$_GET['delete'];
        $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
        header('Location: manage-products.php?msg=deleted');
        exit;
    } catch (PDOException $e) {
        $error = "Delete failed: " . $e->getMessage();
    }
}
if (isset($_GET['msg']) && $_GET['msg'] === 'deleted') $success = "Product deleted!";

// Handle toggle active
if (isset($_GET['toggle'])) {
    try {
        $id = (int)$_GET['toggle'];
        $pdo->prepare("UPDATE products SET is_active = NOT is_active, updated_at = NOW() WHERE id = ?")->execute([$id]);
        header('Location: manage-products.php');
        exit;
    } catch (PDOException $e) {
        $error = "Toggle failed: " . $e->getMessage();
    }
}

$products = $pdo->query("SELECT p.*, c.name_en as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.sort_order ASC, p.id DESC")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name_en")->fetchAll();
?>

<div class="flex justify-between items-center mb-8">
    <h2 class="text-2xl font-bold text-slate-900">Manage Products</h2>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="btn-green text-white px-6 py-2.5 rounded-xl font-bold flex items-center gap-2">
        <span>+</span> Add Product
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
                <th class="p-4 font-medium">Product</th>
                <th class="p-4 font-medium">Category</th>
                <th class="p-4 font-medium">Seed Type</th>
                <th class="p-4 font-medium">Price</th>
                <th class="p-4 font-medium">Status</th>
                <th class="p-4 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($products as $row): ?>
            <tr class="text-slate-600 hover:bg-slate-50 transition">
                <td class="p-4">
                    <div class="flex items-center gap-3">
                        <?php if ($row['image_url']): ?>
                            <img src="<?php echo h($row['image_url']); ?>" class="w-10 h-10 rounded-lg object-cover">
                        <?php else: ?>
                            <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-lg">🌾</div>
                        <?php endif; ?>
                        <div>
                            <p class="text-slate-900 font-medium"><?php echo h($row['name_en']); ?></p>
                            <p class="text-slate-500 text-xs"><?php echo h($row['crop_name_en']); ?></p>
                        </div>
                    </div>
                </td>
                <td class="p-4"><span class="bg-slate-100 text-slate-700 text-xs px-2 py-1 rounded-md"><?php echo h($row['category_name']); ?></span></td>
                <td class="p-4"><?php echo h($row['seed_type']); ?></td>
                <td class="p-4"><?php echo h($row['price_label'] ?: '-'); ?></td>
                <td class="p-4">
                    <a href="manage-products.php?toggle=<?php echo $row['id']; ?>" class="px-2 py-1 rounded-full text-xs font-bold <?php echo $row['is_active'] ? 'bg-green-50 text-green-600 border border-green-200' : 'bg-red-50 text-red-600 border border-red-200'; ?>">
                        <?php echo $row['is_active'] ? 'Active' : 'Inactive'; ?>
                    </a>
                </td>
                <td class="p-4">
                    <a href="edit-product.php?id=<?php echo $row['id']; ?>" class="text-blue-600 hover:text-blue-800 mr-4">Edit</a>
                    <a href="manage-products.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this product?')" class="text-red-500 hover:text-red-700">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($products)): ?>
            <tr><td colspan="6" class="p-10 text-center text-slate-500">No products found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50 overflow-y-auto">
    <div class="bg-white max-w-2xl w-full p-8 rounded-3xl shadow-2xl border border-slate-200 my-8">
        <h3 class="text-2xl font-bold text-slate-900 mb-6">Add Product</h3>
        <form method="POST" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Product Name</label>
                    <input type="text" name="name_en" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Slug (auto if empty)</label>
                    <input type="text" name="slug" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500 font-mono">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Category</label>
                    <select name="category_id" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                        <?php foreach ($categories as $c): ?>
                            <option value="<?php echo $c['id']; ?>"><?php echo h($c['name_en']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Crop Name</label>
                    <input type="text" name="crop_name_en" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Seed Type</label>
                    <input type="text" name="seed_type" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Price Label</label>
                    <input type="text" name="price_label" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Yield Potential</label>
                    <input type="text" name="yield_potential" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Growing Season</label>
                    <input type="text" name="growing_season_en" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Climate</label>
                    <input type="text" name="climate_suitability_en" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Disease Resistance</label>
                    <input type="text" name="disease_resistance_en" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Image URL</label>
                <input type="text" name="image_url" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Description (HTML allowed)</label>
                <textarea name="description_en" rows="4" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Features (HTML allowed)</label>
                <textarea name="features_en" rows="3" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Usage Instructions (HTML allowed)</label>
                <textarea name="usage_instructions_en" rows="3" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500"></textarea>
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
                <button type="submit" name="add_product" class="flex-1 btn-green text-white font-bold py-3 rounded-xl shadow-lg">Save Product</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

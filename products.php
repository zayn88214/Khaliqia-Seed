<?php
require_once 'includes/site_header.php';
require_once 'includes/db.php';

$category_slug = $_GET['category'] ?? '';
$query = "SELECT p.*, c.name_en as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.is_active = 1";
$params = [];

if ($category_slug) {
    $query .= " AND c.slug = ?";
    $params[] = $category_slug;
}

$query .= " ORDER BY p.sort_order ASC, p.id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();

$cat_stmt = $pdo->query("SELECT * FROM categories ORDER BY name_en ASC");
$categories = $cat_stmt->fetchAll();
?>

<section class="py-16 bg-gradient-to-br from-green-50 via-white to-amber-50 agricultural-pattern">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-stone-900 mb-4"><?php echo h(get_content('ui', 'products_title', 'Our Seed Collection')); ?></h1>
            <p class="text-stone-600 max-w-2xl mx-auto"><?php echo h(get_content('ui', 'products_subtitle', 'Premium quality seeds for every farming need.')); ?></p>
        </div>

        <!-- Filter Bar -->
        <div class="flex flex-wrap justify-center gap-4 mb-12">
            <a href="products.php" class="px-6 py-2 rounded-full font-semibold transition <?php echo !$category_slug ? 'bg-green-600 text-white shadow-lg' : 'bg-white text-stone-600 hover:bg-stone-100 border border-stone-200'; ?>">
                All Seeds
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="products.php?category=<?php echo $cat['slug']; ?>" class="px-6 py-2 rounded-full font-semibold transition <?php echo $category_slug === $cat['slug'] ? 'bg-green-600 text-white shadow-lg' : 'bg-white text-stone-600 hover:bg-stone-100 border border-stone-200'; ?>">
                    <?php echo h($cat['name_en']); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($products as $product): ?>
            <div class="bg-white rounded-3xl overflow-hidden border border-stone-100 shadow-sm hover:shadow-xl transition group duration-500">
                <div class="relative h-64 overflow-hidden bg-stone-100">
                    <?php if ($product['image_url']): ?>
                        <img src="<?php echo h($product['image_url']); ?>" alt="<?php echo h($product['name_en']); ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-6xl opacity-20">🌾</div>
                    <?php endif; ?>
                    <div class="absolute top-4 left-4">
                        <span class="bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-green-700 shadow-sm">
                            <?php echo h($product['category_name']); ?>
                        </span>
                    </div>
                </div>
                <div class="p-8">
                    <h3 class="text-xl font-bold text-stone-900 mb-2 group-hover:text-green-600 transition"><?php echo h($product['name_en']); ?></h3>
                    <p class="text-stone-500 text-sm mb-4 line-clamp-2"><?php echo h($product['crop_name_en']); ?></p>
                    
                    <div class="flex items-center justify-between pt-6 border-t border-stone-50">
                        <span class="text-green-600 font-bold"><?php echo h($product['price_label'] ?: 'Check Price'); ?></span>
                        <a href="product-details.php?slug=<?php echo $product['slug']; ?>" class="text-stone-900 font-semibold flex items-center gap-2 group/btn">
                            View Details
                            <svg class="w-5 h-5 group-hover/btn:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if (empty($products)): ?>
                <div class="col-span-full py-20 text-center">
                    <p class="text-stone-400">No products found for this category.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
require_once 'includes/site_footer.php';
?>

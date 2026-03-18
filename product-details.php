<?php
require_once 'includes/site_header.php';
require_once 'includes/db.php';

$slug = $_GET['slug'] ?? '';
if (!$slug) { header('Location: products.php'); exit; }

$stmt = $pdo->prepare("SELECT p.*, c.name_en as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.slug = ? AND p.is_active = 1");
$stmt->execute([$slug]);
$product = $stmt->fetch();

if (!$product) { header('Location: products.php'); exit; }
?>

<section class="py-20 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16">
            <!-- Image Gallery -->
            <div class="space-y-6">
                <div class="aspect-square rounded-3xl overflow-hidden bg-stone-100 border border-stone-100 shadow-lg">
                    <?php if ($product['image_url']): ?>
                        <img src="<?php echo h($product['image_url']); ?>" alt="<?php echo h($product['name_en']); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-8xl opacity-10">🌾</div>
                    <?php endif; ?>
                </div>
                <!-- Gallery would go here -->
            </div>

            <!-- Product Details -->
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="bg-green-100 text-green-700 font-bold px-3 py-1 rounded-lg text-xs uppercase tracking-wider"><?php echo h($product['category_name']); ?></span>
                    <span class="bg-amber-100 text-amber-700 font-bold px-3 py-1 rounded-lg text-xs uppercase tracking-wider"><?php echo h($product['seed_type']); ?></span>
                </div>
                <h1 class="text-4xl font-bold text-stone-900 mb-4"><?php echo h($product['name_en']); ?></h1>
                <p class="text-2xl font-bold text-green-600 mb-8"><?php echo h($product['price_label'] ?: 'Price on request'); ?></p>
                
                <div class="prose prose-stone max-w-none text-stone-600 mb-10 leading-relaxed">
                    <?php echo $product['description_en']; ?>
                </div>

                <div class="grid sm:grid-cols-2 gap-6 mb-10">
                    <div class="bg-stone-50 p-6 rounded-2xl border border-stone-100">
                        <h4 class="text-stone-400 text-xs font-bold uppercase tracking-widest mb-2">Yield Potential</h4>
                        <p class="text-stone-900 font-bold"><?php echo h($product['yield_potential'] ?: 'Standard'); ?></p>
                    </div>
                    <div class="bg-stone-50 p-6 rounded-2xl border border-stone-100">
                        <h4 class="text-stone-400 text-xs font-bold uppercase tracking-widest mb-2">Growing Season</h4>
                        <p class="text-stone-900 font-bold"><?php echo h($product['growing_season_en'] ?: 'Any'); ?></p>
                    </div>
                    <div class="bg-stone-50 p-6 rounded-2xl border border-stone-100">
                        <h4 class="text-stone-400 text-xs font-bold uppercase tracking-widest mb-2">Climate</h4>
                        <p class="text-stone-900 font-bold"><?php echo h($product['climate_suitability_en'] ?: 'Multi-Climate'); ?></p>
                    </div>
                    <div class="bg-stone-50 p-6 rounded-2xl border border-stone-100">
                        <h4 class="text-stone-400 text-xs font-bold uppercase tracking-widest mb-2">Disease Resistance</h4>
                        <p class="text-stone-900 font-bold"><?php echo h($product['disease_resistance_en'] ?: 'High'); ?></p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="contact.php?product=<?php echo urlencode($product['name_en']); ?>" class="flex-1 btn-primary text-center font-bold py-4 rounded-xl shadow-lg">Get Quote Now</a>
                    <a href="https://wa.me/<?php echo preg_replace('/\D+/', '', get_content('contact', 'whatsapp', '')); ?>?text=I'm interested in <?php echo urlencode($product['name_en']); ?>" target="_blank" class="flex-1 bg-white border-2 border-green-600 text-green-600 text-center font-bold py-4 rounded-xl hover:bg-green-50 transition">WhatsApp Inquiry</a>
                </div>
            </div>
        </div>

        <!-- Extra Details -->
        <div class="mt-20">
            <div class="border-b border-stone-200 mb-8">
                <nav class="flex gap-10">
                    <button class="border-b-2 border-green-600 pb-4 font-bold text-stone-900">Features</button>
                    <button class="pb-4 font-bold text-stone-400 hover:text-stone-600">Usage Instructions</button>
                </nav>
            </div>
            <div class="grid md:grid-cols-2 gap-12">
                <div class="prose prose-stone max-w-none text-stone-600">
                    <?php echo $product['features_en']; ?>
                </div>
                <div class="prose prose-stone max-w-none text-stone-600">
                    <?php echo $product['usage_instructions_en']; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once 'includes/site_footer.php';
?>

<?php
include 'includes/header.php';
require_once __DIR__ . '/../includes/db.php';

// Stats logic
$string_count = $pdo->query("SELECT COUNT(*) FROM content")->fetchColumn();
$product_count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$guide_count = $pdo->query("SELECT COUNT(*) FROM farming_guides")->fetchColumn();
$story_count = $pdo->query("SELECT COUNT(*) FROM success_stories")->fetchColumn();
$calendar_count = $pdo->query("SELECT COUNT(*) FROM crop_calendars")->fetchColumn();
$inquiry_count = $pdo->query("SELECT COUNT(*) FROM inquiries")->fetchColumn();
$category_count = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$new_inquiries = $pdo->query("SELECT COUNT(*) FROM inquiries WHERE status = 'new'")->fetchColumn();
$active_products = $pdo->query("SELECT COUNT(*) FROM products WHERE is_active = 1")->fetchColumn();
?>

<!-- Stats Grid -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="admin-card p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-0.5 rounded-full"><?php echo $active_products; ?> active</span>
        </div>
        <p class="text-2xl font-bold text-slate-900"><?php echo $product_count; ?></p>
        <p class="text-xs text-slate-400 mt-0.5">Total Products</p>
    </div>
    <div class="admin-card p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
            </div>
            <?php if ($new_inquiries > 0): ?>
                <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full"><?php echo $new_inquiries; ?> new</span>
            <?php endif; ?>
        </div>
        <p class="text-2xl font-bold text-slate-900"><?php echo $inquiry_count; ?></p>
        <p class="text-xs text-slate-400 mt-0.5">Inquiries</p>
    </div>
    <div class="admin-card p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-900"><?php echo $guide_count; ?></p>
        <p class="text-xs text-slate-400 mt-0.5">Farming Guides</p>
    </div>
    <div class="admin-card p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-900"><?php echo $story_count; ?></p>
        <p class="text-xs text-slate-400 mt-0.5">Success Stories</p>
    </div>
</div>

<!-- Secondary Stats -->
<div class="grid grid-cols-3 gap-4 mb-8">
    <div class="admin-card p-4 flex items-center gap-3">
        <div class="w-9 h-9 rounded-lg bg-slate-50 flex items-center justify-center">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        </div>
        <div>
            <p class="text-lg font-bold text-slate-900"><?php echo $string_count; ?></p>
            <p class="text-xs text-slate-400">Content Fields</p>
        </div>
    </div>
    <div class="admin-card p-4 flex items-center gap-3">
        <div class="w-9 h-9 rounded-lg bg-slate-50 flex items-center justify-center">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
        </div>
        <div>
            <p class="text-lg font-bold text-slate-900"><?php echo $category_count; ?></p>
            <p class="text-xs text-slate-400">Categories</p>
        </div>
    </div>
    <div class="admin-card p-4 flex items-center gap-3">
        <div class="w-9 h-9 rounded-lg bg-slate-50 flex items-center justify-center">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div>
            <p class="text-lg font-bold text-slate-900"><?php echo $calendar_count; ?></p>
            <p class="text-xs text-slate-400">Calendar Entries</p>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <!-- Recent Inquiries -->
    <div class="admin-card">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-semibold text-slate-900">Recent Inquiries</h3>
            <a href="manage-inquiries.php" class="text-xs text-green-600 hover:text-green-700 font-medium">View all &rarr;</a>
        </div>
        <div class="divide-y divide-slate-50">
            <?php
            $recent_inq = $pdo->query("SELECT * FROM inquiries ORDER BY created_at DESC LIMIT 5")->fetchAll();
            foreach ($recent_inq as $inq):
                $status_class = match($inq['status']) { 'new' => 'badge-new', 'in_progress' => 'badge-draft', 'resolved' => 'badge-active', default => 'badge-inactive' };
            ?>
            <div class="px-6 py-3.5 flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-sm font-medium text-slate-800 truncate"><?php echo h($inq['name']); ?></p>
                    <p class="text-xs text-slate-400 truncate"><?php echo h($inq['phone']); ?> <?php echo $inq['crop'] ? '· ' . h($inq['crop']) : ''; ?></p>
                </div>
                <span class="<?php echo $status_class; ?> ml-3 shrink-0"><?php echo ucwords(str_replace('_', ' ', $inq['status'])); ?></span>
            </div>
            <?php endforeach; ?>
            <?php if (empty($recent_inq)): ?>
                <div class="px-6 py-8 text-center text-sm text-slate-400">No inquiries yet.</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="admin-card">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-semibold text-slate-900">Quick Actions</h3>
        </div>
        <div class="p-4 grid grid-cols-2 gap-3">
            <a href="manage-content.php" class="p-4 rounded-xl border border-slate-100 hover:border-green-200 hover:bg-green-50/50 transition group">
                <svg class="w-5 h-5 text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <p class="text-sm font-semibold text-slate-800">Page Content</p>
                <p class="text-xs text-slate-400 mt-0.5"><?php echo $string_count; ?> fields</p>
            </a>
            <a href="manage-products.php" class="p-4 rounded-xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50/50 transition group">
                <svg class="w-5 h-5 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <p class="text-sm font-semibold text-slate-800">Products</p>
                <p class="text-xs text-slate-400 mt-0.5"><?php echo $product_count; ?> products</p>
            </a>
            <a href="manage-guides.php" class="p-4 rounded-xl border border-slate-100 hover:border-amber-200 hover:bg-amber-50/50 transition group">
                <svg class="w-5 h-5 text-amber-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <p class="text-sm font-semibold text-slate-800">Guides</p>
                <p class="text-xs text-slate-400 mt-0.5"><?php echo $guide_count; ?> guides</p>
            </a>
            <a href="manage-inquiries.php" class="p-4 rounded-xl border border-slate-100 hover:border-purple-200 hover:bg-purple-50/50 transition group">
                <svg class="w-5 h-5 text-purple-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                <p class="text-sm font-semibold text-slate-800">Inquiries</p>
                <p class="text-xs text-slate-400 mt-0.5"><?php echo $inquiry_count; ?> messages</p>
            </a>
        </div>
    </div>
</div>

<!-- Recent Content Updates -->
<div class="admin-card mt-6">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-semibold text-slate-900">Recent Content Updates</h3>
        <a href="manage-content.php" class="text-xs text-green-600 hover:text-green-700 font-medium">Manage &rarr;</a>
    </div>
    <div class="divide-y divide-slate-50">
        <?php
        $recent = $pdo->query("SELECT * FROM content ORDER BY last_updated DESC LIMIT 6")->fetchAll();
        foreach ($recent as $r):
        ?>
        <div class="px-6 py-3 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-700"><?php echo ucwords(str_replace('_', ' ', h($r['content_key']))); ?></p>
                <p class="text-xs text-slate-400"><?php echo h($r['page']); ?></p>
            </div>
            <a href="edit-content.php?id=<?php echo $r['id']; ?>" class="text-xs text-green-600 hover:text-green-700 font-medium">Edit</a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

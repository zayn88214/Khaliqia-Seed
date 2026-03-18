<?php
include 'includes/header.php';
require_once __DIR__ . '/../includes/db.php';

function is_image_content_key($key) {
    return strpos($key, 'image') !== false;
}

// Handle addition
if (isset($_POST['add_string'])) {
    $page = $_POST['page'];
    $key = $_POST['key'];
    $value = $_POST['value'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO content (page, content_key, content_value) VALUES (?, ?, ?)");
        $stmt->execute([$page, $key, $value]);
        $success = "Content added successfully!";
    } catch (PDOException $e) {
        $error = "Error adding content: " . $e->getMessage();
    }
}

// Handle bulk update
if (isset($_POST['bulk_update'])) {
    $updates = $_POST['content'] ?? [];
    $count = 0;
    $upload_errors = [];

    $rows = $pdo->query("SELECT id, content_key, content_value FROM content")->fetchAll();
    $content_map = [];
    foreach ($rows as $row) {
        $content_map[(int)$row['id']] = $row;
    }

    $upload_dir_fs = __DIR__ . '/../uploads/content';
    $upload_dir_web = 'uploads/content';
    if (!is_dir($upload_dir_fs)) {
        mkdir($upload_dir_fs, 0755, true);
    }

    foreach ($updates as $id => $value) {
        $id = (int)$id;
        $value_to_save = trim((string)$value);

        if (isset($content_map[$id]) && is_image_content_key($content_map[$id]['content_key']) && isset($_FILES['content_file']['error'][$id])) {
            $file_error = (int)$_FILES['content_file']['error'][$id];

            if ($file_error !== UPLOAD_ERR_NO_FILE) {
                if ($file_error === UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES['content_file']['tmp_name'][$id];
                    $original_name = $_FILES['content_file']['name'][$id];
                    $size = (int)$_FILES['content_file']['size'][$id];

                    $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
                    $allowed_ext = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = $finfo ? finfo_file($finfo, $tmp_name) : '';
                    if ($finfo) {
                        finfo_close($finfo);
                    }
                    $allowed_mime = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

                    if (!in_array($ext, $allowed_ext, true) || !in_array($mime, $allowed_mime, true)) {
                        $upload_errors[] = "Invalid image file for key: " . $content_map[$id]['content_key'];
                    } elseif ($size > 5 * 1024 * 1024) {
                        $upload_errors[] = "Image too large (max 5MB) for key: " . $content_map[$id]['content_key'];
                    } else {
                        $safe_base = preg_replace('/[^a-zA-Z0-9_-]/', '-', pathinfo($original_name, PATHINFO_FILENAME));
                        $safe_base = trim($safe_base, '-');
                        if ($safe_base === '') {
                            $safe_base = 'image';
                        }

                        $file_name = $content_map[$id]['content_key'] . '-' . $safe_base . '-' . time() . '.' . $ext;
                        $target_fs = $upload_dir_fs . '/' . $file_name;

                        if (move_uploaded_file($tmp_name, $target_fs)) {
                            $value_to_save = $upload_dir_web . '/' . $file_name;
                        } else {
                            $upload_errors[] = "Failed to save uploaded file for key: " . $content_map[$id]['content_key'];
                        }
                    }
                } else {
                    $upload_errors[] = "Upload failed for key: " . $content_map[$id]['content_key'];
                }
            }
        }

        $stmt = $pdo->prepare("UPDATE content SET content_value = ? WHERE id = ?");
        $stmt->execute([$value_to_save, $id]);
        $count++;
    }
    $success = "$count content fields updated successfully!";
    if (!empty($upload_errors)) {
        $error = implode(' | ', $upload_errors);
    }
}

// Handle deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM content WHERE id = ?");
    $stmt->execute([$id]);
    $success = "Content deleted!";
}

$page_filter = $_GET['page'] ?? '';

// Get all distinct pages
$pages = $pdo->query("SELECT DISTINCT page FROM content ORDER BY page")->fetchAll(PDO::FETCH_COLUMN);

// If no page selected, show first page
if (!$page_filter && !empty($pages)) {
    $page_filter = $pages[0];
}

// Get strings for selected page
$sql = "SELECT * FROM content";
$params = [];
if ($page_filter) {
    $sql .= " WHERE page = ?";
    $params = [$page_filter];
}
$sql .= " ORDER BY content_key";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$strings = $stmt->fetchAll();

// Page label map for display
$page_labels = [
    'home' => '🏠 Home Page',
    'about' => '📄 About Page',
    'contact' => '📞 Contact Page',
    'products' => '📦 Products Page',
    'guides' => '📖 Guides Page',
    'footer' => '🦶 Footer',
    'header' => '🔝 Header / Navigation',
    'site' => '⚙️ Site Settings',
    'seo' => '🔍 SEO Settings',
    'ui' => '🎨 UI Labels',
    'calendar' => '📅 Calendar Page',
    'success' => '⭐ Success Stories',
    'global' => '🌍 Global / Shared',
];
?>

<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-bold text-slate-900">Page Content</h2>
        <p class="text-slate-500 text-sm mt-1">Edit text content organized by page</p>
    </div>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="btn-green text-white px-6 py-2.5 rounded-xl font-bold flex items-center gap-2">
        <span>+</span> Add New Field
    </button>
</div>

<?php if (isset($success)): ?>
    <div class="alert-success flex justify-between">
        <?php echo $success; ?>
        <button onclick="this.parentElement.remove()" class="text-green-400 hover:text-green-600">×</button>
    </div>
<?php endif; ?>
<?php if (isset($error)): ?>
    <div class="alert-error flex justify-between">
        <?php echo $error; ?>
        <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600">×</button>
    </div>
<?php endif; ?>

<!-- Page Tabs -->
<div class="mb-6 flex flex-wrap gap-2">
    <?php foreach ($pages as $p): ?>
        <a href="manage-content.php?page=<?php echo urlencode($p); ?>" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition <?php echo ($page_filter === $p) ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-white text-slate-600 border border-slate-200 hover:border-green-300'; ?>">
            <?php echo $page_labels[$p] ?? '📄 ' . ucfirst(h($p)); ?>
        </a>
    <?php endforeach; ?>
</div>

<?php if ($page_filter && !empty($strings)): ?>
<!-- Page Content Form -->
<div class="admin-card p-8">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-slate-900"><?php echo $page_labels[$page_filter] ?? ucfirst(h($page_filter)); ?></h3>
        <span class="text-sm text-slate-500"><?php echo count($strings); ?> fields</span>
    </div>
    <form method="POST" enctype="multipart/form-data" class="space-y-5">
        <?php foreach ($strings as $row): ?>
        <div class="group">
            <div class="flex items-center justify-between mb-1.5">
                <label class="block text-sm font-medium text-slate-600">
                    <?php echo ucwords(str_replace('_', ' ', h($row['content_key']))); ?>
                </label>
                <div class="flex items-center gap-3">
                    <span class="text-xs text-slate-400 font-mono"><?php echo h($row['content_key']); ?></span>
                    <a href="manage-content.php?page=<?php echo urlencode($page_filter); ?>&delete=<?php echo $row['id']; ?>" 
                       onclick="return confirm('Delete this field?')" 
                       class="text-red-400 hover:text-red-600 text-xs opacity-0 group-hover:opacity-100 transition">Delete</a>
                </div>
            </div>
            <?php if (strlen($row['content_value']) > 100): ?>
                <textarea name="content[<?php echo $row['id']; ?>]" rows="3" 
                    class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500 transition"><?php echo h($row['content_value']); ?></textarea>
            <?php else: ?>
                <input type="text" name="content[<?php echo $row['id']; ?>]" value="<?php echo h($row['content_value']); ?>" 
                    class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500 transition">
            <?php endif; ?>

            <?php if (is_image_content_key($row['content_key'])): ?>
                <div class="mt-2 p-3 rounded-lg border border-dashed border-slate-300 bg-slate-50">
                    <label class="block text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wider">Upload Image (optional)</label>
                    <input type="file" name="content_file[<?php echo $row['id']; ?>]" accept="image/*"
                        class="w-full text-sm text-slate-700 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-green-100 file:text-green-700 file:font-semibold hover:file:bg-green-200">
                    <p class="text-xs text-slate-400 mt-2">Accepted: JPG, PNG, WEBP, GIF (max 5MB). Uploaded image path will replace text value.</p>
                    <?php if (!empty($row['content_value']) && is_image_content_key($row['content_key'])): ?>
                        <p class="text-xs text-slate-500 mt-2">Current: <?php echo h($row['content_value']); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
        <div class="flex gap-4 pt-4 border-t border-slate-100">
            <button type="submit" name="bulk_update" class="btn-green text-white font-bold py-3 px-8 rounded-xl shadow-lg">Save All Changes</button>
        </div>
    </form>
</div>
<?php elseif (empty($strings)): ?>
<div class="admin-card p-16 text-center">
    <p class="text-slate-500">No content found. Add fields to get started.</p>
</div>
<?php endif; ?>

<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50">
    <div class="bg-white max-w-lg w-full p-8 rounded-3xl shadow-2xl border border-slate-200">
        <h3 class="text-2xl font-bold text-slate-900 mb-6">Add New Content Field</h3>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Page Name (e.g., home, about, contact)</label>
                <input type="text" name="page" required value="<?php echo h($page_filter); ?>" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Field Key (e.g., hero_title, footer_text)</label>
                <input type="text" name="key" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500 font-mono">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Content Value</label>
                <textarea name="value" rows="4" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500"></textarea>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="flex-1 bg-slate-100 text-slate-600 font-bold py-3 rounded-xl hover:bg-slate-200 transition">Cancel</button>
                <button type="submit" name="add_string" class="flex-1 btn-green text-white font-bold py-3 rounded-xl shadow-lg">Save Field</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

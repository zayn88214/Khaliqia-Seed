<?php
include 'includes/header.php';
require_once __DIR__ . '/../includes/db.php';

// Handle status update
if (isset($_POST['update_status'])) {
    $inq_id = (int)$_POST['id'];
    $new_status = $_POST['status'];
    $allowed = ['new', 'in_progress', 'resolved', 'closed'];
    if (in_array($new_status, $allowed, true)) {
        $pdo->prepare("UPDATE inquiries SET status = ?, updated_at = NOW() WHERE id = ?")->execute([$new_status, $inq_id]);
    }
    header('Location: manage-inquiries.php');
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM inquiries WHERE id = ?")->execute([(int)$_GET['delete']]);
    header('Location: manage-inquiries.php?msg=deleted');
    exit;
}
if (isset($_GET['msg']) && $_GET['msg'] === 'deleted') $success = "Inquiry deleted!";

$status_filter = $_GET['status'] ?? '';
$sql = "SELECT * FROM inquiries";
$params = [];
if ($status_filter) {
    $sql .= " WHERE status = ?";
    $params[] = $status_filter;
}
$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$inquiries = $stmt->fetchAll();
?>

<div class="flex justify-between items-center mb-8">
    <h2 class="text-2xl font-bold text-slate-900">Contact Inquiries</h2>
    <div class="text-slate-500 text-sm"><?php echo count($inquiries); ?> inquiries</div>
</div>

<?php if (isset($success)): ?>
    <div class="alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<!-- Filter -->
<div class="mb-6 flex gap-3">
    <a href="manage-inquiries.php" class="px-4 py-2 rounded-lg text-sm font-medium <?php echo !$status_filter ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-white text-slate-600 border border-slate-200'; ?>">All</a>
    <?php foreach (['new' => 'New', 'in_progress' => 'In Progress', 'resolved' => 'Resolved', 'closed' => 'Closed'] as $k => $v): ?>
        <a href="manage-inquiries.php?status=<?php echo $k; ?>" class="px-4 py-2 rounded-lg text-sm font-medium <?php echo $status_filter === $k ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-white text-slate-600 border border-slate-200'; ?>"><?php echo $v; ?></a>
    <?php endforeach; ?>
</div>

<div class="admin-card overflow-hidden">
    <table class="w-full text-left">
        <thead>
            <tr class="text-slate-500 text-sm border-b border-slate-100 bg-slate-50">
                <th class="p-4 font-medium">Name</th>
                <th class="p-4 font-medium">Contact</th>
                <th class="p-4 font-medium">Message</th>
                <th class="p-4 font-medium">Date</th>
                <th class="p-4 font-medium">Status</th>
                <th class="p-4 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($inquiries as $row): ?>
            <tr class="text-slate-600 hover:bg-slate-50 transition">
                <td class="p-4">
                    <p class="text-slate-900 font-medium"><?php echo h($row['name']); ?></p>
                    <?php if ($row['region']): ?><p class="text-slate-500 text-xs"><?php echo h($row['region']); ?></p><?php endif; ?>
                </td>
                <td class="p-4">
                    <p class="text-sm"><?php echo h($row['phone']); ?></p>
                    <?php if ($row['email']): ?><p class="text-slate-500 text-xs"><?php echo h($row['email']); ?></p><?php endif; ?>
                </td>
                <td class="p-4 max-w-xs">
                    <p class="truncate text-sm"><?php echo h($row['message']); ?></p>
                    <?php if ($row['crop']): ?><span class="bg-slate-100 text-slate-700 text-xs px-2 py-0.5 rounded mt-1 inline-block"><?php echo h($row['crop']); ?></span><?php endif; ?>
                </td>
                <td class="p-4 text-sm"><?php echo $row['created_at'] ? date('M j, Y', strtotime($row['created_at'])) : '-'; ?></td>
                <td class="p-4">
                    <form method="POST" class="inline">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <select name="status" onchange="this.form.submit()" class="bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-500/30">
                            <?php foreach (['new','in_progress','resolved','closed'] as $s): ?>
                                <option value="<?php echo $s; ?>" <?php echo $row['status'] === $s ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_',' ',$s)); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="update_status" value="1">
                    </form>
                </td>
                <td class="p-4">
                    <a href="manage-inquiries.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete?')" class="text-red-500 hover:text-red-700 text-sm">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($inquiries)): ?>
            <tr><td colspan="6" class="p-10 text-center text-slate-500">No inquiries found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>

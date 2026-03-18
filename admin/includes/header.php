<?php
ob_start();
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
session_start();

// Simple auth check (placeholder for now)
if (!isset($_SESSION['admin_logged_in']) && basename($_SERVER['PHP_SELF']) !== 'login.php') {
    header('Location: login.php');
    exit;
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Khaliqia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', 'Inter', sans-serif; }
        body { background: #f8fafc; color: #0f172a; }

        /* Sidebar */
        .admin-sidebar {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.625rem 0.875rem;
            border-radius: 0.625rem;
            color: #94a3b8;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
            gap: 0.75rem;
        }
        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.06);
            color: #e2e8f0;
        }
        .sidebar-link.active {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
        }
        .sidebar-link.active svg { opacity: 1; }
        .sidebar-link svg { width: 1.125rem; height: 1.125rem; opacity: 0.6; flex-shrink: 0; }
        .sidebar-link:hover svg { opacity: 0.9; }
        .sidebar-section-label {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #475569;
            padding: 1.25rem 0.875rem 0.375rem;
        }

        /* Cards */
        .admin-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            transition: box-shadow 0.2s;
        }
        .admin-card:hover { box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06); }

        /* Buttons */
        .btn-green {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white;
            font-weight: 600;
            transition: all 0.2s ease;
            border-radius: 0.625rem;
        }
        .btn-green:hover {
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
            transform: translateY(-1px);
        }

        /* Table */
        .admin-table { width: 100%; text-align: left; }
        .admin-table thead th {
            padding: 0.875rem 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .admin-table tbody td {
            padding: 0.875rem 1rem;
            font-size: 0.875rem;
            color: #475569;
            border-bottom: 1px solid #f1f5f9;
        }
        .admin-table tbody tr { transition: background 0.15s; }
        .admin-table tbody tr:hover { background: #f8fafc; }

        /* Form inputs */
        .admin-input {
            width: 100%;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 0.625rem;
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
            color: #0f172a;
            transition: all 0.2s;
            outline: none;
        }
        .admin-input:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
        }
        .admin-input::placeholder { color: #94a3b8; }
        .admin-label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.375rem;
        }

        /* Status badges */
        .badge-active { display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background: #dcfce7; color: #16a34a; }
        .badge-inactive { display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background: #fef2f2; color: #dc2626; }
        .badge-draft { display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background: #fef9c3; color: #a16207; }
        .badge-new { display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background: #dbeafe; color: #2563eb; }

        /* Modal */
        .admin-modal {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            z-index: 50;
            overflow-y: auto;
        }
        .admin-modal-content {
            background: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            padding: 2rem;
        }

        /* Top bar */
        .admin-topbar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #e2e8f0;
        }

        /* Alert toasts */
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            padding: 0.75rem 1rem;
            border-radius: 0.625rem;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 0.75rem 1rem;
            border-radius: 0.625rem;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        /* Mobile */
        @media (max-width: 1023px) {
            .admin-sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                width: 280px;
                z-index: 60;
                transition: left 0.3s ease;
            }
            .admin-sidebar.open { left: 0; }
            .sidebar-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.4);
                z-index: 55;
                display: none;
            }
            .sidebar-overlay.show { display: block; }
        }

        /* Scrollbar */
        .admin-sidebar::-webkit-scrollbar { width: 4px; }
        .admin-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }

        /* Legacy compat for existing admin pages */
        .card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .glass { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
    <!-- Mobile sidebar overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <div class="flex">
    <!-- Sidebar -->
    <aside class="admin-sidebar w-[260px] flex-shrink-0 flex flex-col p-4 overflow-y-auto" id="adminSidebar">
        <div class="flex items-center gap-3 px-2 mb-8 mt-2">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center text-white text-lg" style="background: linear-gradient(135deg, #16a34a, #15803d);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
            </div>
            <div>
                <h1 class="text-sm font-bold text-white tracking-tight">Khaliqia</h1>
                <p class="text-[0.65rem] text-slate-400">Seed Corporation</p>
            </div>
            <button class="lg:hidden ml-auto text-slate-400 hover:text-white" onclick="toggleSidebar()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <nav class="flex-1 space-y-0.5">
            <div class="sidebar-section-label">Overview</div>
            <a href="index.php" class="sidebar-link <?php echo $current_page === 'index.php' ? 'active' : ''; ?>">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Dashboard
            </a>

            <div class="sidebar-section-label">Content</div>
            <a href="manage-content.php" class="sidebar-link <?php echo $current_page === 'manage-content.php' || $current_page === 'edit-content.php' ? 'active' : ''; ?>">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Page Content
            </a>
            <a href="manage-categories.php" class="sidebar-link <?php echo $current_page === 'manage-categories.php' || $current_page === 'edit-category.php' ? 'active' : ''; ?>">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                Categories
            </a>
            <a href="manage-products.php" class="sidebar-link <?php echo $current_page === 'manage-products.php' || $current_page === 'edit-product.php' ? 'active' : ''; ?>">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Products
            </a>

            <div class="sidebar-section-label">Resources</div>
            <a href="manage-guides.php" class="sidebar-link <?php echo $current_page === 'manage-guides.php' || $current_page === 'edit-guide.php' ? 'active' : ''; ?>">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Farming Guides
            </a>
            <a href="manage-stories.php" class="sidebar-link <?php echo $current_page === 'manage-stories.php' || $current_page === 'edit-story.php' ? 'active' : ''; ?>">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                Success Stories
            </a>
            <a href="manage-calendars.php" class="sidebar-link <?php echo $current_page === 'manage-calendars.php' || $current_page === 'edit-calendar.php' ? 'active' : ''; ?>">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Crop Calendar
            </a>

            <div class="sidebar-section-label">Communication</div>
            <a href="manage-inquiries.php" class="sidebar-link <?php echo $current_page === 'manage-inquiries.php' ? 'active' : ''; ?>">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                Inquiries
            </a>
        </nav>

        <!-- Bottom section -->
        <div class="mt-auto pt-4 border-t border-slate-700/50">
            <a href="../index.php" target="_blank" class="sidebar-link text-slate-500 hover:text-green-400 mb-1">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                View Website
            </a>
            <a href="logout.php" class="sidebar-link text-red-400/70 hover:text-red-400 hover:bg-red-500/10">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Sign Out
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 min-h-screen flex flex-col">
        <!-- Top Bar -->
        <header class="admin-topbar sticky top-0 z-40 px-6 py-3 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button class="lg:hidden p-2 -ml-2 rounded-lg text-slate-500 hover:bg-slate-100" onclick="toggleSidebar()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <p class="text-xs text-slate-400 font-medium">Welcome back,</p>
                    <p class="text-sm font-semibold text-slate-800"><?php echo h($_SESSION['admin_name'] ?? 'Admin'); ?></p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="../index.php" target="_blank" class="hidden sm:flex items-center gap-1.5 text-xs text-slate-500 hover:text-green-600 font-medium transition px-3 py-1.5 rounded-lg hover:bg-green-50">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Visit Site
                </a>
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                    <?php echo strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)); ?>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-6 lg:p-8">

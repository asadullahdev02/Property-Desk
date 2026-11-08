<?php
/**
 * Abdullah Property Dealer - Admin Shared Header & Sidebar
 */
require_once __DIR__ . '/../includes/config.php';
require_admin();

$admin_page = $admin_page ?? 'dashboard';
$stats = get_dashboard_stats();
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($admin_title ?? 'Admin Dashboard'); ?> | Abdullah Property Dealer</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-body">

  <!-- Admin Sidebar -->
  <aside class="admin-sidebar">
    <div class="admin-brand">
      <div class="badge">APD</div>
      <div class="info">
        <h4>Abdullah Properties</h4>
        <span>Admin Panel</span>
      </div>
    </div>

    <nav class="admin-nav">
      <!-- Dashboard -->
      <a href="index.php" class="admin-nav-item <?php echo ($admin_page === 'dashboard') ? 'active' : ''; ?>">
        <svg viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
        <span>Dashboard</span>
      </a>

      <!-- Properties -->
      <a href="properties.php" class="admin-nav-item <?php echo ($admin_page === 'properties') ? 'active' : ''; ?>">
        <svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
        <span>All Properties</span>
      </a>

      <!-- Add Property -->
      <a href="property-form.php" class="admin-nav-item <?php echo ($admin_page === 'add-property') ? 'active' : ''; ?>">
        <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
        <span>Add Property</span>
      </a>

      <!-- Inquiries -->
      <a href="inquiries.php" class="admin-nav-item <?php echo ($admin_page === 'inquiries') ? 'active' : ''; ?>">
        <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
        <span>Inquiries</span>
        <?php if ($stats['new_inquiries'] > 0): ?>
          <span class="badge-pill badge-new" style="margin-left:auto;"><?php echo $stats['new_inquiries']; ?></span>
        <?php endif; ?>
      </a>

      <!-- Public Site Link -->
      <a href="../index.php" target="_blank" class="admin-nav-item">
        <svg viewBox="0 0 24 24"><path d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"/></svg>
        <span>View Public Site</span>
      </a>
    </nav>

    <!-- Sidebar Footer -->
    <div class="admin-sidebar-footer">
      <div class="user-mini">
        <div>
          <div class="name"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Abdullah Malik'); ?></div>
          <div class="role"><?php echo htmlspecialchars($_SESSION['admin_role'] ?? 'Administrator'); ?></div>
        </div>
        <a href="logout.php" class="logout-link" title="Sign out">Logout</a>
      </div>
    </div>
  </aside>

  <!-- Main Content Area -->
  <main class="admin-main">
    
    <!-- Top Bar Header -->
    <div class="admin-top-header">
      <div>
        <h1><?php echo htmlspecialchars($admin_title ?? 'Dashboard'); ?></h1>
        <p style="color:var(--adm-grey);font-size:0.88rem;margin:4px 0 0 0;">
          <?php echo htmlspecialchars($admin_subtitle ?? 'Manage Pakistani property listings, valuations, and client leads.'); ?>
        </p>
      </div>
      <div class="admin-header-actions">
        <?php if ($admin_page !== 'add-property'): ?>
          <a href="property-form.php" class="btn btn-burgundy btn-sm">
            <svg style="width:14px;height:14px;fill:currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            <span>Add New Property</span>
          </a>
        <?php endif; ?>
      </div>
    </div>

    <!-- Flash Messages -->
    <?php if ($flash): ?>
      <div class="alert alert-<?php echo ($flash['type'] === 'success') ? 'success' : 'error'; ?>">
        <span><?php echo htmlspecialchars($flash['message']); ?></span>
      </div>
    <?php endif; ?>


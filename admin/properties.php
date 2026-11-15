<?php
/**
 * Abdullah Property Dealer - Manage All Properties
 */
require_once __DIR__ . '/../includes/config.php';
require_admin();

$admin_page = 'properties';
$admin_title = 'Manage Properties';
$admin_subtitle = 'Add, edit, remove, and change statuses for properties across Pakistan.';

// Handle CSV Export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $properties = get_properties();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=apd_properties_' . date('Ymd_His') . '.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Title', 'City', 'Location', 'Category', 'Purpose', 'Price (PKR)', 'Price Display', 'Area', 'Bedrooms', 'Bathrooms', 'Status', 'Created At']);
    foreach ($properties as $p) {
        fputcsv($output, [
            $p['id'] ?? '',
            $p['title'] ?? '',
            $p['city'] ?? '',
            $p['location'] ?? '',
            $p['category'] ?? '',
            $p['purpose'] ?? '',
            $p['price'] ?? 0,
            $p['price_display'] ?? format_pkr($p['price'] ?? 0, $p['purpose'] ?? 'sale'),
            ($p['area_value'] ?? '') . ' ' . ($p['area_unit'] ?? ''),
            $p['bedrooms'] ?? 0,
            $p['bathrooms'] ?? 0,
            $p['status'] ?? '',
            $p['created_at'] ?? ''
        ]);
    }
    fclose($output);
    exit;
}

// Handle Quick Status Change via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $prop_id = trim($_POST['property_id'] ?? '');
    $new_status = trim($_POST['status'] ?? '');
    
    $valid_statuses = ['Available', 'Under Offer', 'Sold', 'Rented'];
    if (in_array($new_status, $valid_statuses)) {
        $props = get_properties();
        $updated = false;
        foreach ($props as &$p) {
            if ($p['id'] === $prop_id) {
                $p['status'] = $new_status;
                $updated = true;
                break;
            }
        }
        if ($updated) {
            save_properties($props);
            set_flash('success', "Status for property {$prop_id} updated to '{$new_status}'.");
        } else {
            set_flash('error', "Property {$prop_id} not found.");
        }
    }
    header('Location: properties.php');
    exit;
}

$properties = get_properties();

include __DIR__ . '/admin-header.php';
?>

<div class="admin-card">
  <div class="admin-card-header" style="flex-wrap:wrap;gap:12px;">
    <div>
      <h3>All Properties (<?php echo count($properties); ?> Total Listings)</h3>
      <span style="font-size:0.8rem;color:var(--adm-grey);">Real estate inventory across Pakistan</span>
    </div>
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
      <input type="text" id="adminPropSearch" placeholder="Filter by ID, title, city..." class="filter-select" style="padding:6px 12px;font-size:0.85rem;border-radius:4px;min-width:220px;">
      <a href="properties.php?export=csv" class="btn btn-outline btn-sm">Export CSV</a>
      <a href="property-form.php" class="btn btn-burgundy btn-sm">+ Add New Property</a>
    </div>
  </div>
  
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Thumb</th>
          <th>ID</th>
          <th>Title</th>
          <th>City & Location</th>
          <th>Category</th>
          <th>Purpose</th>
          <th>Price</th>
          <th>Status (Quick Update)</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($properties)): ?>
          <tr><td colspan="9" style="text-align:center;padding:30px;color:var(--adm-grey);">No properties available. Click "Add New Property" to create one.</td></tr>
        <?php else: ?>
          <?php foreach ($properties as $prop): ?>
            <tr class="admin-prop-row" data-search="<?php echo htmlspecialchars(strtolower($prop['id'] . ' ' . $prop['title'] . ' ' . $prop['city'] . ' ' . $prop['location'] . ' ' . $prop['category'] . ' ' . $prop['status'])); ?>">
              <td>
                <img src="../<?php echo htmlspecialchars($prop['image']); ?>" alt="Thumb" class="table-thumb">
              </td>
              <td><code><?php echo htmlspecialchars($prop['id']); ?></code></td>
              <td>
                <strong><?php echo htmlspecialchars($prop['title']); ?></strong><br>
                <span style="font-size:0.75rem;color:var(--adm-grey);"><?php echo htmlspecialchars($prop['area_value'] . ' ' . $prop['area_unit']); ?> | <?php echo (int)$prop['bedrooms']; ?> Beds</span>
              </td>
              <td>
                <strong><?php echo htmlspecialchars($prop['city']); ?></strong><br>
                <span style="font-size:0.75rem;color:var(--adm-grey);"><?php echo htmlspecialchars($prop['location']); ?></span>
              </td>
              <td><?php echo htmlspecialchars($prop['category']); ?></td>
              <td>
                <span class="badge-pill <?php echo ($prop['purpose'] === 'sale') ? 'badge-sold' : 'badge-rented'; ?>">
                  <?php echo ($prop['purpose'] === 'sale') ? 'Sale' : 'Rent'; ?>
                </span>
              </td>
              <td style="font-weight:700;color:var(--adm-burgundy);white-space:nowrap;">
                <?php echo htmlspecialchars($prop['price_display'] ?? format_pkr($prop['price'])); ?>
              </td>
              <td>
                <!-- Quick Status Change Form -->
                <form method="POST" action="properties.php" style="display:inline-flex;gap:6px;align-items:center;">
                  <input type="hidden" name="action" value="update_status">
                  <input type="hidden" name="property_id" value="<?php echo htmlspecialchars($prop['id']); ?>">
                  <select name="status" onchange="this.form.submit()" class="filter-select" style="padding:4px 8px;font-size:0.8rem;border-radius:4px;">
                    <option value="Available" <?php echo ($prop['status'] === 'Available') ? 'selected' : ''; ?>>Available</option>
                    <option value="Under Offer" <?php echo ($prop['status'] === 'Under Offer') ? 'selected' : ''; ?>>Under Offer</option>
                    <option value="Sold" <?php echo ($prop['status'] === 'Sold') ? 'selected' : ''; ?>>Sold</option>
                    <option value="Rented" <?php echo ($prop['status'] === 'Rented') ? 'selected' : ''; ?>>Rented</option>
                  </select>
                </form>
              </td>
              <td>
                <div class="action-btn-group">
                  <!-- View Live -->
                  <a href="../property-details.php?id=<?php echo urlencode($prop['id']); ?>" target="_blank" class="btn-icon" title="View Public Page">
                    <svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                  </a>
                  <!-- Edit Property -->
                  <a href="property-form.php?id=<?php echo urlencode($prop['id']); ?>" class="btn-icon" title="Edit Property">
                    <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                  </a>
                  <!-- Delete Property -->
                  <a href="delete-property.php?id=<?php echo urlencode($prop['id']); ?>&token=<?php echo get_csrf_token(); ?>" 
                     class="btn-icon delete btn-confirm-delete" 
                     data-title="<?php echo htmlspecialchars($prop['title']); ?>"
                     title="Delete Property">
                    <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                  </a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include __DIR__ . '/admin-footer.php'; ?>


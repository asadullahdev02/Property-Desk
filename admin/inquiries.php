<?php
/**
 * Abdullah Property Dealer - Customer Inquiries Management
 */
require_once __DIR__ . '/../includes/config.php';
require_admin();

$admin_page = 'inquiries';
$admin_title = 'Customer Inquiries';
$admin_subtitle = 'Review and respond to client leads, site visit requests, and property queries.';

// Handle CSV Export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $inquiries = get_inquiries();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=apd_inquiries_' . date('Ymd_His') . '.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Inquiry ID', 'Customer Name', 'Phone', 'Address', 'Property ID', 'Property Title', 'Customer Message', 'Status', 'Date Received']);
    foreach ($inquiries as $inq) {
        fputcsv($output, [
            $inq['id'] ?? '',
            $inq['name'] ?? '',
            $inq['phone'] ?? '',
            $inq['address'] ?? '',
            $inq['property_id'] ?? '',
            $inq['property_title'] ?? '',
            $inq['message'] ?? '',
            $inq['status'] ?? '',
            $inq['created_at'] ?? ''
        ]);
    }
    fclose($output);
    exit;
}

// Handle Status Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_inquiry_status') {
    $inq_id = trim($_POST['inquiry_id'] ?? '');
    $new_status = trim($_POST['status'] ?? '');

    $valid = ['New', 'Contacted', 'Closed'];
    if (in_array($new_status, $valid)) {
        $inquiries = get_inquiries();
        $updated = false;
        foreach ($inquiries as &$inq) {
            if ($inq['id'] === $inq_id) {
                $inq['status'] = $new_status;
                $updated = true;
                break;
            }
        }
        if ($updated) {
            save_inquiries($inquiries);
            set_flash('success', "Inquiry {$inq_id} status updated to '{$new_status}'.");
        }
    }
    header('Location: inquiries.php');
    exit;
}

$inquiries = get_inquiries();

include __DIR__ . '/admin-header.php';
?>

<div class="admin-card">
  <div class="admin-card-header" style="flex-wrap:wrap;gap:12px;">
    <div>
      <h3>Customer Inquiries Log (<?php echo count($inquiries); ?> Total Records)</h3>
      <span style="font-size:0.8rem;color:var(--adm-grey);">Real estate client leads and WhatsApp inquiries</span>
    </div>
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
      <input type="text" id="adminInqSearch" placeholder="Filter by name, phone, property..." class="filter-select" style="padding:6px 12px;font-size:0.85rem;border-radius:4px;min-width:220px;">
      <a href="inquiries.php?export=csv" class="btn btn-outline btn-sm">Export Leads CSV</a>
    </div>
  </div>

  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>ID & Time</th>
          <th>Customer Details</th>
          <th>Address</th>
          <th>Property Inquired</th>
          <th>Customer Message</th>
          <th>Status</th>
          <th>Direct Contact</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($inquiries)): ?>
          <tr><td colspan="7" style="text-align:center;padding:30px;color:var(--adm-grey);">No inquiries found.</td></tr>
        <?php else: ?>
          <?php foreach ($inquiries as $inq): ?>
            <tr class="admin-inq-row" data-search="<?php echo htmlspecialchars(strtolower($inq['id'] . ' ' . $inq['name'] . ' ' . $inq['phone'] . ' ' . $inq['address'] . ' ' . ($inq['property_title'] ?? '') . ' ' . ($inq['status'] ?? ''))); ?>">
              <td>
                <code><?php echo htmlspecialchars($inq['id']); ?></code><br>
                <span style="font-size:0.75rem;color:var(--adm-grey);"><?php echo htmlspecialchars($inq['created_at']); ?></span>
              </td>
              <td>
                <strong style="color:var(--adm-charcoal);"><?php echo htmlspecialchars($inq['name']); ?></strong><br>
                <span style="font-size:0.85rem;color:var(--adm-grey);"><?php echo htmlspecialchars($inq['phone']); ?></span>
              </td>
              <td style="font-size:0.85rem;max-width:160px;">
                <?php echo htmlspecialchars($inq['address']); ?>
              </td>
              <td>
                <span style="font-weight:600;font-size:0.88rem;"><?php echo htmlspecialchars($inq['property_title'] ?? 'General Inquiry'); ?></span><br>
                <?php if (!empty($inq['property_id']) && $inq['property_id'] !== 'General Inquiry'): ?>
                  <a href="../property-details.php?id=<?php echo urlencode($inq['property_id']); ?>" target="_blank" style="font-size:0.75rem;color:var(--adm-burgundy);">
                    View Listing (<?php echo htmlspecialchars($inq['property_id']); ?>) →
                  </a>
                <?php endif; ?>
              </td>
              <td style="font-size:0.85rem;max-width:280px;line-height:1.5;">
                <?php echo nl2br(htmlspecialchars($inq['message'])); ?>
              </td>
              <td>
                <!-- Quick Status Dropdown -->
                <form method="POST" action="inquiries.php" style="display:inline-block;">
                  <input type="hidden" name="action" value="update_inquiry_status">
                  <input type="hidden" name="inquiry_id" value="<?php echo htmlspecialchars($inq['id']); ?>">
                  <select name="status" onchange="this.form.submit()" class="filter-select" style="padding:4px 8px;font-size:0.78rem;">
                    <option value="New" <?php echo (($inq['status'] ?? 'New') === 'New') ? 'selected' : ''; ?>>New</option>
                    <option value="Contacted" <?php echo (($inq['status'] ?? '') === 'Contacted') ? 'selected' : ''; ?>>Contacted</option>
                    <option value="Closed" <?php echo (($inq['status'] ?? '') === 'Closed') ? 'selected' : ''; ?>>Closed</option>
                  </select>
                </form>
              </td>
              <td>
                <!-- WhatsApp Quick Reply -->
                <?php 
                  $clean_phone = preg_replace('/[^0-9]/', '', $inq['phone']); 
                  $wa_text = "Assalam-o-Alaikum " . $inq['name'] . ", this is Abdullah Property Dealer following up on your inquiry about " . ($inq['property_title'] ?? 'our properties') . ". How can we assist you?";
                ?>
                <a href="https://wa.me/<?php echo $clean_phone; ?>?text=<?php echo urlencode($wa_text); ?>" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="btn btn-sm btn-terracotta" 
                   style="font-size:0.75rem;padding:5px 10px;white-space:nowrap;">
                  WhatsApp Reply
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include __DIR__ . '/admin-footer.php'; ?>


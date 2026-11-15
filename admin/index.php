<?php
/**
 * Abdullah Property Dealer - Admin Dashboard Overview
 */
require_once __DIR__ . '/../includes/config.php';

$admin_page = 'dashboard';
$admin_title = 'Admin Dashboard';
$admin_subtitle = 'Overview of property portfolio, market statuses, and customer inquiries.';

$properties = get_properties();
$inquiries = get_inquiries();
$stats = get_dashboard_stats();

include __DIR__ . '/admin-header.php';
?>

<!-- Statistics Overview Grid -->
<div class="stats-grid">
  <!-- Total Properties -->
  <div class="stat-card">
    <div class="label">Total Properties</div>
    <div class="value"><?php echo $stats['total_properties']; ?></div>
    <div class="sub">Across 6 Pakistani Cities</div>
  </div>

  <!-- Available Properties -->
  <div class="stat-card terracotta">
    <div class="label">Available Listings</div>
    <div class="value"><?php echo $stats['available']; ?></div>
    <div class="sub">Active on market</div>
  </div>

  <!-- Sold Properties -->
  <div class="stat-card charcoal">
    <div class="label">Sold Properties</div>
    <div class="value"><?php echo $stats['sold']; ?></div>
    <div class="sub">Successfully closed deals</div>
  </div>

  <!-- Rented Properties -->
  <div class="stat-card warm">
    <div class="label">Rented Properties</div>
    <div class="value"><?php echo $stats['rented']; ?></div>
    <div class="sub">Occupied on tenancy</div>
  </div>
</div>

<!-- Portfolio Valuation & Active Inventory Strip -->
<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); margin-bottom: 25px;">
  <div class="stat-card" style="border-left: 4px solid var(--adm-burgundy);">
    <div class="label">Total Portfolio Valuation</div>
    <div class="value" style="font-size: 1.5rem;"><?php echo format_pkr($stats['portfolio_value'] ?? 0); ?></div>
    <div class="sub">Aggregate property value under management</div>
  </div>

  <div class="stat-card terracotta" style="border-left: 4px solid var(--adm-terracotta);">
    <div class="label">Active Market Inventory</div>
    <div class="value" style="font-size: 1.5rem;"><?php echo format_pkr($stats['available_value'] ?? 0); ?></div>
    <div class="sub">Valuation of currently available listings</div>
  </div>
</div>

<!-- Secondary Stats Strip (Inquiries) -->
<div style="background:var(--adm-white);border:1px solid var(--adm-border);border-radius:8px;padding:18px 24px;margin-bottom:30px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:15px;">
  <div>
    <strong style="font-family:'Cinzel',serif;font-size:1.1rem;color:var(--adm-charcoal);">Customer Inquiries Received:</strong>
    <span style="font-family:'Cinzel',serif;font-size:1.4rem;font-weight:700;color:var(--adm-burgundy);margin-left:8px;"><?php echo $stats['total_inquiries']; ?> Inquiries</span>
    <span style="font-size:0.85rem;color:var(--adm-grey);margin-left:12px;">(<?php echo $stats['new_inquiries']; ?> awaiting agent follow-up)</span>
  </div>
  <div style="display:flex;gap:10px;">
    <a href="inquiries.php?export=csv" class="btn btn-outline btn-sm">Export Leads CSV</a>
    <a href="inquiries.php" class="btn btn-terracotta btn-sm">View All Inquiries</a>
  </div>
</div>

<!-- Recent Properties Table -->
<div class="admin-card">
  <div class="admin-card-header">
    <h3>Recent Property Listings</h3>
    <a href="properties.php" style="font-size:0.85rem;color:var(--adm-burgundy);font-weight:700;">View All Properties →</a>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Thumb</th>
          <th>Title & Location</th>
          <th>City</th>
          <th>Type</th>
          <th>Purpose</th>
          <th>Price</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach (array_slice($properties, 0, 5) as $prop): ?>
          <tr>
            <td>
              <img src="../<?php echo htmlspecialchars($prop['image']); ?>" alt="Thumb" class="table-thumb">
            </td>
            <td>
              <strong><?php echo htmlspecialchars($prop['title']); ?></strong><br>
              <span style="font-size:0.78rem;color:var(--adm-grey);"><?php echo htmlspecialchars($prop['location']); ?></span>
            </td>
            <td><?php echo htmlspecialchars($prop['city']); ?></td>
            <td><?php echo htmlspecialchars($prop['category']); ?></td>
            <td>
              <span class="badge-pill <?php echo ($prop['purpose'] === 'sale') ? 'badge-sold' : 'badge-rented'; ?>">
                <?php echo ($prop['purpose'] === 'sale') ? 'Sale' : 'Rent'; ?>
              </span>
            </td>
            <td style="font-weight:700;color:var(--adm-burgundy);">
              <?php echo htmlspecialchars($prop['price_display'] ?? format_pkr($prop['price'])); ?>
            </td>
            <td>
              <span class="badge-pill badge-<?php echo strtolower(str_replace(' ', '-', $prop['status'])); ?>">
                <?php echo htmlspecialchars($prop['status']); ?>
              </span>
            </td>
            <td>
              <div class="action-btn-group">
                <a href="../property-details.php?id=<?php echo urlencode($prop['id']); ?>" target="_blank" class="btn-icon" title="View Property">
                  <svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                </a>
                <a href="property-form.php?id=<?php echo urlencode($prop['id']); ?>" class="btn-icon" title="Edit Property">
                  <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                </a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Recent Inquiries Table -->
<div class="admin-card">
  <div class="admin-card-header">
    <h3>Recent Customer Inquiries</h3>
    <a href="inquiries.php" style="font-size:0.85rem;color:var(--adm-burgundy);font-weight:700;">View All Inquiries →</a>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Inquiry ID</th>
          <th>Customer Name</th>
          <th>Phone (+92)</th>
          <th>Property of Interest</th>
          <th>Status</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($inquiries)): ?>
          <tr><td colspan="7" style="text-align:center;padding:25px;color:var(--adm-grey);">No inquiries received yet.</td></tr>
        <?php else: ?>
          <?php foreach (array_slice($inquiries, 0, 5) as $inq): ?>
            <tr>
              <td><code><?php echo htmlspecialchars($inq['id']); ?></code></td>
              <td><strong><?php echo htmlspecialchars($inq['name']); ?></strong></td>
              <td>
                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $inq['phone']); ?>?text=Hello%20<?php echo urlencode($inq['name']); ?>,%20this%20is%20Abdullah%20Property%20Dealer%20regarding%20your%20inquiry." target="_blank" style="color:var(--adm-terracotta);font-weight:600;">
                  <?php echo htmlspecialchars($inq['phone']); ?>
                </a>
              </td>
              <td><?php echo htmlspecialchars($inq['property_title'] ?? $inq['property_id']); ?></td>
              <td>
                <span class="badge-pill badge-<?php echo strtolower($inq['status'] ?? 'new'); ?>">
                  <?php echo htmlspecialchars($inq['status'] ?? 'New'); ?>
                </span>
              </td>
              <td style="font-size:0.8rem;color:var(--adm-grey);"><?php echo htmlspecialchars($inq['created_at']); ?></td>
              <td>
                <a href="inquiries.php" class="btn btn-sm btn-outline" style="padding:4px 8px;font-size:0.75rem;">Manage</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include __DIR__ . '/admin-footer.php'; ?>


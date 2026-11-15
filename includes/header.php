<?php
/**
 * Abdullah Property Dealer - Reusable Header Component
 */
require_once __DIR__ . '/config.php';

$current_page = $current_page ?? 'home';
$page_title = $page_title ?? 'Abdullah Property Dealer | Premier Real Estate Agency in Pakistan';
$page_desc = $page_desc ?? 'Discover premium houses, apartments, plots and commercial properties across Pakistan with Abdullah Property Dealer.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?php echo htmlspecialchars($page_desc); ?>">
  <title><?php echo htmlspecialchars($page_title); ?></title>
  
  <!-- Favicon Initials APD -->
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%236B1724'/><text x='50%' y='63%' dominant-baseline='middle' text-anchor='middle' font-family='Georgia,serif' font-size='44' font-weight='bold' fill='%23FAF7F2'>APD</text></svg>">

  <!-- Core Stylesheet -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

  <!-- Demo Notice Banner -->
  <div class="demo-banner">
    <span><strong>Demo Mode:</strong> All property listings, pricing, and inquiries are for demonstration and portfolio testing.</span>
    <span class="d-none-sm">|</span>
    <span>Location: <strong>Pakistan</strong> (Lahore, Islamabad, Karachi, Rawalpindi, Faisalabad, Kasur)</span>
  </div>

  <!-- Topbar -->
  <div class="topbar">
    <div class="container">
      <div class="topbar-info">
        <div class="topbar-item">
          <!-- Location Icon -->
          <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
          <span>Gulberg III, Lahore, Pakistan</span>
        </div>
        <div class="topbar-item">
          <!-- Phone Icon -->
          <svg viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
          <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', AGENT_PHONE); ?>"><?php echo htmlspecialchars(AGENT_PHONE); ?></a>
        </div>
        <div class="topbar-item">
          <!-- Time Icon -->
          <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
          <span>Mon - Sat: 9:00 AM - 7:00 PM (PKT)</span>
        </div>
      </div>
      <div class="topbar-links">
        <a href="properties.php?favorites=1" class="topbar-fav-link" id="topbarFavLink" title="View Saved Properties Shortlist">
          <svg style="width:14px;height:14px;fill:currentColor;" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
          <span>Saved (<span class="fav-count-badge">0</span>)</span>
        </a>
        <a href="admin/login.php" class="admin-link-btn" title="Admin Control Panel">
          Admin Portal
        </a>
      </div>
    </div>
  </div>

  <!-- Main Navigation Bar -->
  <header class="navbar">
    <div class="container">
      <!-- Brand Logo with APD Initials -->
      <a href="index.php" class="brand-logo" aria-label="Abdullah Property Dealer Home">
        <div class="logo-badge">APD</div>
        <div class="brand-text">
          <span class="brand-name">Abdullah Property Dealer</span>
          <span class="brand-sub">Pakistan Real Estate</span>
        </div>
      </a>

      <!-- Navigation Links -->
      <nav class="nav-menu" id="navMenu">
        <a href="index.php" class="nav-link <?php echo ($current_page === 'home') ? 'active' : ''; ?>">Home</a>
        <a href="properties.php?purpose=sale" class="nav-link <?php echo ($current_page === 'buy') ? 'active' : ''; ?>">Buy Property</a>
        <a href="properties.php?purpose=rent" class="nav-link <?php echo ($current_page === 'rent') ? 'active' : ''; ?>">Rent Property</a>
        <a href="properties.php?type=Commercial+Properties" class="nav-link <?php echo ($current_page === 'commercial') ? 'active' : ''; ?>">Commercial</a>
        <a href="properties.php?favorites=1" class="nav-link nav-link-fav" title="Saved Properties Shortlist">
          <svg style="width:14px;height:14px;fill:currentColor;vertical-align:-2px;margin-right:4px;" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>Saved (<span class="fav-count-badge">0</span>)
        </a>
        <a href="index.php#aboutSection" class="nav-link <?php echo ($current_page === 'about') ? 'active' : ''; ?>">About Us</a>
        <a href="index.php#contactSection" class="nav-link <?php echo ($current_page === 'contact') ? 'active' : ''; ?>">Contact</a>
      </nav>

      <!-- Prominent Contact Agent CTA Button -->
      <div class="nav-cta">
        <a href="index.php#contactSection" class="btn btn-burgundy">
          <!-- User / Agent SVG -->
          <svg style="width:16px;height:16px;fill:currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
          <span>Contact Agent</span>
        </a>
        <button class="mobile-toggle" id="mobileMenuToggle" aria-label="Open Navigation Menu">
          <span></span>
          <span></span>
          <span></span>
        </button>
      </div>
    </div>
  </header>


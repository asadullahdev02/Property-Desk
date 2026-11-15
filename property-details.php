<?php
/**
 * Abdullah Property Dealer - Dedicated Property Details Page
 */
require_once __DIR__ . '/includes/config.php';

$prop_id = $_GET['id'] ?? '';
$property = get_property_by_id($prop_id);

if (!$property) {
    // Redirect or 404
    http_response_code(404);
    $page_title = 'Property Not Found | Abdullah Property Dealer';
    include __DIR__ . '/includes/header.php';
    ?>
    <div class="container section-padding" style="text-align: center;">
      <h2 style="font-family: var(--font-serif); font-size: 2rem; color: var(--c-burgundy); margin-bottom: 12px;">Property Not Found</h2>
      <p style="color: var(--c-grey-dark); margin-bottom: 24px;">The property you are looking for does not exist or may have been removed.</p>
      <a href="properties.php" class="btn btn-burgundy">Browse All Properties</a>
    </div>
    <?php
    include __DIR__ . '/includes/footer.php';
    exit;
}

$page_title = htmlspecialchars($property['title']) . ' | Abdullah Property Dealer';
$page_desc = htmlspecialchars(substr($property['description'], 0, 160));
$current_page = ($property['purpose'] === 'rent') ? 'rent' : 'buy';

$gallery = !empty($property['gallery']) ? $property['gallery'] : [$property['image']];

include __DIR__ . '/includes/header.php';
?>

<!-- Breadcrumbs Bar -->
<div class="breadcrumb-bar">
  <div class="container">
    <div class="breadcrumb-list">
      <a href="index.php">Home</a>
      <span class="breadcrumb-separator">/</span>
      <a href="properties.php">Properties</a>
      <span class="breadcrumb-separator">/</span>
      <a href="properties.php?city=<?php echo urlencode($property['city']); ?>"><?php echo htmlspecialchars($property['city']); ?></a>
      <span class="breadcrumb-separator">/</span>
      <span style="color:var(--c-burgundy);"><?php echo htmlspecialchars($property['id']); ?></span>
    </div>
  </div>
</div>

<!-- Header Section: Title, Address, Price, Status -->
<section class="prop-detail-header">
  <div class="container">
    <div class="prop-detail-header-flex">
      <div class="detail-headline">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;flex-wrap:wrap;">
          <span class="badge-purpose <?php echo strtolower($property['purpose']); ?>">
            <?php echo ($property['purpose'] === 'sale') ? 'For Sale' : 'For Rent'; ?>
          </span>
          <span class="badge-status <?php echo strtolower($property['status']); ?>">
            Status: <?php echo htmlspecialchars($property['status']); ?>
          </span>
          <span class="badge-category"><?php echo htmlspecialchars($property['category']); ?></span>
          <span style="font-size:0.8rem;color:var(--c-grey-medium);font-weight:600;">ID: <?php echo htmlspecialchars($property['id']); ?></span>
        </div>

        <h1><?php echo htmlspecialchars($property['title']); ?></h1>

        <div class="detail-address">
          <svg style="width:16px;height:16px;fill:var(--c-terracotta);flex-shrink:0;" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
          <span><?php echo htmlspecialchars($property['location']); ?></span>
        </div>
      </div>

      <!-- Price Valuation in PKR -->
      <div class="detail-price-box">
        <div class="detail-price-val">
          <?php echo htmlspecialchars($property['price_display'] ?? format_pkr($property['price'], $property['purpose'])); ?>
        </div>
        <div class="detail-price-unit">
          Price in Pakistani Rupees (PKR)
        </div>
        
        <!-- Action Buttons -->
        <div class="detail-actions-bar">
          <button type="button" class="btn-detail-action btn-fav-detail" id="btnFavDetail" data-prop-id="<?php echo htmlspecialchars($property['id']); ?>" title="Save to Favorites">
            <svg class="heart-icon" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
            <span class="fav-detail-label">Save to Favorites</span>
          </button>
          <button type="button" class="btn-detail-action" id="btnShareDetail" title="Copy Property Link">
            <svg viewBox="0 0 24 24"><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92c0-1.61-1.31-2.92-2.92-2.92z"/></svg>
            <span>Share</span>
          </button>
          <button type="button" class="btn-detail-action" onclick="window.print()" title="Print Specification Sheet">
            <svg viewBox="0 0 24 24"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>
            <span>Print Sheet</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Main Property Layout -->
<section class="container">
  
  <!-- Multi-Image Interactive Gallery -->
  <div class="detail-gallery-wrap">
    <div class="main-image-viewer">
      <img id="mainPropertyImage" src="<?php echo htmlspecialchars($property['image']); ?>" alt="<?php echo htmlspecialchars($property['title']); ?>">
    </div>
    
    <!-- Thumbnail Strip -->
    <?php if (count($gallery) > 1): ?>
      <div class="thumb-strip">
        <?php foreach ($gallery as $index => $img_path): ?>
          <div class="thumb-item <?php echo ($index === 0) ? 'active' : ''; ?>" data-full-img="<?php echo htmlspecialchars($img_path); ?>">
            <img src="<?php echo htmlspecialchars($img_path); ?>" alt="Thumbnail <?php echo $index + 1; ?>">
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- Content Split Layout -->
  <div class="detail-main-layout">
    
    <!-- Left Column: Specs, Description, Amenities -->
    <div class="detail-content-col">
      
      <!-- Key Statistics Strip -->
      <div class="detail-key-stats">
        <div class="key-stat-box">
          <div class="key-stat-num"><?php echo ($property['bedrooms'] > 0) ? $property['bedrooms'] : 'N/A'; ?></div>
          <div class="key-stat-lbl">Bedrooms</div>
        </div>
        <div class="key-stat-box">
          <div class="key-stat-num"><?php echo ($property['bathrooms'] > 0) ? $property['bathrooms'] : 'N/A'; ?></div>
          <div class="key-stat-lbl">Bathrooms</div>
        </div>
        <div class="key-stat-box">
          <div class="key-stat-num"><?php echo htmlspecialchars($property['area_value'] . ' ' . $property['area_unit']); ?></div>
          <div class="key-stat-lbl">Property Size</div>
        </div>
        <div class="key-stat-box">
          <div class="key-stat-num"><?php echo htmlspecialchars($property['covered_area'] ?? 'N/A'); ?></div>
          <div class="key-stat-lbl">Covered Area</div>
        </div>
      </div>

      <!-- Description Section -->
      <div class="detail-section-card">
        <h3>Property Overview & Architecture</h3>
        <div class="detail-text-body">
          <p><?php echo nl2br(htmlspecialchars($property['description'])); ?></p>
        </div>
      </div>

      <!-- Amenities Section -->
      <?php if (!empty($property['amenities'])): ?>
        <div class="detail-section-card">
          <h3>Features & Amenities</h3>
          <div class="amenities-list-grid">
            <?php foreach ($property['amenities'] as $amenity): ?>
              <div class="amenity-chip">
                <!-- Check Icon in Burgundy -->
                <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                <span><?php echo htmlspecialchars($amenity); ?></span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

      <!-- Quick Summary Table -->
      <div class="detail-section-card">
        <h3>Property Specifications</h3>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;font-size:0.92rem;">
          <div style="padding:8px 0;border-bottom:1px solid var(--c-grey-border);">
            <strong style="color:var(--c-grey-medium);">Property ID:</strong> <?php echo htmlspecialchars($property['id']); ?>
          </div>
          <div style="padding:8px 0;border-bottom:1px solid var(--c-grey-border);">
            <strong style="color:var(--c-grey-medium);">Purpose:</strong> <?php echo ($property['purpose'] === 'sale') ? 'For Sale' : 'For Rent'; ?>
          </div>
          <div style="padding:8px 0;border-bottom:1px solid var(--c-grey-border);">
            <strong style="color:var(--c-grey-medium);">Category:</strong> <?php echo htmlspecialchars($property['category']); ?>
          </div>
          <div style="padding:8px 0;border-bottom:1px solid var(--c-grey-border);">
            <strong style="color:var(--c-grey-medium);">City:</strong> <?php echo htmlspecialchars($property['city']); ?>
          </div>
          <div style="padding:8px 0;border-bottom:1px solid var(--c-grey-border);">
            <strong style="color:var(--c-grey-medium);">Size:</strong> <?php echo htmlspecialchars($property['area_value'] . ' ' . $property['area_unit']); ?>
          </div>
          <div style="padding:8px 0;border-bottom:1px solid var(--c-grey-border);">
            <strong style="color:var(--c-grey-medium);">Current Status:</strong> <?php echo htmlspecialchars($property['status']); ?>
          </div>
        </div>
      </div>

      <!-- PKR Real Estate Mortgage & Installment Financing Calculator -->
      <div class="detail-section-card mortgage-calc-card" id="mortgageCalculator">
        <div class="calc-header-wrap">
          <div class="calc-icon-badge">
            <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
          </div>
          <div>
            <h3>PKR Financing & Installment Calculator</h3>
            <p class="calc-subtext">Estimate monthly installment options based on prevailing Pakistani bank KIBOR & Islamic financing rates.</p>
          </div>
        </div>

        <div class="calc-grid-layout">
          <!-- Input Controls -->
          <div class="calc-controls">
            <div class="calc-input-group">
              <label for="calcPropPrice" class="calc-label">Property Price (PKR)</label>
              <div class="calc-input-with-affix">
                <span class="affix">PKR</span>
                <input type="number" id="calcPropPrice" class="calc-field-control" value="<?php echo (float)$property['price']; ?>" min="500000" step="100000">
              </div>
              <div class="calc-price-word" id="calcPriceFormatted"></div>
            </div>

            <div class="calc-input-group">
              <div class="calc-label-row">
                <label for="calcDownPct" class="calc-label">Down Payment: <strong id="calcDownPctLabel" class="calc-dyn-val">20%</strong></label>
                <span class="calc-badge-amt" id="calcDownAmtLabel">PKR 0</span>
              </div>
              <input type="range" id="calcDownPct" min="10" max="60" step="5" value="20" class="calc-slider-bar">
              <div class="calc-pills-row">
                <button type="button" class="calc-pill-pct" data-pct="15">15%</button>
                <button type="button" class="calc-pill-pct active" data-pct="20">20%</button>
                <button type="button" class="calc-pill-pct" data-pct="25">25%</button>
                <button type="button" class="calc-pill-pct" data-pct="30">30%</button>
                <button type="button" class="calc-pill-pct" data-pct="50">50%</button>
              </div>
            </div>

            <div class="calc-input-group">
              <div class="calc-label-row">
                <label for="calcTenure" class="calc-label">Financing Period: <strong id="calcTenureLabel" class="calc-dyn-val">15 Years</strong></label>
                <span class="calc-badge-amt" id="calcMonthsLabel">180 Months</span>
              </div>
              <input type="range" id="calcTenure" min="1" max="25" step="1" value="15" class="calc-slider-bar">
              <div class="calc-pills-row">
                <button type="button" class="calc-pill-tenure" data-years="5">5 Yrs</button>
                <button type="button" class="calc-pill-tenure" data-years="10">10 Yrs</button>
                <button type="button" class="calc-pill-tenure active" data-years="15">15 Yrs</button>
                <button type="button" class="calc-pill-tenure" data-years="20">20 Yrs</button>
                <button type="button" class="calc-pill-tenure" data-years="25">25 Yrs</button>
              </div>
            </div>

            <div class="calc-input-group">
              <div class="calc-label-row">
                <label for="calcRate" class="calc-label">Estimated Bank Profit Rate (KIBOR + Margin)</label>
                <span class="calc-badge-amt" id="calcRateLabel">15.5% Annual</span>
              </div>
              <div class="calc-input-with-affix">
                <input type="number" id="calcRate" class="calc-field-control" value="15.5" min="5" max="30" step="0.25">
                <span class="affix suffix">%</span>
              </div>
              <small class="calc-note">Indicative rate for HBL, Meezan, Bank Alfalah, and Standard Chartered Home Loans.</small>
            </div>
          </div>

          <!-- Calculation Outputs Summary -->
          <div class="calc-summary-box">
            <div class="calc-monthly-card">
              <span class="monthly-tag">Estimated Monthly Installment</span>
              <div class="monthly-number" id="calcMonthlyVal">PKR 0</div>
              <span class="monthly-sub">Principal repayment + bank markup</span>
            </div>

            <div class="calc-specs-breakdown">
              <div class="calc-spec-line">
                <span>Financed Loan Amount:</span>
                <strong id="calcLoanVal">PKR 0</strong>
              </div>
              <div class="calc-spec-line">
                <span>Initial Down Payment:</span>
                <strong id="calcDownVal">PKR 0</strong>
              </div>
              <div class="calc-spec-line">
                <span>Total Markup / Profit:</span>
                <strong id="calcProfitVal">PKR 0</strong>
              </div>
              <div class="calc-spec-line total-line">
                <span>Total Financing Repayment:</span>
                <strong id="calcTotalVal">PKR 0</strong>
              </div>
            </div>

            <button type="button" id="btnApplyPlanToInquiry" class="btn btn-burgundy btn-block" style="display:flex;align-items:center;justify-content:center;gap:8px;">
              <svg style="width:16px;height:16px;fill:currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/></svg>
              <span>Apply Plan to Inquiry Form</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column: Agent Profile & Inquire Form -->
    <div class="detail-sidebar-col">
      <div class="detail-agent-box">
        
        <!-- Agent Bio Header -->
        <div class="agent-profile-header">
          <img src="assets/images/about-agent.jpg" alt="Agent Abdullah Malik" class="agent-avatar">
          <div class="agent-meta">
            <h4>Abdullah Malik</h4>
            <p>Principal Property Consultant</p>
            <span style="font-size:0.75rem;color:var(--c-burgundy);font-weight:700;">★ Verified Real Estate Broker</span>
          </div>
        </div>

        <!-- Direct Contact Actions -->
        <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:24px;">
          <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', AGENT_PHONE); ?>" class="btn btn-burgundy btn-block">
            <svg style="width:16px;height:16px;fill:currentColor" viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
            <span>Call <?php echo htmlspecialchars(AGENT_PHONE); ?></span>
          </a>

          <!-- WhatsApp Contact Agent -->
          <a href="https://wa.me/<?php echo AGENT_WHATSAPP; ?>?text=Assalam-o-Alaikum,%20I%20am%20interested%20in%20visiting%20property%20<?php echo urlencode($property['title'] . ' (ID: ' . $property['id'] . ') priced at ' . ($property['price_display'] ?? format_pkr($property['price']))); ?>" 
             target="_blank" 
             rel="noopener noreferrer"
             class="btn btn-terracotta btn-block">
            <svg style="width:16px;height:16px;fill:currentColor" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.16 12.04 20.16C10.66 20.16 9.3 19.8 8.1 19.09L7.81 18.92L4.69 19.74L5.52 16.7L5.33 16.4C4.55 15.15 4.13 13.55 4.13 11.92C4.13 7.37 7.84 3.67 12.05 3.67M9.27 7.55C9.07 7.55 8.74 7.62 8.46 7.92C8.18 8.23 7.39 8.97 7.39 10.45C7.39 11.93 8.47 13.36 8.62 13.56C8.77 13.76 10.74 16.8 13.76 18.1C14.48 18.41 15.04 18.6 15.48 18.74C16.2 18.97 16.85 18.94 17.37 18.86C17.95 18.77 19.16 18.12 19.41 17.41C19.67 16.7 19.67 16.09 19.59 15.96C19.51 15.83 19.31 15.76 19.01 15.61C18.71 15.46 17.23 14.73 16.96 14.63C16.68 14.53 16.48 14.48 16.28 14.78C16.08 15.09 15.5 15.76 15.33 15.96C15.15 16.16 14.98 16.18 14.68 16.03C14.38 15.88 13.41 15.56 12.27 14.54C11.38 13.75 10.78 12.77 10.63 12.52C10.48 12.26 10.61 12.13 10.76 11.98C10.9 11.85 11.06 11.63 11.21 11.46C11.36 11.28 11.41 11.16 11.51 10.96C11.61 10.76 11.56 10.58 11.49 10.43C11.41 10.28 10.84 8.88 10.6 8.3C10.37 7.74 10.13 7.82 9.96 7.81C9.8 7.8 9.61 7.8 9.42 7.8L9.27 7.55Z"/></svg>
            <span>WhatsApp Inquiry</span>
          </a>
        </div>

        <!-- Inquire About This Property Form -->
        <h4 style="font-family:var(--font-serif);font-size:1.15rem;color:var(--c-charcoal);margin-bottom:12px;">Inquire About Property</h4>
        
        <form id="propertyInquiryForm" method="POST" action="api/contact.php">
          <input type="hidden" name="property_id" value="<?php echo htmlspecialchars($property['id']); ?>">
          <input type="hidden" name="property_title" value="<?php echo htmlspecialchars($property['title']); ?>">

          <div class="form-group">
            <label class="form-label" for="detailInqName">Name</label>
            <input type="text" id="detailInqName" name="name" class="form-control" placeholder="Enter your name" required>
          </div>

          <div class="form-group">
            <label class="form-label" for="detailInqAddress">Address</label>
            <input type="text" id="detailInqAddress" name="address" class="form-control" placeholder="Your address" required>
          </div>

          <div class="form-group">
            <label class="form-label" for="detailInqPhone">Phone Number</label>
            <div class="phone-input-group">
              <label for="detailInqPhone" class="phone-prefix">+92</label>
              <input type="tel" id="detailInqPhone" name="phone" class="form-control" required>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="detailInqMsg">Message</label>
            <textarea id="detailInqMsg" name="message" class="form-control" rows="3" required>Assalam-o-Alaikum, I am interested in <?php echo htmlspecialchars($property['title']); ?> (<?php echo htmlspecialchars($property['id']); ?>). Please share meeting details.</textarea>
          </div>

          <button type="submit" class="btn btn-burgundy btn-block" style="display:flex;align-items:center;justify-content:center;gap:8px;">
            <svg style="width:16px;height:16px;fill:currentColor" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.16 12.04 20.16C10.66 20.16 9.3 19.8 8.1 19.09L7.81 18.92L4.69 19.74L5.52 16.7L5.33 16.4C4.55 15.15 4.13 13.55 4.13 11.92C4.13 7.37 7.84 3.67 12.05 3.67M9.27 7.55C9.07 7.55 8.74 7.62 8.46 7.92C8.18 8.23 7.39 8.97 7.39 10.45C7.39 11.93 8.47 13.36 8.62 13.56C8.77 13.76 10.74 16.8 13.76 18.1C14.48 18.41 15.04 18.6 15.48 18.74C16.2 18.97 16.85 18.94 17.37 18.86C17.95 18.77 19.16 18.12 19.41 17.41C19.67 16.7 19.67 16.09 19.59 15.96C19.51 15.83 19.31 15.76 19.01 15.61C18.71 15.46 17.23 14.73 16.96 14.63C16.68 14.53 16.48 14.48 16.28 14.78C16.08 15.09 15.5 15.76 15.33 15.96C15.15 16.16 14.98 16.18 14.68 16.03C14.38 15.88 13.41 15.56 12.27 14.54C11.38 13.75 10.78 12.77 10.63 12.52C10.48 12.26 10.61 12.13 10.76 11.98C10.9 11.85 11.06 11.63 11.21 11.46C11.36 11.28 11.41 11.16 11.51 10.96C11.61 10.76 11.56 10.58 11.49 10.43C11.41 10.28 10.84 8.88 10.6 8.3C10.37 7.74 10.13 7.82 9.96 7.81C9.8 7.8 9.61 7.8 9.42 7.8L9.27 7.55Z"/></svg>
            <span>Submit Inquiry & Send to WhatsApp</span>
          </button>
        </form>

      </div>
    </div>

  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>


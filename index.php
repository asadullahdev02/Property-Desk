<?php
/**
 * Abdullah Property Dealer - Homepage
 * Built with HTML5, CSS3, Vanilla JS, Core PHP, JSON demo data
 */
require_once __DIR__ . '/includes/config.php';

$current_page = 'home';
$page_title = 'Abdullah Property Dealer | Premier Real Estate Agency in Pakistan';
$page_desc = 'Discover premium houses, luxury villas, apartments, commercial plazas and plots across Lahore, Islamabad, Karachi, Rawalpindi, Faisalabad and Kasur.';

$properties = get_properties();
$cities = ['Lahore', 'Islamabad', 'Karachi', 'Rawalpindi', 'Faisalabad', 'Kasur'];
$categories = [
    [
        'name' => 'Houses',
        'image' => 'assets/images/house-1.jpg',
        'count' => count(array_filter($properties, fn($p) => $p['category'] === 'Houses'))
    ],
    [
        'name' => 'Apartments',
        'image' => 'assets/images/apartment-1.jpg',
        'count' => count(array_filter($properties, fn($p) => $p['category'] === 'Apartments'))
    ],
    [
        'name' => 'Residential Plots',
        'image' => 'assets/images/plot-1.jpg',
        'count' => count(array_filter($properties, fn($p) => $p['category'] === 'Residential Plots'))
    ],
    [
        'name' => 'Commercial Properties',
        'image' => 'assets/images/commercial-1.jpg',
        'count' => count(array_filter($properties, fn($p) => $p['category'] === 'Commercial Properties'))
    ],
    [
        'name' => 'Shops',
        'image' => 'assets/images/shop-1.jpg',
        'count' => count(array_filter($properties, fn($p) => $p['category'] === 'Shops'))
    ],
    [
        'name' => 'Luxury Villas',
        'image' => 'assets/images/villa-1.jpg',
        'count' => count(array_filter($properties, fn($p) => $p['category'] === 'Luxury Villas'))
    ]
];

include __DIR__ . '/includes/header.php';
?>

<!-- ==========================================================================
     HERO SECTION
     ========================================================================== -->
<section class="hero-section">
  <div class="container">
    <div class="hero-content">
      <div class="hero-location-tag">
        <span>📍 Pakistan Real Estate Excellence</span>
      </div>
      <h1 class="hero-title">Find Your Perfect Property in Pakistan</h1>
      <p class="hero-subtitle">
        Discover premium houses, apartments, plots and commercial properties with Abdullah Property Dealer.
      </p>
    </div>

    <!-- Property Search Interface -->
    <div class="hero-search-wrapper">
      <!-- Purpose Selection -->
      <div class="search-purpose-tabs">
        <button type="button" class="purpose-tab-btn active" data-purpose="buy">Buy Property</button>
        <button type="button" class="purpose-tab-btn" data-purpose="rent">Rent Property</button>
      </div>

      <form id="heroSearchForm">
        <div class="search-fields-grid">
          <!-- City Selection -->
          <div class="search-field-group">
            <label class="search-label" for="heroCity">City</label>
            <select id="heroCity" class="search-select">
              <option value="all">All Pakistani Cities</option>
              <?php foreach ($cities as $city): ?>
                <option value="<?php echo htmlspecialchars($city); ?>"><?php echo htmlspecialchars($city); ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Property Type Selection -->
          <div class="search-field-group">
            <label class="search-label" for="heroType">Property Type</label>
            <select id="heroType" class="search-select">
              <option value="all">All Property Types</option>
              <option value="Houses">Houses</option>
              <option value="Apartments">Apartments</option>
              <option value="Residential Plots">Residential Plots</option>
              <option value="Commercial Properties">Commercial Properties</option>
              <option value="Shops">Shops</option>
              <option value="Luxury Villas">Luxury Villas</option>
            </select>
          </div>

          <!-- Minimum Price in PKR -->
          <div class="search-field-group">
            <label class="search-label" for="heroMinPrice">Min Price (PKR)</label>
            <select id="heroMinPrice" class="search-select">
              <option value="">Any Minimum</option>
              <option value="5000000">PKR 50 Lakh (5M)</option>
              <option value="10000000">PKR 1 Crore (10M)</option>
              <option value="20000000">PKR 2 Crore (20M)</option>
              <option value="35000000">PKR 3.5 Crore (35M)</option>
              <option value="50000000">PKR 5 Crore (50M)</option>
            </select>
          </div>

          <!-- Maximum Price in PKR -->
          <div class="search-field-group">
            <label class="search-label" for="heroMaxPrice">Max Price (PKR)</label>
            <select id="heroMaxPrice" class="search-select">
              <option value="">Any Maximum</option>
              <option value="20000000">PKR 2 Crore (20M)</option>
              <option value="50000000">PKR 5 Crore (50M)</option>
              <option value="100000000">PKR 10 Crore (100M)</option>
              <option value="250000000">PKR 25 Crore (250M)</option>
            </select>
          </div>

          <!-- Search Button -->
          <button type="submit" class="btn btn-burgundy btn-search">
            <svg style="width:16px;height:16px;fill:currentColor" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
            <span>Search Properties</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</section>

<!-- ==========================================================================
     PROPERTY CATEGORIES
     ========================================================================== -->
<section class="section-padding categories-section">
  <div class="container">
    <div class="section-head">
      <span class="section-tag">Explore Portfolio</span>
      <h2 class="section-title">Property Categories</h2>
      <p class="section-subtitle">
        Browse verified listings categorized by architectural design, purpose, and investment potential across Pakistan.
      </p>
    </div>

    <div class="category-grid">
      <?php foreach ($categories as $cat): ?>
        <div class="category-card" data-category="<?php echo htmlspecialchars($cat['name']); ?>">
          <div class="category-img-wrap">
            <img src="<?php echo htmlspecialchars($cat['image']); ?>" alt="<?php echo htmlspecialchars($cat['name']); ?> in Pakistan" loading="lazy">
          </div>
          <div class="category-info">
            <h4 class="category-title"><?php echo htmlspecialchars($cat['name']); ?></h4>
            <span class="category-count"><?php echo $cat['count']; ?> Available</span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ==========================================================================
     FEATURED PROPERTY LISTINGS & SEARCH FILTERING
     ========================================================================== -->
<section class="section-padding listings-section" id="featuredListings">
  <div class="container">
    <div class="section-head">
      <span class="section-tag">Curated Listings</span>
      <h2 class="section-title">Featured Property Listings</h2>
      <p class="section-subtitle">
        Explore prime residential and commercial properties in Lahore, Islamabad, Karachi, Rawalpindi, Faisalabad, and Kasur.
      </p>
    </div>

    <!-- Working JavaScript Filter Toolbar -->
    <div class="filter-toolbar">
      <div class="filter-row">
        <div class="filter-left">
          <!-- Purpose Filter -->
          <select id="filterPurpose" class="filter-select" aria-label="Filter by Purpose">
            <option value="all">Purpose: All (Sale / Rent)</option>
            <option value="sale">For Sale</option>
            <option value="rent">For Rent</option>
          </select>

          <!-- City Filter -->
          <select id="filterCity" class="filter-select" aria-label="Filter by City">
            <option value="all">City: All Locations</option>
            <?php foreach ($cities as $city): ?>
              <option value="<?php echo htmlspecialchars($city); ?>"><?php echo htmlspecialchars($city); ?></option>
            <?php endforeach; ?>
          </select>

          <!-- Type Filter -->
          <select id="filterType" class="filter-select" aria-label="Filter by Property Type">
            <option value="all">Type: All Categories</option>
            <option value="Houses">Houses</option>
            <option value="Apartments">Apartments</option>
            <option value="Residential Plots">Residential Plots</option>
            <option value="Commercial Properties">Commercial Properties</option>
            <option value="Shops">Shops</option>
            <option value="Luxury Villas">Luxury Villas</option>
          </select>

          <!-- Bedrooms Filter -->
          <select id="filterBedrooms" class="filter-select" aria-label="Filter by Bedrooms">
            <option value="all">Bedrooms: Any</option>
            <option value="1">1 Bedroom</option>
            <option value="2">2 Bedrooms</option>
            <option value="3">3 Bedrooms</option>
            <option value="4">4 Bedrooms</option>
            <option value="5+">5+ Bedrooms</option>
          </select>

          <!-- Min Price -->
          <input type="number" id="filterMinPrice" class="filter-select" placeholder="Min PKR" style="width: 120px;">
          <!-- Max Price -->
          <input type="number" id="filterMaxPrice" class="filter-select" placeholder="Max PKR" style="width: 120px;">
        </div>

        <div class="filter-right">
          <!-- Available properties only -->
          <label class="checkbox-label">
            <input type="checkbox" id="filterAvailableOnly">
            <span>Available Only</span>
          </label>

          <!-- Clear Filters Button -->
          <button type="button" id="clearFiltersBtn" class="btn btn-sm btn-outline">Clear Filters</button>

          <!-- Counter -->
          <span class="filter-counter" id="resultsCounter">Showing <?php echo count($properties); ?> properties</span>
        </div>
      </div>
    </div>

    <!-- Property Cards Grid -->
    <div class="property-grid" id="propertiesGrid">
      <?php foreach ($properties as $prop): ?>
        <article class="property-card" 
                 data-id="<?php echo htmlspecialchars($prop['id']); ?>"
                 data-purpose="<?php echo htmlspecialchars($prop['purpose']); ?>"
                 data-city="<?php echo htmlspecialchars($prop['city']); ?>"
                 data-category="<?php echo htmlspecialchars($prop['category']); ?>"
                 data-price="<?php echo (float)$prop['price']; ?>"
                 data-bedrooms="<?php echo (int)$prop['bedrooms']; ?>"
                 data-status="<?php echo htmlspecialchars($prop['status']); ?>">
          
          <!-- Thumbnail & Badges -->
          <div class="property-thumb-wrap">
            <img src="<?php echo htmlspecialchars($prop['image']); ?>" alt="<?php echo htmlspecialchars($prop['title']); ?>" loading="lazy">
            
            <div class="prop-badge-group">
              <span class="badge-purpose <?php echo strtolower($prop['purpose']); ?>">
                <?php echo ($prop['purpose'] === 'sale') ? 'For Sale' : 'For Rent'; ?>
              </span>
              <?php if (strtolower($prop['status']) !== 'available'): ?>
                <span class="badge-status <?php echo strtolower($prop['status']); ?>">
                  <?php echo htmlspecialchars($prop['status']); ?>
                </span>
              <?php endif; ?>
            </div>

            <div class="prop-badge-right">
              <span class="badge-category"><?php echo htmlspecialchars($prop['category']); ?></span>
            </div>
          </div>

          <!-- Property Card Details -->
          <div class="property-body">
            <div class="property-price">
              <?php echo htmlspecialchars($prop['price_display'] ?? format_pkr($prop['price'], $prop['purpose'])); ?>
            </div>
            <h3 class="property-title">
              <a href="property-details.php?id=<?php echo urlencode($prop['id']); ?>">
                <?php echo htmlspecialchars($prop['title']); ?>
              </a>
            </h3>

            <div class="property-location">
              <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
              <span><?php echo htmlspecialchars($prop['location']); ?></span>
            </div>

            <!-- Specs Strip -->
            <div class="property-specs">
              <div class="spec-item">
                <span class="spec-val"><?php echo ($prop['bedrooms'] > 0) ? $prop['bedrooms'] : 'N/A'; ?></span>
                <span class="spec-label">Beds</span>
              </div>
              <div class="spec-item">
                <span class="spec-val"><?php echo ($prop['bathrooms'] > 0) ? $prop['bathrooms'] : 'N/A'; ?></span>
                <span class="spec-label">Baths</span>
              </div>
              <div class="spec-item">
                <span class="spec-val"><?php echo htmlspecialchars($prop['area_value'] . ' ' . $prop['area_unit']); ?></span>
                <span class="spec-label"><?php echo htmlspecialchars($prop['covered_area'] ?? 'Area'); ?></span>
              </div>
            </div>

            <!-- Card Actions -->
            <div class="property-footer">
              <a href="property-details.php?id=<?php echo urlencode($prop['id']); ?>" class="btn btn-burgundy btn-sm" style="flex-grow:1;">
                View Details
              </a>
              <!-- WhatsApp Contact Agent -->
              <a href="https://wa.me/<?php echo AGENT_WHATSAPP; ?>?text=Assalam-o-Alaikum,%20I%20am%20inquiring%20about%20<?php echo urlencode($prop['title'] . ' (ID: ' . $prop['id'] . ') in ' . $prop['city']); ?>" 
                 target="_blank" 
                 rel="noopener noreferrer"
                 class="btn-whatsapp-icon" 
                 title="Inquire on WhatsApp">
                <svg viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.16 12.04 20.16C10.66 20.16 9.3 19.8 8.1 19.09L7.81 18.92L4.69 19.74L5.52 16.7L5.33 16.4C4.55 15.15 4.13 13.55 4.13 11.92C4.13 7.37 7.84 3.67 12.05 3.67M9.27 7.55C9.07 7.55 8.74 7.62 8.46 7.92C8.18 8.23 7.39 8.97 7.39 10.45C7.39 11.93 8.47 13.36 8.62 13.56C8.77 13.76 10.74 16.8 13.76 18.1C14.48 18.41 15.04 18.6 15.48 18.74C16.2 18.97 16.85 18.94 17.37 18.86C17.95 18.77 19.16 18.12 19.41 17.41C19.67 16.7 19.67 16.09 19.59 15.96C19.51 15.83 19.31 15.76 19.01 15.61C18.71 15.46 17.23 14.73 16.96 14.63C16.68 14.53 16.48 14.48 16.28 14.78C16.08 15.09 15.5 15.76 15.33 15.96C15.15 16.16 14.98 16.18 14.68 16.03C14.38 15.88 13.41 15.56 12.27 14.54C11.38 13.75 10.78 12.77 10.63 12.52C10.48 12.26 10.61 12.13 10.76 11.98C10.9 11.85 11.06 11.63 11.21 11.46C11.36 11.28 11.41 11.16 11.51 10.96C11.61 10.76 11.56 10.58 11.49 10.43C11.41 10.28 10.84 8.88 10.6 8.3C10.37 7.74 10.13 7.82 9.96 7.81C9.8 7.8 9.61 7.8 9.42 7.8L9.27 7.55Z"/></svg>
              </a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>

      <!-- Dynamic Empty Results Message -->
      <div id="emptyResultsMsg" class="empty-results" style="display: none;">
        <h4>No matching properties found</h4>
        <p style="color:var(--c-grey-medium);margin-bottom:15px;">Try adjusting your search criteria, removing price filters, or searching across all cities.</p>
        <button type="button" onclick="resetFilters()" class="btn btn-burgundy btn-sm">Reset All Filters</button>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     ABOUT US SECTION
     ========================================================================== -->
<section class="section-padding about-section" id="aboutSection">
  <div class="container">
    <div class="about-grid">
      <!-- About Image with Experience Badge -->
      <div class="about-img-wrap">
        <img src="assets/images/about-agent.jpg" alt="Abdullah Property Dealer Agency" loading="lazy">
        <div class="about-img-badge">
          <div class="num">15+</div>
          <div class="lbl">Years in Pakistan Real Estate</div>
        </div>
      </div>

      <!-- About Description -->
      <div class="about-content">
        <span class="section-tag">About Agency</span>
        <h2>Your Trusted Real Estate Partner</h2>
        <p class="about-desc">
          Abdullah Property Dealer helps customers explore residential and commercial property opportunities across Pakistan. Our goal is to make property searching simple, convenient and transparent.
        </p>

        <!-- Highlights Grid -->
        <div class="about-features">
          <div class="about-feat-item">
            <div class="about-feat-icon">✓</div>
            <div class="about-feat-text">
              <h5>Verified Titles & Approvals</h5>
              <p>LDA, CDA, TMA and DHA clear title documentation support.</p>
            </div>
          </div>

          <div class="about-feat-item">
            <div class="about-feat-icon">✓</div>
            <div class="about-feat-text">
              <h5>Transparent Valuation</h5>
              <p>Fair, market-calibrated pricing in PKR with zero hidden surcharges.</p>
            </div>
          </div>

          <div class="about-feat-item">
            <div class="about-feat-icon">✓</div>
            <div class="about-feat-text">
              <h5>Nationwide Presence</h5>
              <p>Active networks in Lahore, Islamabad, Karachi, Rawalpindi & Kasur.</p>
            </div>
          </div>

          <div class="about-feat-item">
            <div class="about-feat-icon">✓</div>
            <div class="about-feat-text">
              <h5>Dedicated Agent Support</h5>
              <p>One-on-one consultation, site visits, and legal file transfer assistance.</p>
            </div>
          </div>
        </div>

        <div style="display:flex;gap:15px;align-items:center;">
          <a href="#contactSection" class="btn btn-burgundy">Consult an Agent</a>
          <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', AGENT_PHONE); ?>" class="btn btn-outline">Call <?php echo htmlspecialchars(AGENT_PHONE); ?></a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     CONTACT US SECTION
     ========================================================================== -->
<section class="section-padding contact-section" id="contactSection">
  <div class="container">
    <div class="section-head">
      <span class="section-tag">Get In Touch</span>
      <h2 class="section-title">Contact Abdullah Property Dealer</h2>
      <p class="section-subtitle">
        Have questions about buying, renting, or selling property in Pakistan? Send us an inquiry or reach out via WhatsApp.
      </p>
    </div>

    <div class="contact-grid">
      <!-- Contact Form -->
      <div class="contact-card-box">
        <h3 class="contact-box-title">Submit a Property Inquiry</h3>
        <p class="contact-box-sub">Our representative will get in touch with you within 24 business hours.</p>

        <form id="contactForm" method="POST" action="api/contact.php">
          <input type="hidden" name="property_id" value="General Inquiry">
          <input type="hidden" name="property_title" value="General Agency Contact">

          <!-- 1. Name -->
          <div class="form-group">
            <label class="form-label" for="contactName">Name</label>
            <input type="text" id="contactName" name="name" class="form-control" placeholder="Enter your name" required>
          </div>

          <!-- 2. Address -->
          <div class="form-group">
            <label class="form-label" for="contactAddress">Address</label>
            <input type="text" id="contactAddress" name="address" class="form-control" placeholder="Your address" required>
          </div>

          <!-- 3. Phone Number with Pakistan Country Code +92 -->
          <div class="form-group">
            <label class="form-label" for="contactPhone">Phone Number</label>
            <div class="phone-input-group">
              <label for="contactPhone" class="phone-prefix">+92</label>
              <input type="tel" id="contactPhone" name="phone" class="form-control" required>
            </div>
          </div>

          <!-- 4. Message -->
          <div class="form-group">
            <label class="form-label" for="contactMessage">Message</label>
            <textarea id="contactMessage" name="message" class="form-control" rows="4" placeholder="Write your message here" required></textarea>
          </div>

          <!-- Submit Inquiry Button -->
          <button type="submit" class="btn btn-burgundy btn-block" style="padding:14px;display:flex;align-items:center;justify-content:center;gap:8px;">
            <svg style="width:18px;height:18px;fill:currentColor" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.16 12.04 20.16C10.66 20.16 9.3 19.8 8.1 19.09L7.81 18.92L4.69 19.74L5.52 16.7L5.33 16.4C4.55 15.15 4.13 13.55 4.13 11.92C4.13 7.37 7.84 3.67 12.05 3.67M9.27 7.55C9.07 7.55 8.74 7.62 8.46 7.92C8.18 8.23 7.39 8.97 7.39 10.45C7.39 11.93 8.47 13.36 8.62 13.56C8.77 13.76 10.74 16.8 13.76 18.1C14.48 18.41 15.04 18.6 15.48 18.74C16.2 18.97 16.85 18.94 17.37 18.86C17.95 18.77 19.16 18.12 19.41 17.41C19.67 16.7 19.67 16.09 19.59 15.96C19.51 15.83 19.31 15.76 19.01 15.61C18.71 15.46 17.23 14.73 16.96 14.63C16.68 14.53 16.48 14.48 16.28 14.78C16.08 15.09 15.5 15.76 15.33 15.96C15.15 16.16 14.98 16.18 14.68 16.03C14.38 15.88 13.41 15.56 12.27 14.54C11.38 13.75 10.78 12.77 10.63 12.52C10.48 12.26 10.61 12.13 10.76 11.98C10.9 11.85 11.06 11.63 11.21 11.46C11.36 11.28 11.41 11.16 11.51 10.96C11.61 10.76 11.56 10.58 11.49 10.43C11.41 10.28 10.84 8.88 10.6 8.3C10.37 7.74 10.13 7.82 9.96 7.81C9.8 7.8 9.61 7.8 9.42 7.8L9.27 7.55Z"/></svg>
            <span>Submit Inquiry & Send to WhatsApp</span>
          </button>
        </form>
      </div>

      <!-- Agency Contact Sidebar -->
      <div class="contact-sidebar">
        <!-- Direct Agency Details -->
        <div class="info-card-panel">
          <h3>Agency Head Office</h3>
          <p>
            Visit our corporate office for personalized portfolio reviews, transfer assistance, and legal documentation consultation.
          </p>

          <div class="contact-detail-list">
            <div class="contact-detail-row">
              <div class="contact-detail-icon">
                <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
              </div>
              <div>
                <strong style="display:block;color:var(--c-cream);">Location:</strong>
                <span><?php echo htmlspecialchars(AGENT_ADDRESS); ?></span>
              </div>
            </div>

            <div class="contact-detail-row">
              <div class="contact-detail-icon">
                <svg viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
              </div>
              <div>
                <strong style="display:block;color:var(--c-cream);">Direct Phone:</strong>
                <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', AGENT_PHONE); ?>" style="color:var(--c-terracotta-light);"><?php echo htmlspecialchars(AGENT_PHONE); ?></a>
              </div>
            </div>

            <div class="contact-detail-row">
              <div class="contact-detail-icon">
                <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
              </div>
              <div>
                <strong style="display:block;color:var(--c-cream);">Email Address:</strong>
                <a href="mailto:<?php echo htmlspecialchars(AGENT_EMAIL); ?>" style="color:var(--c-cream);"><?php echo htmlspecialchars(AGENT_EMAIL); ?></a>
              </div>
            </div>
          </div>
        </div>

        <!-- WhatsApp Quick Connect (Configurable Phone Number, strictly no green) -->
        <div class="whatsapp-action-card">
          <div class="text">
            <h4>Quick WhatsApp Connect</h4>
            <p>Configurable agent number: <?php echo htmlspecialchars(AGENT_PHONE); ?></p>
          </div>
          <a href="https://wa.me/<?php echo AGENT_WHATSAPP; ?>?text=Assalam-o-Alaikum%20Abdullah%20Property%20Dealer,%20I%20would%20like%20to%20speak%20with%20an%20agent." 
             target="_blank" 
             rel="noopener noreferrer"
             class="btn btn-terracotta btn-sm"
             style="white-space:nowrap;">
            Chat on WhatsApp
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>


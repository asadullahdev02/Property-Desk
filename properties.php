<?php
/**
 * Abdullah Property Dealer - Properties Directory Page
 */
require_once __DIR__ . '/includes/config.php';

$purpose_filter = $_GET['purpose'] ?? 'all';
$type_filter = $_GET['type'] ?? 'all';
$city_filter = $_GET['city'] ?? 'all';

$current_page = 'buy';
if ($purpose_filter === 'rent') {
    $current_page = 'rent';
} elseif (stripos($type_filter, 'commercial') !== false || stripos($type_filter, 'shop') !== false) {
    $current_page = 'commercial';
}

$page_title = 'Explore Properties | Abdullah Property Dealer Pakistan';
$page_desc = 'Search and filter prime residential and commercial real estate across Pakistan. Houses, luxury villas, apartments, plots, and retail shops.';

$properties = get_properties();
$cities = ['Lahore', 'Islamabad', 'Karachi', 'Rawalpindi', 'Faisalabad', 'Kasur'];

include __DIR__ . '/includes/header.php';
?>

<!-- Breadcrumbs -->
<div class="breadcrumb-bar">
  <div class="container">
    <div class="breadcrumb-list">
      <a href="index.php">Home</a>
      <span class="breadcrumb-separator">/</span>
      <span>Property Listings</span>
      <?php if ($purpose_filter !== 'all'): ?>
        <span class="breadcrumb-separator">/</span>
        <span style="color:var(--c-burgundy);text-transform:capitalize;"><?php echo ($purpose_filter === 'sale') ? 'For Sale' : 'For Rent'; ?></span>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Header Banner -->
<section style="background:var(--c-cream-soft);padding:45px 0 30px 0;border-bottom:1px solid var(--c-grey-border);">
  <div class="container">
    <div class="section-head" style="margin-bottom:0;text-align:left;">
      <span class="section-tag">Property Directory</span>
      <h1 class="section-title" style="margin-bottom:8px;">
        <?php 
          if ($purpose_filter === 'sale') echo 'Properties For Sale in Pakistan';
          elseif ($purpose_filter === 'rent') echo 'Properties For Rent in Pakistan';
          elseif ($type_filter !== 'all') echo htmlspecialchars($type_filter) . ' in Pakistan';
          else echo 'All Property Listings in Pakistan';
        ?>
      </h1>
      <p class="section-subtitle">
        Verified residential plots, luxury villas, modern houses, corporate plazas, and prime shops with verified title deeds.
      </p>
    </div>
  </div>
</section>

<!-- Filter Toolbar & Property Grid -->
<section class="section-padding" style="padding-top:40px;">
  <div class="container">
    
    <!-- Working JavaScript Filter Toolbar -->
    <div class="filter-toolbar">
      <!-- Search & Sort Row -->
      <div class="filter-search-row">
        <div class="filter-search-wrap">
          <svg class="search-svg-icon" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
          <input type="text" id="filterSearch" class="filter-search-input" placeholder="Search by location, keyword, DHA, Bahria, Gulberg, ID...">
          <button type="button" id="clearSearchBtn" class="clear-search-btn" title="Clear text" style="display:none;">&times;</button>
        </div>

        <div class="filter-quick-actions">
          <!-- Sort Dropdown -->
          <select id="filterSort" class="filter-select" aria-label="Sort Properties">
            <option value="default">Sort: Recommended</option>
            <option value="price-asc">Price: Low to High</option>
            <option value="price-desc">Price: High to Low</option>
            <option value="beds-desc">Bedrooms: Most First</option>
            <option value="area-desc">Size: Largest First</option>
          </select>

          <!-- Favorites Filter Toggle -->
          <button type="button" id="filterFavoritesBtn" class="filter-pill-btn" title="Toggle Saved Properties">
            <svg viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
            <span>Saved (<span class="fav-count-badge">0</span>)</span>
          </button>
        </div>
      </div>

      <div class="filter-row">
        <div class="filter-left">
          <!-- Purpose Filter -->
          <select id="filterPurpose" class="filter-select" aria-label="Filter by Purpose">
            <option value="all" <?php echo ($purpose_filter === 'all') ? 'selected' : ''; ?>>Purpose: All</option>
            <option value="sale" <?php echo ($purpose_filter === 'sale') ? 'selected' : ''; ?>>For Sale</option>
            <option value="rent" <?php echo ($purpose_filter === 'rent') ? 'selected' : ''; ?>>For Rent</option>
          </select>

          <!-- City Filter -->
          <select id="filterCity" class="filter-select" aria-label="Filter by City">
            <option value="all" <?php echo ($city_filter === 'all') ? 'selected' : ''; ?>>City: All Locations</option>
            <?php foreach ($cities as $city): ?>
              <option value="<?php echo htmlspecialchars($city); ?>" <?php echo ($city_filter === $city) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($city); ?>
              </option>
            <?php endforeach; ?>
          </select>

          <!-- Type Filter -->
          <select id="filterType" class="filter-select" aria-label="Filter by Property Type">
            <option value="all" <?php echo ($type_filter === 'all') ? 'selected' : ''; ?>>Type: All Categories</option>
            <option value="Houses" <?php echo ($type_filter === 'Houses') ? 'selected' : ''; ?>>Houses</option>
            <option value="Apartments" <?php echo ($type_filter === 'Apartments') ? 'selected' : ''; ?>>Apartments</option>
            <option value="Residential Plots" <?php echo ($type_filter === 'Residential Plots') ? 'selected' : ''; ?>>Residential Plots</option>
            <option value="Commercial Properties" <?php echo ($type_filter === 'Commercial Properties') ? 'selected' : ''; ?>>Commercial Properties</option>
            <option value="Shops" <?php echo ($type_filter === 'Shops') ? 'selected' : ''; ?>>Shops</option>
            <option value="Luxury Villas" <?php echo ($type_filter === 'Luxury Villas') ? 'selected' : ''; ?>>Luxury Villas</option>
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

          <!-- Price inputs -->
          <input type="number" id="filterMinPrice" class="filter-select" placeholder="Min PKR" style="width: 120px;">
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

          <!-- Dynamic Counter -->
          <span class="filter-counter" id="resultsCounter">Showing <?php echo count($properties); ?> properties</span>
        </div>
      </div>
    </div>

    <!-- Properties Grid -->
    <div class="property-grid" id="propertiesGrid">
      <?php foreach ($properties as $prop): ?>
        <article class="property-card" 
                 data-id="<?php echo htmlspecialchars($prop['id']); ?>"
                 data-purpose="<?php echo htmlspecialchars($prop['purpose']); ?>"
                 data-city="<?php echo htmlspecialchars($prop['city']); ?>"
                 data-category="<?php echo htmlspecialchars($prop['category']); ?>"
                 data-price="<?php echo (float)$prop['price']; ?>"
                 data-bedrooms="<?php echo (int)$prop['bedrooms']; ?>"
                 data-area="<?php echo (float)($prop['area_value'] ?? 0); ?>"
                 data-featured="<?php echo !empty($prop['featured']) ? '1' : '0'; ?>"
                 data-title="<?php echo htmlspecialchars(strtolower($prop['title'])); ?>"
                 data-location="<?php echo htmlspecialchars(strtolower($prop['location'])); ?>"
                 data-status="<?php echo htmlspecialchars($prop['status']); ?>">
          
          <!-- Thumbnail & Badges -->
          <div class="property-thumb-wrap">
            <img src="<?php echo htmlspecialchars($prop['image']); ?>" alt="<?php echo htmlspecialchars($prop['title']); ?>" loading="lazy">
            
            <button type="button" class="btn-fav-card" data-prop-id="<?php echo htmlspecialchars($prop['id']); ?>" aria-label="Save to Favorites" title="Save to Favorites">
              <svg class="heart-icon" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
            </button>
            
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

      <!-- Dynamic Empty State -->
      <div id="emptyResultsMsg" class="empty-results" style="display: none;">
        <h4>No matching properties found</h4>
        <p style="color:var(--c-grey-medium);margin-bottom:15px;">Try adjusting your filter options or removing price constraints.</p>
        <button type="button" onclick="resetFilters()" class="btn btn-burgundy btn-sm">Reset All Filters</button>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>


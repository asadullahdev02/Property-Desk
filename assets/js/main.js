/**
 * Abdullah Property Dealer - Main Client Script
 * Dynamic Filtering, Real Estate Interactions, Form Submissions
 */

document.addEventListener('DOMContentLoaded', () => {
  initMobileNav();
  initHeroSearch();
  initPropertyFiltering();
  initGalleryViewer();
  initContactForm();
  initCategoryCards();
});

/**
 * Mobile Navigation Drawer
 */
function initMobileNav() {
  const toggleBtn = document.getElementById('mobileMenuToggle');
  const navMenu = document.getElementById('navMenu');

  if (toggleBtn && navMenu) {
    toggleBtn.addEventListener('click', () => {
      navMenu.classList.toggle('active');
    });

    // Close when clicking outside
    document.addEventListener('click', (e) => {
      if (!navMenu.contains(e.target) && !toggleBtn.contains(e.target) && navMenu.classList.contains('active')) {
        navMenu.classList.remove('active');
      }
    });
  }
}

/**
 * Hero Search Bar Interaction
 */
function initHeroSearch() {
  const tabs = document.querySelectorAll('.purpose-tab-btn');
  const heroForm = document.getElementById('heroSearchForm');
  let currentPurpose = 'buy';

  tabs.forEach(tab => {
    tab.addEventListener('click', (e) => {
      e.preventDefault();
      tabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      currentPurpose = tab.getAttribute('data-purpose');
    });
  });

  if (heroForm) {
    heroForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const city = document.getElementById('heroCity')?.value || 'all';
      const type = document.getElementById('heroType')?.value || 'all';
      const minPrice = document.getElementById('heroMinPrice')?.value || '';
      const maxPrice = document.getElementById('heroMaxPrice')?.value || '';

      // Check if we have the property grid on current page
      const propertyGrid = document.getElementById('propertiesGrid');
      if (propertyGrid) {
        // Sync with filter toolbar on page
        const purposeSelect = document.getElementById('filterPurpose');
        const citySelect = document.getElementById('filterCity');
        const typeSelect = document.getElementById('filterType');
        const minPriceInput = document.getElementById('filterMinPrice');
        const maxPriceInput = document.getElementById('filterMaxPrice');

        if (purposeSelect) purposeSelect.value = (currentPurpose === 'buy') ? 'sale' : 'rent';
        if (citySelect) citySelect.value = city;
        if (typeSelect) typeSelect.value = type;
        if (minPriceInput) minPriceInput.value = minPrice;
        if (maxPriceInput) maxPriceInput.value = maxPrice;

        // Trigger filter
        applyPropertyFilters();

        // Smooth scroll to listings
        const listingsSec = document.getElementById('featuredListings');
        if (listingsSec) {
          listingsSec.scrollIntoView({ behavior: 'smooth' });
        }
      } else {
        // Navigate to properties.php with query params
        const params = new URLSearchParams();
        if (currentPurpose) params.set('purpose', currentPurpose === 'buy' ? 'sale' : 'rent');
        if (city && city !== 'all') params.set('city', city);
        if (type && type !== 'all') params.set('type', type);
        if (minPrice) params.set('min_price', minPrice);
        if (maxPrice) params.set('max_price', maxPrice);
        window.location.href = 'properties.php?' + params.toString();
      }
    });
  }
}

/**
 * Category Cards Filter Trigger
 */
function initCategoryCards() {
  const categoryCards = document.querySelectorAll('.category-card');
  categoryCards.forEach(card => {
    card.addEventListener('click', (e) => {
      const cat = card.getAttribute('data-category');
      if (!cat) return;

      const typeSelect = document.getElementById('filterType');
      const listingsSec = document.getElementById('featuredListings') || document.getElementById('propertiesGrid');

      if (typeSelect && listingsSec) {
        e.preventDefault();
        typeSelect.value = cat;
        applyPropertyFilters();
        listingsSec.scrollIntoView({ behavior: 'smooth' });
      }
    });
  });
}

/**
 * Dynamic Property Filtering Engine
 */
function initPropertyFiltering() {
  const filterElements = [
    'filterPurpose',
    'filterCity',
    'filterType',
    'filterBedrooms',
    'filterMinPrice',
    'filterMaxPrice',
    'filterAvailableOnly'
  ];

  filterElements.forEach(id => {
    const el = document.getElementById(id);
    if (el) {
      el.addEventListener('change', applyPropertyFilters);
      if (el.tagName === 'INPUT' && el.type === 'number') {
        el.addEventListener('input', applyPropertyFilters);
      }
    }
  });

  const clearBtn = document.getElementById('clearFiltersBtn');
  if (clearBtn) {
    clearBtn.addEventListener('click', (e) => {
      e.preventDefault();
      resetFilters();
    });
  }

  // Parse URL query parameters if present on load
  const urlParams = new URLSearchParams(window.location.search);
  let hasUrlParams = false;

  if (urlParams.has('purpose') && document.getElementById('filterPurpose')) {
    document.getElementById('filterPurpose').value = urlParams.get('purpose');
    hasUrlParams = true;
  }
  if (urlParams.has('city') && document.getElementById('filterCity')) {
    document.getElementById('filterCity').value = urlParams.get('city');
    hasUrlParams = true;
  }
  if (urlParams.has('type') && document.getElementById('filterType')) {
    document.getElementById('filterType').value = urlParams.get('type');
    hasUrlParams = true;
  }
  if (urlParams.has('min_price') && document.getElementById('filterMinPrice')) {
    document.getElementById('filterMinPrice').value = urlParams.get('min_price');
    hasUrlParams = true;
  }
  if (urlParams.has('max_price') && document.getElementById('filterMaxPrice')) {
    document.getElementById('filterMaxPrice').value = urlParams.get('max_price');
    hasUrlParams = true;
  }

  if (hasUrlParams) {
    applyPropertyFilters();
  }
}

function applyPropertyFilters() {
  const cards = document.querySelectorAll('.property-card');
  if (!cards.length) return;

  const purpose = document.getElementById('filterPurpose')?.value || 'all';
  const city = document.getElementById('filterCity')?.value || 'all';
  const type = document.getElementById('filterType')?.value || 'all';
  const bedrooms = document.getElementById('filterBedrooms')?.value || 'all';
  const minPrice = parseFloat(document.getElementById('filterMinPrice')?.value) || 0;
  const maxPrice = parseFloat(document.getElementById('filterMaxPrice')?.value) || Infinity;
  const availableOnly = document.getElementById('filterAvailableOnly')?.checked || false;

  let visibleCount = 0;

  cards.forEach(card => {
    const cardPurpose = card.getAttribute('data-purpose') || '';
    const cardCity = card.getAttribute('data-city') || '';
    const cardType = card.getAttribute('data-category') || '';
    const cardPrice = parseFloat(card.getAttribute('data-price')) || 0;
    const cardBeds = parseInt(card.getAttribute('data-bedrooms')) || 0;
    const cardStatus = (card.getAttribute('data-status') || '').toLowerCase();

    // Matching conditions
    const matchPurpose = (purpose === 'all' || cardPurpose.toLowerCase() === purpose.toLowerCase());
    const matchCity = (city === 'all' || cardCity.toLowerCase() === city.toLowerCase());
    const matchType = (type === 'all' || cardType.toLowerCase() === type.toLowerCase());
    const matchMin = cardPrice >= minPrice;
    const matchMax = cardPrice <= maxPrice;

    let matchBeds = true;
    if (bedrooms !== 'all') {
      if (bedrooms === '5+') {
        matchBeds = cardBeds >= 5;
      } else {
        matchBeds = cardBeds === parseInt(bedrooms);
      }
    }

    let matchStatus = true;
    if (availableOnly) {
      matchStatus = (cardStatus === 'available');
    }

    if (matchPurpose && matchCity && matchType && matchMin && matchMax && matchBeds && matchStatus) {
      card.style.display = 'flex';
      visibleCount++;
    } else {
      card.style.display = 'none';
    }
  });

  // Update dynamic counter
  const counterEl = document.getElementById('resultsCounter');
  if (counterEl) {
    counterEl.textContent = `Showing ${visibleCount} of ${cards.length} properties`;
  }

  // Handle empty state message
  const emptyState = document.getElementById('emptyResultsMsg');
  if (emptyState) {
    emptyState.style.display = (visibleCount === 0) ? 'block' : 'none';
  }
}

function resetFilters() {
  const purposeSelect = document.getElementById('filterPurpose');
  const citySelect = document.getElementById('filterCity');
  const typeSelect = document.getElementById('filterType');
  const bedroomsSelect = document.getElementById('filterBedrooms');
  const minPriceInput = document.getElementById('filterMinPrice');
  const maxPriceInput = document.getElementById('filterMaxPrice');
  const availableCheck = document.getElementById('filterAvailableOnly');

  if (purposeSelect) purposeSelect.value = 'all';
  if (citySelect) citySelect.value = 'all';
  if (typeSelect) typeSelect.value = 'all';
  if (bedroomsSelect) bedroomsSelect.value = 'all';
  if (minPriceInput) minPriceInput.value = '';
  if (maxPriceInput) maxPriceInput.value = '';
  if (availableCheck) availableCheck.checked = false;

  applyPropertyFilters();
}

/**
 * Property Details Gallery Viewer
 */
function initGalleryViewer() {
  const mainImg = document.getElementById('mainPropertyImage');
  const thumbs = document.querySelectorAll('.thumb-item');

  if (mainImg && thumbs.length) {
    thumbs.forEach(thumb => {
      thumb.addEventListener('click', () => {
        thumbs.forEach(t => t.classList.remove('active'));
        thumb.classList.add('active');
        const newSrc = thumb.getAttribute('data-full-img');
        if (newSrc) {
          mainImg.style.opacity = '0.4';
          setTimeout(() => {
            mainImg.src = newSrc;
            mainImg.style.opacity = '1';
          }, 150);
        }
      });
    });
  }
}

/**
 * Contact & Inquiry AJAX Form
 */
function initContactForm() {
  const contactForm = document.getElementById('contactForm');
  const propertyInquiryForm = document.getElementById('propertyInquiryForm');

  [contactForm, propertyInquiryForm].forEach(form => {
    if (!form) return;

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const submitBtn = form.querySelector('button[type="submit"]');
      const originalBtnText = submitBtn ? submitBtn.innerHTML : 'Submit';
      const alertContainer = form.querySelector('.form-alert-msg') || createAlertContainer(form);

      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Submitting Inquiry...';
      }

      try {
        const formData = new FormData(form);
        const response = await fetch('api/contact.php', {
          method: 'POST',
          headers: {
            'Accept': 'application/json'
          },
          body: formData
        });

        const result = await response.json();

        if (result.success) {
          alertContainer.className = 'alert alert-success';
          let alertHtml = `<strong>Inquiry Submitted!</strong> ${result.message}`;
          
          if (result.whatsapp_url) {
            alertHtml += `
              <div style="margin-top:12px;">
                <a href="${result.whatsapp_url}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-terracotta" style="display:inline-flex;align-items:center;gap:8px;font-weight:700;">
                  <svg style="width:16px;height:16px;fill:currentColor" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.16 12.04 20.16C10.66 20.16 9.3 19.8 8.1 19.09L7.81 18.92L4.69 19.74L5.52 16.7L5.33 16.4C4.55 15.15 4.13 13.55 4.13 11.92C4.13 7.37 7.84 3.67 12.05 3.67M9.27 7.55C9.07 7.55 8.74 7.62 8.46 7.92C8.18 8.23 7.39 8.97 7.39 10.45C7.39 11.93 8.47 13.36 8.62 13.56C8.77 13.76 10.74 16.8 13.76 18.1C14.48 18.41 15.04 18.6 15.48 18.74C16.2 18.97 16.85 18.94 17.37 18.86C17.95 18.77 19.16 18.12 19.41 17.41C19.67 16.7 19.67 16.09 19.59 15.96C19.51 15.83 19.31 15.76 19.01 15.61C18.71 15.46 17.23 14.73 16.96 14.63C16.68 14.53 16.48 14.48 16.28 14.78C16.08 15.09 15.5 15.76 15.33 15.96C15.15 16.16 14.98 16.18 14.68 16.03C14.38 15.88 13.41 15.56 12.27 14.54C11.38 13.75 10.78 12.77 10.63 12.52C10.48 12.26 10.61 12.13 10.76 11.98C10.9 11.85 11.06 11.63 11.21 11.46C11.36 11.28 11.41 11.16 11.51 10.96C11.61 10.76 11.56 10.58 11.49 10.43C11.41 10.28 10.84 8.88 10.6 8.3C10.37 7.74 10.13 7.82 9.96 7.81C9.8 7.8 9.61 7.8 9.42 7.8L9.27 7.55Z"/></svg>
                  <span>Open & Send via WhatsApp Now</span>
                </a>
              </div>`;
          }

          alertContainer.innerHTML = alertHtml;
          alertContainer.style.display = 'block';
          form.reset();

          // Scroll alert into view
          alertContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

          // Forward directly to WhatsApp
          if (result.whatsapp_url) {
            const waTab = window.open(result.whatsapp_url, '_blank');
            // If popup is blocked by the browser, fallback to navigating directly
            if (!waTab || waTab.closed || typeof waTab.closed === 'undefined') {
              setTimeout(() => {
                window.location.href = result.whatsapp_url;
              }, 600);
            }
          }
        } else {
          alertContainer.className = 'alert alert-error';
          alertContainer.innerHTML = `<strong>Notice:</strong> ${result.message || 'Please check your information and try again.'}`;
          alertContainer.style.display = 'block';
        }
      } catch (err) {
        // Fallback: submit standard form if AJAX fails
        form.submit();
      } finally {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalBtnText;
        }
      }
    });
  });
}

function createAlertContainer(form) {
  const div = document.createElement('div');
  div.className = 'form-alert-msg';
  div.style.display = 'none';
  form.prepend(div);
  return div;
}


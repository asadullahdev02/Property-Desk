/**
 * Abdullah Property Dealer - Main Client Script
 * Dynamic Filtering, Search & Sorting, Favorites System, PKR Mortgage Calculator, Interactive Forms
 */

document.addEventListener('DOMContentLoaded', () => {
  initMobileNav();
  initHeroSearch();
  initPropertyFiltering();
  initGalleryViewer();
  initContactForm();
  initCategoryCards();
  initFavoritesSystem();
  initMortgageCalculator();
  initPropertyDetailActions();
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
      const keyword = document.getElementById('heroKeyword')?.value.trim() || '';

      // Check if we have the property grid on current page
      const propertyGrid = document.getElementById('propertiesGrid');
      if (propertyGrid) {
        // Sync with filter toolbar on page
        const purposeSelect = document.getElementById('filterPurpose');
        const citySelect = document.getElementById('filterCity');
        const typeSelect = document.getElementById('filterType');
        const minPriceInput = document.getElementById('filterMinPrice');
        const maxPriceInput = document.getElementById('filterMaxPrice');
        const searchInput = document.getElementById('filterSearch');

        if (purposeSelect) purposeSelect.value = (currentPurpose === 'buy') ? 'sale' : 'rent';
        if (citySelect) citySelect.value = city;
        if (typeSelect) typeSelect.value = type;
        if (minPriceInput) minPriceInput.value = minPrice;
        if (maxPriceInput) maxPriceInput.value = maxPrice;
        if (searchInput && keyword) searchInput.value = keyword;

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
        if (keyword) params.set('search', keyword);
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
 * Dynamic Property Filtering & Search & Sorting Engine
 */
let filterFavoritesOnly = false;

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

  // Search input with instant feedback
  const searchInput = document.getElementById('filterSearch');
  const clearSearchBtn = document.getElementById('clearSearchBtn');

  if (searchInput) {
    searchInput.addEventListener('input', () => {
      if (clearSearchBtn) {
        clearSearchBtn.style.display = searchInput.value ? 'inline-block' : 'none';
      }
      applyPropertyFilters();
    });
  }

  if (clearSearchBtn) {
    clearSearchBtn.addEventListener('click', (e) => {
      e.preventDefault();
      if (searchInput) {
        searchInput.value = '';
        clearSearchBtn.style.display = 'none';
        applyPropertyFilters();
        searchInput.focus();
      }
    });
  }

  // Sort dropdown
  const sortSelect = document.getElementById('filterSort');
  if (sortSelect) {
    sortSelect.addEventListener('change', () => {
      applyPropertySorting();
      applyPropertyFilters();
    });
  }

  // Favorites filter toggle pill
  const favToggleBtn = document.getElementById('filterFavoritesBtn');
  if (favToggleBtn) {
    favToggleBtn.addEventListener('click', (e) => {
      e.preventDefault();
      filterFavoritesOnly = !filterFavoritesOnly;
      favToggleBtn.classList.toggle('active', filterFavoritesOnly);
      applyPropertyFilters();
    });
  }

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
  if (urlParams.has('search') && document.getElementById('filterSearch')) {
    document.getElementById('filterSearch').value = urlParams.get('search');
    if (clearSearchBtn) clearSearchBtn.style.display = 'inline-block';
    hasUrlParams = true;
  }
  if (urlParams.has('favorites') || urlParams.has('saved')) {
    filterFavoritesOnly = true;
    if (favToggleBtn) favToggleBtn.classList.add('active');
    hasUrlParams = true;
  }
  if (urlParams.has('sort') && sortSelect) {
    sortSelect.value = urlParams.get('sort');
    applyPropertySorting();
    hasUrlParams = true;
  }

  if (hasUrlParams) {
    applyPropertyFilters();
  }
}

function applyPropertySorting() {
  const grid = document.getElementById('propertiesGrid');
  if (!grid) return;

  const sortValue = document.getElementById('filterSort')?.value || 'default';
  const cards = Array.from(grid.querySelectorAll('.property-card'));
  if (!cards.length) return;

  cards.sort((a, b) => {
    const priceA = parseFloat(a.getAttribute('data-price')) || 0;
    const priceB = parseFloat(b.getAttribute('data-price')) || 0;
    const bedsA = parseInt(a.getAttribute('data-bedrooms')) || 0;
    const bedsB = parseInt(b.getAttribute('data-bedrooms')) || 0;
    const areaA = parseFloat(a.getAttribute('data-area')) || 0;
    const areaB = parseFloat(b.getAttribute('data-area')) || 0;
    const featuredA = parseInt(a.getAttribute('data-featured')) || 0;
    const featuredB = parseInt(b.getAttribute('data-featured')) || 0;

    switch (sortValue) {
      case 'price-asc':
        return priceA - priceB;
      case 'price-desc':
        return priceB - priceA;
      case 'beds-desc':
        return bedsB - bedsA;
      case 'area-desc':
        return areaB - areaA;
      case 'default':
      default:
        // Featured listings first
        if (featuredB !== featuredA) {
          return featuredB - featuredA;
        }
        return (a.getAttribute('data-id') || '').localeCompare(b.getAttribute('data-id') || '');
    }
  });

  const emptyMsg = document.getElementById('emptyResultsMsg');
  cards.forEach(card => grid.appendChild(card));
  if (emptyMsg) grid.appendChild(emptyMsg);
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
  const searchQuery = document.getElementById('filterSearch')?.value.trim().toLowerCase() || '';

  const favs = getFavorites();
  let visibleCount = 0;

  cards.forEach(card => {
    const cardId = card.getAttribute('data-id') || '';
    const cardPurpose = card.getAttribute('data-purpose') || '';
    const cardCity = card.getAttribute('data-city') || '';
    const cardType = card.getAttribute('data-category') || '';
    const cardPrice = parseFloat(card.getAttribute('data-price')) || 0;
    const cardBeds = parseInt(card.getAttribute('data-bedrooms')) || 0;
    const cardStatus = (card.getAttribute('data-status') || '').toLowerCase();
    const cardTitle = card.getAttribute('data-title') || '';
    const cardLocation = card.getAttribute('data-location') || '';

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

    let matchFav = true;
    if (filterFavoritesOnly) {
      matchFav = favs.includes(cardId);
    }

    let matchSearch = true;
    if (searchQuery) {
      const haystack = (cardTitle + ' ' + cardLocation + ' ' + cardCity + ' ' + cardType + ' ' + cardId).toLowerCase();
      matchSearch = haystack.includes(searchQuery);
    }

    if (matchPurpose && matchCity && matchType && matchMin && matchMax && matchBeds && matchStatus && matchFav && matchSearch) {
      card.style.display = 'flex';
      visibleCount++;
    } else {
      card.style.display = 'none';
    }
  });

  // Update dynamic counter
  const counterEl = document.getElementById('resultsCounter');
  if (counterEl) {
    if (filterFavoritesOnly) {
      counterEl.textContent = `Showing ${visibleCount} saved property (${favs.length} total saved)`;
    } else {
      counterEl.textContent = `Showing ${visibleCount} of ${cards.length} properties`;
    }
  }

  // Handle empty state message
  const emptyState = document.getElementById('emptyResultsMsg');
  if (emptyState) {
    if (visibleCount === 0) {
      emptyState.style.display = 'block';
      const emptyTitle = emptyState.querySelector('h4');
      const emptyDesc = emptyState.querySelector('p');
      if (filterFavoritesOnly && favs.length === 0) {
        if (emptyTitle) emptyTitle.textContent = 'No saved properties yet';
        if (emptyDesc) emptyDesc.textContent = 'Click the heart icon on any property card to bookmark it for later review.';
      } else {
        if (emptyTitle) emptyTitle.textContent = 'No matching properties found';
        if (emptyDesc) emptyDesc.textContent = 'Try adjusting your search keywords, filter options, or price constraints.';
      }
    } else {
      emptyState.style.display = 'none';
    }
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
  const searchInput = document.getElementById('filterSearch');
  const clearSearchBtn = document.getElementById('clearSearchBtn');
  const sortSelect = document.getElementById('filterSort');
  const favToggleBtn = document.getElementById('filterFavoritesBtn');

  if (purposeSelect) purposeSelect.value = 'all';
  if (citySelect) citySelect.value = 'all';
  if (typeSelect) typeSelect.value = 'all';
  if (bedroomsSelect) bedroomsSelect.value = 'all';
  if (minPriceInput) minPriceInput.value = '';
  if (maxPriceInput) maxPriceInput.value = '';
  if (availableCheck) availableCheck.checked = false;
  if (searchInput) searchInput.value = '';
  if (clearSearchBtn) clearSearchBtn.style.display = 'none';
  if (sortSelect) sortSelect.value = 'default';
  
  filterFavoritesOnly = false;
  if (favToggleBtn) favToggleBtn.classList.remove('active');

  applyPropertySorting();
  applyPropertyFilters();
}

/**
 * Saved / Favorites Engine (Persistent localStorage)
 */
function getFavorites() {
  try {
    const raw = localStorage.getItem('apd_favorites');
    const parsed = raw ? JSON.parse(raw) : [];
    return Array.isArray(parsed) ? parsed : [];
  } catch (e) {
    return [];
  }
}

function saveFavorites(favs) {
  try {
    localStorage.setItem('apd_favorites', JSON.stringify(favs));
  } catch (e) {
    // Ignore storage quota errors
  }
}

function toggleFavorite(propId) {
  if (!propId) return false;
  let favs = getFavorites();
  const index = favs.indexOf(propId);
  let isSaved = false;

  if (index === -1) {
    favs.push(propId);
    isSaved = true;
    showToast(`Property ${propId} saved to your favorites!`);
  } else {
    favs.splice(index, 1);
    isSaved = false;
    showToast(`Property ${propId} removed from favorites.`);
  }

  saveFavorites(favs);
  updateFavoriteBadges();
  syncFavoriteButtons();

  // If we are currently in favorites-only view, re-filter
  if (filterFavoritesOnly) {
    applyPropertyFilters();
  }

  return isSaved;
}

function updateFavoriteBadges() {
  const count = getFavorites().length;
  document.querySelectorAll('.fav-count-badge').forEach(badge => {
    badge.textContent = count;
  });
}

function syncFavoriteButtons() {
  const favs = getFavorites();

  // Card heart buttons
  document.querySelectorAll('.btn-fav-card').forEach(btn => {
    const id = btn.getAttribute('data-prop-id');
    const active = favs.includes(id);
    btn.classList.toggle('active', active);
    btn.setAttribute('title', active ? 'Remove from Favorites' : 'Save to Favorites');
  });

  // Details page heart button
  const detailBtn = document.getElementById('btnFavDetail');
  if (detailBtn) {
    const id = detailBtn.getAttribute('data-prop-id');
    const active = favs.includes(id);
    detailBtn.classList.toggle('active', active);
    const label = detailBtn.querySelector('.fav-detail-label');
    if (label) {
      label.textContent = active ? 'Saved in Favorites' : 'Save to Favorites';
    }
  }
}

function initFavoritesSystem() {
  updateFavoriteBadges();
  syncFavoriteButtons();

  // Wire click listener for property cards (delegation)
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.btn-fav-card');
    if (btn) {
      e.preventDefault();
      e.stopPropagation();
      const propId = btn.getAttribute('data-prop-id');
      toggleFavorite(propId);
    }
  });
}

/**
 * PKR Mortgage & Financing Calculator
 */
function initMortgageCalculator() {
  const calcCard = document.getElementById('mortgageCalculator');
  if (!calcCard) return;

  const priceInput = document.getElementById('calcPropPrice');
  const downSlider = document.getElementById('calcDownPct');
  const tenureSlider = document.getElementById('calcTenure');
  const rateInput = document.getElementById('calcRate');
  const downPills = document.querySelectorAll('.calc-pill-pct');
  const tenurePills = document.querySelectorAll('.calc-pill-tenure');
  const applyBtn = document.getElementById('btnApplyPlanToInquiry');

  function calculate() {
    const price = parseFloat(priceInput?.value) || 0;
    const downPct = parseFloat(downSlider?.value) || 20;
    const tenureYears = parseInt(tenureSlider?.value) || 15;
    const annualRate = parseFloat(rateInput?.value) || 15.5;

    const downAmount = price * (downPct / 100);
    const loanAmount = Math.max(0, price - downAmount);
    const months = tenureYears * 12;
    const monthlyRate = (annualRate / 100) / 12;

    let monthlyInstallment = 0;
    if (loanAmount > 0 && monthlyRate > 0) {
      monthlyInstallment = (loanAmount * (monthlyRate * Math.pow(1 + monthlyRate, months))) / (Math.pow(1 + monthlyRate, months) - 1);
    }

    const totalMarkup = (monthlyInstallment * months) - loanAmount;
    const totalRepayment = downAmount + (monthlyInstallment * months);

    // Update labels
    const downPctLabel = document.getElementById('calcDownPctLabel');
    const downAmtLabel = document.getElementById('calcDownAmtLabel');
    const tenureLabel = document.getElementById('calcTenureLabel');
    const monthsLabel = document.getElementById('calcMonthsLabel');
    const rateLabel = document.getElementById('calcRateLabel');
    const priceFormatted = document.getElementById('calcPriceFormatted');

    if (downPctLabel) downPctLabel.textContent = `${downPct}%`;
    if (downAmtLabel) downAmtLabel.textContent = formatPKR(downAmount);
    if (tenureLabel) tenureLabel.textContent = `${tenureYears} Years`;
    if (monthsLabel) monthsLabel.textContent = `${months} Months`;
    if (rateLabel) rateLabel.textContent = `${annualRate}% Annual`;
    if (priceFormatted) priceFormatted.textContent = `Valuation: ${formatPKR(price)}`;

    // Update result cards
    const monthlyVal = document.getElementById('calcMonthlyVal');
    const loanVal = document.getElementById('calcLoanVal');
    const downVal = document.getElementById('calcDownVal');
    const profitVal = document.getElementById('calcProfitVal');
    const totalVal = document.getElementById('calcTotalVal');

    if (monthlyVal) monthlyVal.textContent = formatPKR(Math.round(monthlyInstallment)) + ' / Mo';
    if (loanVal) loanVal.textContent = formatPKR(Math.round(loanAmount));
    if (downVal) downVal.textContent = formatPKR(Math.round(downAmount));
    if (profitVal) profitVal.textContent = formatPKR(Math.round(totalMarkup));
    if (totalVal) totalVal.textContent = formatPKR(Math.round(totalRepayment));
  }

  if (priceInput) priceInput.addEventListener('input', calculate);

  if (downSlider) {
    downSlider.addEventListener('input', () => {
      downPills.forEach(p => p.classList.toggle('active', p.getAttribute('data-pct') === downSlider.value));
      calculate();
    });
  }

  downPills.forEach(pill => {
    pill.addEventListener('click', () => {
      downPills.forEach(p => p.classList.remove('active'));
      pill.classList.add('active');
      if (downSlider) {
        downSlider.value = pill.getAttribute('data-pct');
        calculate();
      }
    });
  });

  if (tenureSlider) {
    tenureSlider.addEventListener('input', () => {
      tenurePills.forEach(p => p.classList.toggle('active', p.getAttribute('data-years') === tenureSlider.value));
      calculate();
    });
  }

  tenurePills.forEach(pill => {
    pill.addEventListener('click', () => {
      tenurePills.forEach(p => p.classList.remove('active'));
      pill.classList.add('active');
      if (tenureSlider) {
        tenureSlider.value = pill.getAttribute('data-years');
        calculate();
      }
    });
  });

  if (rateInput) rateInput.addEventListener('input', calculate);

  // Apply plan to inquiry form message
  if (applyBtn) {
    applyBtn.addEventListener('click', (e) => {
      e.preventDefault();
      const price = parseFloat(priceInput?.value) || 0;
      const downPct = parseFloat(downSlider?.value) || 20;
      const tenureYears = parseInt(tenureSlider?.value) || 15;
      const downAmount = price * (downPct / 100);
      const loanAmount = Math.max(0, price - downAmount);
      const months = tenureYears * 12;
      const annualRate = parseFloat(rateInput?.value) || 15.5;
      const monthlyRate = (annualRate / 100) / 12;
      const monthlyInstallment = (loanAmount > 0 && monthlyRate > 0)
        ? Math.round((loanAmount * (monthlyRate * Math.pow(1 + monthlyRate, months))) / (Math.pow(1 + monthlyRate, months) - 1))
        : 0;

      const inqMsg = document.getElementById('detailInqMsg');
      const inqForm = document.getElementById('propertyInquiryForm');

      if (inqMsg) {
        inqMsg.value = `Assalam-o-Alaikum,\nI am interested in acquiring this property with an installment/financing plan:\n• Property Valuation: ${formatPKR(price)}\n• Down Payment: ${formatPKR(downAmount)} (${downPct}%)\n• Financing Period: ${tenureYears} Years (${months} Months)\n• Estimated Installment: ${formatPKR(monthlyInstallment)} / Month\n\nPlease advise on bank pre-approval and arrange a property visit.`;
      }

      if (inqForm) {
        inqForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
        inqMsg.focus();
        showToast('Financing plan applied to inquiry form!');
      }
    });
  }

  // Initial calculation run
  calculate();
}

/**
 * Format Currency in Pakistani Rupee (Crore, Lakh, Thousand)
 */
function formatPKR(val) {
  const num = parseFloat(val);
  if (isNaN(num) || num <= 0) return 'PKR 0';

  if (num >= 10000000) {
    const crore = num / 10000000;
    return `PKR ${crore.toFixed(2).replace(/\.00$/, '')} Crore`;
  } else if (num >= 100000) {
    const lakh = num / 100000;
    return `PKR ${lakh.toFixed(2).replace(/\.00$/, '')} Lakh`;
  } else {
    return 'PKR ' + num.toLocaleString('en-PK');
  }
}

/**
 * Property Details Header Actions (Favorites, Share, Print)
 */
function initPropertyDetailActions() {
  const favBtn = document.getElementById('btnFavDetail');
  const shareBtn = document.getElementById('btnShareDetail');

  if (favBtn) {
    favBtn.addEventListener('click', (e) => {
      e.preventDefault();
      const id = favBtn.getAttribute('data-prop-id');
      toggleFavorite(id);
    });
  }

  if (shareBtn) {
    shareBtn.addEventListener('click', async (e) => {
      e.preventDefault();
      const shareData = {
        title: document.title,
        text: 'Check out this prime property listing on Abdullah Property Dealer Pakistan:',
        url: window.location.href
      };

      if (navigator.share) {
        try {
          await navigator.share(shareData);
          return;
        } catch (err) {
          // If dismissed by user, do nothing
          if (err.name === 'AbortError') return;
        }
      }

      // Clipboard fallback
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(window.location.href).then(() => {
          showToast('Property listing link copied to clipboard!');
        }).catch(() => {
          prompt('Copy property URL:', window.location.href);
        });
      } else {
        prompt('Copy property URL:', window.location.href);
      }
    });
  }
}

/**
 * Toast Notification Popup
 */
function showToast(message, duration = 3200) {
  let toast = document.getElementById('apdToast');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'apdToast';
    toast.className = 'apd-toast';
    document.body.appendChild(toast);
  }

  toast.textContent = message;
  toast.classList.add('show');

  if (window.apdToastTimer) {
    clearTimeout(window.apdToastTimer);
  }

  window.apdToastTimer = setTimeout(() => {
    toast.classList.remove('show');
  }, duration);
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

/**
 * Search page - filter form, live results, URL sync
 */
document.addEventListener('DOMContentLoaded', () => {
    initFilterForm();
    initPriceRange();
    initSortListener();
});

/**
 * Auto-submit filter form on select change
 */
function initFilterForm() {
    const form = document.getElementById('filterForm');
    if (!form) return;

    form.querySelectorAll('select').forEach(sel => {
        sel.addEventListener('change', () => form.submit());
    });

    // Highlight active filters
    const params = new URLSearchParams(window.location.search);
    let activeCount = 0;
    ['city', 'min_price', 'max_price', 'rating', 'stars', 'sort'].forEach(k => {
        if (params.get(k)) activeCount++;
    });

    if (activeCount > 0) {
        const badge = document.createElement('span');
        badge.className = 'badge badge-info';
        badge.textContent = activeCount + ' active';
        badge.style.marginLeft = '8px';
        const heading = document.querySelector('aside h3');
        if (heading) heading.appendChild(badge);
    }
}

/**
 * Price range dual slider (if present)
 */
function initPriceRange() {
    const minInput = document.querySelector('input[name="min_price"]');
    const maxInput = document.querySelector('input[name="max_price"]');
    if (!minInput || !maxInput) return;

    minInput.addEventListener('change', () => {
        if (maxInput.value && parseInt(minInput.value) > parseInt(maxInput.value)) {
            maxInput.value = minInput.value;
        }
    });
}

/**
 * Sort dropdown auto-submit
 */
function initSortListener() {
    const sortSel = document.querySelector('select[name="sort"]');
    if (sortSel) {
        sortSel.addEventListener('change', () => {
            document.getElementById('filterForm')?.submit();
        });
    }
}

/**
 * Highlight search term in hotel names
 */
function highlightSearchTerm(term) {
    if (!term) return;
    const regex = new RegExp(`(${term})`, 'gi');
    document.querySelectorAll('.hotel-card-name, .hotel-card-location').forEach(el => {
        el.innerHTML = el.textContent.replace(regex, '<mark style="background:rgba(108,99,255,0.2);border-radius:3px;">$1</mark>');
    });
}

// Run highlight on page load
const searchCity = new URLSearchParams(window.location.search).get('city');
if (searchCity) highlightSearchTerm(searchCity);

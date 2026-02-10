import $ from 'jquery';
window.$ = window.jQuery = $;

import 'datatables.net';
import 'datatables.net-dt';
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

import './bootstrap';
import './components/sidebar';

// Navbar mobile menu functionality
document.addEventListener('DOMContentLoaded', function() {
    const hamburgerBtn = document.querySelector('.hamburger-btn');
    const mobileMenu = document.querySelector('.mobile-menu');

    if (hamburgerBtn && mobileMenu) {
        hamburgerBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        if (!hamburgerBtn.contains(event.target) && !mobileMenu.contains(event.target)) {
            mobileMenu.classList.add('hidden');
        }
    });
});

// DataTable initialization
window.initDataTable = function(selector, options = {}) {
    const defaultOptions = {
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        dom: 'f r t i p', // Search left, info right, no length menu
        info: false, // Hide info text
        stripe: true,
        language: {
            search: '<i class="fas fa-search text-gray-400 mr-2"></i>',
            searchPlaceholder: "Cari...",
            lengthMenu: "Tampilkan _MENU_ entri",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                last: '<i class="fas fa-angle-double-right"></i>',
                next: '<i class="fas fa-chevron-right"></i>',
                previous: '<i class="fas fa-chevron-left"></i>'
            }
        },
        ...options
    };

    return $(selector).DataTable(defaultOptions);
};

// Auto initialize DataTables for tables with class .datatable
$(document).ready(function() {
    $('.datatable').each(function() {
        const url = $(this).data('url');
        let columns = [];

        if (url && url.includes('users-api')) {
            columns = [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'role', name: 'role', orderable: false },
                { data: 'is_active', name: 'is_active', orderable: false },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ];
        } else if (url && url.includes('companies-api')) {
            columns = [
                { data: 'name', name: 'name' },
                { data: 'slug', name: 'slug' },
                { data: 'is_active_text', name: 'is_active' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ];
        } else if (url && url.includes('menus-api')) {
            columns = [
                { data: 'name', name: 'name' },
                { data: 'icon', name: 'icon' },
                { data: 'route', name: 'route' },
                { data: 'permission_name', name: 'permission_name' },
                { data: 'parent_name', name: 'parent_name' },
                { data: 'order', name: 'order' },
                { data: 'is_active', name: 'is_active', render: function(data) { return data ? 'Yes' : 'No'; } },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ];
        } else if (url && url.includes('clusters-api')) {
            columns = [
                { data: 'name', name: 'name' },
                { data: 'site_plan', name: 'site_plan', orderable: false },
                { data: 'address', name: 'address' },
                { data: 'price_range', name: 'price_range' },
                {
                    data: 'total_units',
                    name: 'total_units',
                    render: function(data) {
                        return data + ' units';
                    }
                },
                { data: 'is_active_text', name: 'is_active' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ];
        } else if (url && url.includes('reservations-api')) {
            columns = [
                { data: 'reservation_code', name: 'reservation_code', orderable: false },
                { data: 'customer_info', name: 'customer_name', orderable: false },
                { data: 'unit_info', name: 'unit_id', orderable: false },
                { data: 'sales_info', name: 'sales_id', orderable: false },
                { data: 'price_formatted', name: 'price_snapshot', orderable: false },
                { data: 'booking_fee_formatted', name: 'booking_fee', orderable: false },
                { data: 'dp_plan_formatted', name: 'dp_plan', orderable: false },
                { data: 'status_badge', name: 'status', orderable: false },
                { data: 'reservation_date_formatted', name: 'reservation_date' },
                { data: 'expired_at_formatted', name: 'expired_at' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ];
        } else if (url && url.includes('units-api')) {
            columns = [
                { data: 'cluster_name', name: 'cluster_name' },
                { data: 'name', name: 'name' },
                { data: 'unit_code', name: 'unit_code' },
                { data: 'house_type', name: 'house_type' },
                { data: 'land_area_formatted', name: 'land_area_formatted' },
                { data: 'building_area_formatted', name: 'building_area_formatted' },
                { data: 'price_formatted', name: 'price_formatted' },
                { data: 'progress_percentage', name: 'progress_percentage' },
                { data: 'status_badge', name: 'status_badge', orderable: false },
                { data: 'coordinates_status', name: 'coordinates_status', orderable: false },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ];
        }

        const options = url ? { ajax: url, columns: columns } : {};
        initDataTable(this, options);
    });
});

// Toggle dropdown function
window.toggleDropdown = function(id) {
    const dropdown = document.getElementById('dropdown-' + id);
    // Hide other dropdowns
    document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
        if (el !== dropdown) el.classList.add('hidden');
    });
    dropdown.classList.toggle('hidden');
};

// Event delegation for dropdown toggles
$(document).on('click', '.dropdown-toggle', function(e) {
    e.stopPropagation();
    const menuId = $(this).data('menu-id');
    if (menuId) {
        toggleDropdown(menuId);
    }
});

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.dropdown-actions')) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
            el.classList.add('hidden');
        });
    }
});

// Font Awesome Icon Picker
const fontAwesomeIcons = [
    'fas fa-home', 'fas fa-tachometer-alt', 'fas fa-users', 'fas fa-building',
    'fas fa-bars', 'fas fa-cog', 'fas fa-user', 'fas fa-sign-out-alt',
    'fas fa-plus', 'fas fa-edit', 'fas fa-trash', 'fas fa-eye',
    'fas fa-search', 'fas fa-filter', 'fas fa-download', 'fas fa-upload',
    'fas fa-file', 'fas fa-folder', 'fas fa-image', 'fas fa-video',
    'fas fa-music', 'fas fa-chart-bar', 'fas fa-chart-line', 'fas fa-chart-pie',
    'fas fa-calendar', 'fas fa-clock', 'fas fa-bell', 'fas fa-envelope',
    'fas fa-phone', 'fas fa-map-marker', 'fas fa-globe', 'fas fa-star',
    'fas fa-heart', 'fas fa-thumbs-up', 'fas fa-thumbs-down', 'fas fa-check',
    'fas fa-times', 'fas fa-exclamation-triangle', 'fas fa-info-circle',
    'fas fa-question-circle', 'fas fa-cogs', 'fas fa-wrench', 'fas fa-key',
    'fas fa-lock', 'fas fa-unlock', 'fas fa-shield-alt', 'fas fa-user-shield',
    'fas fa-database', 'fas fa-server', 'fas fa-cloud', 'fas fa-code',
    'fas fa-terminal', 'fas fa-bug', 'fas fa-lightbulb', 'fas fa-rocket',
    'fas fa-paper-plane', 'fas fa-share', 'fas fa-external-link-alt',
    'fas fa-link', 'fas fa-unlink', 'fas fa-copy', 'fas fa-paste',
    'fas fa-save', 'fas fa-print', 'fas fa-cut', 'fas fa-undo', 'fas fa-redo',
    'fas fa-list', 'fas fa-list-ol', 'fas fa-list-ul', 'fas fa-table',
    'fas fa-columns', 'fas fa-align-left', 'fas fa-align-center',
    'fas fa-align-right', 'fas fa-bold', 'fas fa-italic', 'fas fa-underline',
    'fas fa-palette', 'fas fa-paint-brush', 'fas fa-camera', 'fas fa-play',
    'fas fa-pause', 'fas fa-stop', 'fas fa-forward', 'fas fa-backward',
    'fas fa-step-forward', 'fas fa-step-backward', 'fas fa-eject',
    'fas fa-shopping-cart', 'fas fa-credit-card', 'fas fa-money-bill',
    'fas fa-calculator', 'fas fa-tags', 'fas fa-tag', 'fas fa-gift',
    'fas fa-truck', 'fas fa-plane', 'fas fa-car', 'fas fa-bicycle',
    'fas fa-ship', 'fas fa-anchor', 'fas fa-umbrella', 'fas fa-coffee',
    'fas fa-utensils', 'fas fa-glass-martini', 'fas fa-beer', 'fas fa-wine-glass'
];

function initIconPicker() {
    const searchInput = document.getElementById('icon-search');
    const dropdown = document.getElementById('icon-dropdown');
    const iconList = document.getElementById('icon-list');
    const hiddenInput = document.getElementById('icon');
    const previewIcon = document.getElementById('preview-icon');
    const iconText = document.getElementById('icon-text');

    if (!searchInput || !dropdown || !iconList) return;

    // Populate initial icons
    renderIcons(fontAwesomeIcons);

    // Show dropdown on focus
    searchInput.addEventListener('focus', function() {
        dropdown.classList.remove('hidden');
    });

    // Filter icons on input
    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const filteredIcons = fontAwesomeIcons.filter(icon =>
            icon.toLowerCase().includes(query)
        );
        renderIcons(filteredIcons);
        dropdown.classList.remove('hidden');
    });

    // Hide dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });

    function renderIcons(icons) {
        iconList.innerHTML = '';
        icons.forEach(icon => {
            const item = document.createElement('div');
            item.className = 'flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer';
            item.innerHTML = `
                <i class="${icon} mr-3 text-gray-600"></i>
                <span class="text-sm text-gray-700">${icon}</span>
            `;
            item.addEventListener('click', function() {
                hiddenInput.value = icon;
                searchInput.value = icon;
                previewIcon.className = icon + ' text-gray-600';
                iconText.textContent = icon;
                dropdown.classList.add('hidden');
            });
            iconList.appendChild(item);
        });
    }

    // Set initial value if exists
    const initialValue = hiddenInput.value;
    if (initialValue) {
        searchInput.value = initialValue;
        previewIcon.className = initialValue + ' text-gray-600';
        iconText.textContent = initialValue;
    }
}

// Initialize icon picker when DOM is ready
$(document).ready(function() {
    initIconPicker();
    initSlugGenerator();
});

// Slug Generator
function initSlugGenerator() {
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');

    if (!nameInput || !slugInput) return;

    // Auto-generate slug from name
    nameInput.addEventListener('input', function() {
        const name = this.value;
        const slug = name
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '') // Remove special characters
            .replace(/[\s_-]+/g, '-') // Replace spaces, underscores with hyphens
            .replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens

        slugInput.value = slug;
    });

    // Prevent manual editing of slug if it was auto-generated
    let lastAutoGeneratedSlug = '';
    nameInput.addEventListener('input', function() {
        lastAutoGeneratedSlug = slugInput.value;
    });

    slugInput.addEventListener('focus', function() {
        if (this.value === lastAutoGeneratedSlug) {
            // Allow editing if it matches auto-generated
            this.dataset.wasAutoGenerated = 'true';
        }
    });

    slugInput.addEventListener('input', function() {
        if (this.dataset.wasAutoGenerated === 'true') {
            // Clear the flag once user starts typing
            delete this.dataset.wasAutoGenerated;
        }
    });
}

// Initialize Leaflet map
window.initMap = function(containerId, lat, lng, zoom = 15) {
    const map = L.map(containerId).setView([lat, lng], zoom);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    L.marker([lat, lng]).addTo(map)
        .bindPopup('Cluster Location')
        .openPopup();

    return map;
};

// Progress Bar Functionality
window.initProgressBar = function() {
    const progressInput = document.getElementById('progress');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');

    if (progressInput && progressBar && progressText) {
        // Update progress bar on input change
        progressInput.addEventListener('input', function() {
            const value = Math.min(100, Math.max(0, this.value || 0));
            progressBar.style.width = value + '%';
            progressText.textContent = value + '%';
        });

        // Update progress bar on page load
        const initialValue = Math.min(100, Math.max(0, progressInput.value || 0));
        progressBar.style.width = initialValue + '%';
        progressText.textContent = initialValue + '%';
    }
};

// Initialize progress bar when DOM is ready
$(document).ready(function() {
    initProgressBar();
});

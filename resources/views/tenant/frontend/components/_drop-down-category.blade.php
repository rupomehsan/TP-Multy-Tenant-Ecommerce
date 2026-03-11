@php
    $categories = DB::table('categories')->where('show_on_navbar', 1)->get();
@endphp

<style>
    /* Modern Navigation Bar Styles */
    .modern-nav-wrapper {
        background: #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        position: relative;
        z-index: 100;
    }

    .modern-nav-container {
        padding-right: 144px;
        padding-left: 144px;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    /* All Categories Button */
    .all-categories-btn {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
        color: white;
        padding: 15px 25px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        border-radius: 0;
        min-width: 280px;
        position: relative;
    }

    .all-categories-btn:hover {
        background: linear-gradient(135deg, #ee5a6f 0%, #ff6b6b 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);
    }

    .all-categories-btn .hamburger-icon {
        font-size: 20px;
        display: flex;
        align-items: center;
    }

    .all-categories-btn.active {
        background: linear-gradient(135deg, #ee5a6f 0%, #d84560 100%);
    }

    /* Horizontal Navigation Menu */
    .horizontal-nav {
        flex: 1;
        display: flex;
        align-items: center;
    }

    .nav-menu-list {
        display: flex;
        align-items: center;
        gap: 0;
        list-style: none;
        margin: 0;
        padding: 0;
        flex-wrap: wrap;
    }

    .nav-menu-item {
        position: relative;
    }

    .nav-menu-link {
        display: flex;
        align-items: center;
        padding: 18px 20px;
        color: #333;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        transition: all 0.3s ease;
        position: relative;
    }

    .nav-menu-link:hover {
        color: #ff6b6b;
        background: #fff5f5;
    }

    .nav-menu-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%) scaleX(0);
        width: 80%;
        height: 3px;
        background: linear-gradient(90deg, #ff6b6b, #ee5a6f);
        transition: transform 0.3s ease;
    }

    .nav-menu-link:hover::after,
    .nav-menu-link.active::after {
        transform: translateX(-50%) scaleX(1);
    }

    .nav-menu-link .dropdown-arrow {
        margin-left: 5px;
        font-size: 10px;
        transition: transform 0.3s ease;
    }

    .nav-menu-link:hover .dropdown-arrow {
        transform: rotate(180deg);
    }

    /* Dropdown Mega Menu */
    .mega-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        background: white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        min-width: 250px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        z-index: 1000;
        border-top: 3px solid #ff6b6b;
    }

    .nav-menu-item:hover .mega-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .mega-dropdown-inner {
        padding: 20px;
        max-height: 500px;
        overflow-y: auto;
    }

    .mega-dropdown-item {
        padding: 10px 15px;
        transition: all 0.3s ease;
        border-radius: 4px;
    }

    .mega-dropdown-item:hover {
        background: #fff5f5;
    }

    .mega-dropdown-link {
        color: #555;
        text-decoration: none;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 500;
    }

    .mega-dropdown-link:hover {
        color: #ff6b6b;
    }

    .mega-dropdown-link i {
        font-size: 11px;
        opacity: 0.6;
    }

    /* Child Dropdown */
    .child-dropdown {
        display: none;
        padding-left: 20px;
        margin-top: 8px;
    }

    .child-dropdown.active {
        display: block;
    }

    .child-dropdown-item {
        padding: 8px 0;
    }

    .child-dropdown-link {
        color: #666;
        text-decoration: none;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: color 0.3s ease;
    }

    .child-dropdown-link:hover {
        color: #ff6b6b;
    }

    .child-dropdown-link::before {
        content: '•';
        color: #ff6b6b;
    }

    /* Sidebar Dropdown (appears below button) */
    .category-sidebar-dropdown {
        position: absolute;
        top: 100%;
        left: 144px;
        width: 280px;
        background: white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        max-height: 470px;
        overflow-y: auto;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        z-index: 999;
        border-top: 3px solid #ff6b6b;
    }

    .category-sidebar-dropdown.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .sidebar-menu-item {
        border-bottom: 1px solid #f0f0f0;
    }

    .sidebar-menu-item:last-child {
        border-bottom: none;
    }

    .sidebar-item-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        color: #333;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .sidebar-item-link:hover {
        background: #fff5f5;
        color: #ff6b6b;
        padding-left: 25px;
    }

    .sidebar-item-content {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }

    .sidebar-icon {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #ff6b6b;
    }

    .sidebar-arrow {
        font-size: 12px;
        transition: transform 0.3s ease;
        color: #999;
    }

    .sidebar-item-link.active .sidebar-arrow {
        transform: rotate(180deg);
        color: #ff6b6b;
    }

    /* Sidebar Submenu */
    .sidebar-submenu {
        display: none;
        background: #fff5f5;
        padding: 8px 0;
    }

    .sidebar-submenu.active {
        display: block;
    }

    .sidebar-submenu-item {
        padding: 10px 20px 10px 56px;
    }

    .sidebar-submenu-link {
        color: #555;
        text-decoration: none;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: color 0.3s ease;
    }

    .sidebar-submenu-link:hover {
        color: #ff6b6b;
    }

    /* Sidebar Child Submenu */
    .sidebar-child-submenu {
        display: none;
        padding: 8px 0 8px 20px;
    }

    .sidebar-child-submenu.active {
        display: block;
    }

    .sidebar-child-item {
        padding: 8px 0;
    }

    .sidebar-child-link {
        color: #666;
        text-decoration: none;
        font-size: 12px;
        transition: color 0.3s ease;
    }

    .sidebar-child-link:hover {
        color: #ff6b6b;
    }

    .sidebar-child-link::before {
        content: '• ';
        color: #ff6b6b;
    }

    /* Scrollbar for dropdowns */
    .category-sidebar-dropdown::-webkit-scrollbar,
    .mega-dropdown-inner::-webkit-scrollbar {
        width: 6px;
    }

    .category-sidebar-dropdown::-webkit-scrollbar-track,
    .mega-dropdown-inner::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .category-sidebar-dropdown::-webkit-scrollbar-thumb,
    .mega-dropdown-inner::-webkit-scrollbar-thumb {
        background: #ff6b6b;
        border-radius: 3px;
    }

    /* Mobile Responsive */
    @media (max-width: 991px) {
        .modern-nav-container {
            flex-wrap: wrap;
        }

        .all-categories-btn {
            width: 100%;
            justify-content: center;
        }

        .horizontal-nav {
            width: 100%;
            overflow-x: auto;
        }

        .nav-menu-list {
            flex-wrap: nowrap;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .nav-menu-link {
            white-space: nowrap;
            padding: 15px 15px;
            font-size: 12px;
        }

        .mega-dropdown {
            position: fixed;
            left: 0;
            right: 0;
            min-width: 100%;
        }

        .category-sidebar-dropdown {
            left: 0;
            right: 0;
            width: 100%;
        }
    }

    @media (max-width: 768px) {
        .nav-menu-link {
            padding: 12px 10px;
            font-size: 11px;
        }
    }
</style>

<!-- Modern Navigation Bar -->
<nav class="modern-nav-wrapper">
    <div class="modern-nav-container">
        <!-- All Categories Button (Left Side) -->
        <button class="all-categories-btn" id="allCategoriesBtn">
            <span class="hamburger-icon">
                <i class="bi bi-list"></i>
            </span>
            <span>All Categories</span>
        </button>

        <!-- Horizontal Navigation Menu (Right Side) -->
        <div class="horizontal-nav">
            <ul class="nav-menu-list">
                <li class="nav-menu-item">
                    <a href="{{ url('/') }}" class="nav-menu-link active">
                        HOME
                    </a>
                </li>
                @foreach ($categories as $index => $category)
                    @if ($category->show_on_navbar == 1)
                        @php
                            $subcategories = DB::table('subcategories')->where('category_id', $category->id)->get();
                        @endphp
                        <li class="nav-menu-item">
                            <a href="{{ url('shop') }}?category={{ $category->slug }}" class="nav-menu-link">
                                {{ $category->name }}
                                @if (count($subcategories) > 0)
                                    <span class="dropdown-arrow">
                                        <i class="bi bi-chevron-down"></i>
                                    </span>
                                @endif
                            </a>

                            @if (count($subcategories) > 0)
                                <div class="mega-dropdown">
                                    <div class="mega-dropdown-inner">
                                        @foreach ($subcategories as $subcategory)
                                            @php
                                                $childcategories = DB::table('child_categories')
                                                    ->where('subcategory_id', $subcategory->id)
                                                    ->get();
                                            @endphp
                                            <div class="mega-dropdown-item">
                                                <a href="{{ url('shop') }}?category={{ $category->slug }}&subcategory_id={{ $subcategory->id }}"
                                                    class="mega-dropdown-link"
                                                    data-subcategory-id="{{ $subcategory->id }}">
                                                    <span>{{ $subcategory->name }}</span>
                                                    @if (count($childcategories) > 0)
                                                        <i class="bi bi-chevron-right"></i>
                                                    @endif
                                                </a>

                                                @if (count($childcategories) > 0)
                                                    <div class="child-dropdown" id="navchild-{{ $subcategory->id }}">
                                                        @foreach ($childcategories as $childcategory)
                                                            <div class="child-dropdown-item">
                                                                <a href="{{ url('shop') }}?category={{ $category->slug }}&subcategory_id={{ $subcategory->id }}&childcategory_id={{ $childcategory->id }}"
                                                                    class="child-dropdown-link">
                                                                    {{ $childcategory->name }}
                                                                </a>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Category Sidebar Dropdown (appears below ALL CATEGORIES button) -->
    <div class="category-sidebar-dropdown active" id="categorySidebarDropdown">
        @foreach ($categories as $category)
            @if ($category->show_on_navbar == 1)
                @php
                    $subcategories = DB::table('subcategories')->where('category_id', $category->id)->get();
                @endphp
                <div class="sidebar-menu-item">
                    <div class="sidebar-item-link" data-category-id="{{ $category->id }}">
                        <div class="sidebar-item-content">
                            <span class="sidebar-icon">
                                @if (stripos($category->name, 'hot') !== false || stripos($category->name, 'offer') !== false)
                                    <i class="bi bi-fire"></i>
                                @elseif (stripos($category->name, 'brand') !== false)
                                    <i class="bi bi-star-fill"></i>
                                @elseif (stripos($category->name, 'makeup') !== false)
                                    <i class="bi bi-brush"></i>
                                @elseif (stripos($category->name, 'health') !== false || stripos($category->name, 'beauty') !== false)
                                    <i class="bi bi-heart-pulse"></i>
                                @elseif (stripos($category->name, 'bath') !== false || stripos($category->name, 'body') !== false)
                                    <i class="bi bi-droplet"></i>
                                @elseif (stripos($category->name, 'hair') !== false)
                                    <i class="bi bi-scissors"></i>
                                @elseif (stripos($category->name, 'kids') !== false || stripos($category->name, 'baby') !== false)
                                    <i class="bi bi-balloon"></i>
                                @elseif (stripos($category->name, 'mens') !== false || stripos($category->name, 'men') !== false)
                                    <i class="bi bi-briefcase"></i>
                                @elseif (stripos($category->name, 'perfume') !== false)
                                    <i class="bi bi-flower1"></i>
                                @elseif (stripos($category->name, 'groom') !== false)
                                    <i class="bi bi-gem"></i>
                                @elseif (stripos($category->name, 'fashion') !== false || stripos($category->name, 'accessories') !== false)
                                    <i class="bi bi-bag"></i>
                                @elseif (stripos($category->name, 'home') !== false || stripos($category->name, 'lifestyle') !== false)
                                    <i class="bi bi-house-door"></i>
                                @else
                                    <i class="bi bi-box-seam"></i>
                                @endif
                            </span>
                            <span>{{ $category->name }}</span>
                        </div>
                        @if (count($subcategories) > 0)
                            <span class="sidebar-arrow">
                                <i class="bi bi-chevron-down"></i>
                            </span>
                        @endif
                    </div>

                    @if (count($subcategories) > 0)
                        <div class="sidebar-submenu" id="sidebarsubmenu-{{ $category->id }}">
                            @foreach ($subcategories as $subcategory)
                                @php
                                    $childcategories = DB::table('child_categories')
                                        ->where('subcategory_id', $subcategory->id)
                                        ->get();
                                @endphp
                                <div class="sidebar-submenu-item">
                                    <a href="{{ url('shop') }}?category={{ $category->slug }}&subcategory_id={{ $subcategory->id }}"
                                        class="sidebar-submenu-link"
                                        data-sidebar-subcategory-id="{{ $subcategory->id }}">
                                        <span>{{ $subcategory->name }}</span>
                                        @if (count($childcategories) > 0)
                                            <i class="bi bi-chevron-right"></i>
                                        @endif
                                    </a>

                                    @if (count($childcategories) > 0)
                                        <div class="sidebar-child-submenu" id="sidebarchild-{{ $subcategory->id }}">
                                            @foreach ($childcategories as $childcategory)
                                                <div class="sidebar-child-item">
                                                    <a href="{{ url('shop') }}?category={{ $category->slug }}&subcategory_id={{ $subcategory->id }}&childcategory_id={{ $childcategory->id }}"
                                                        class="sidebar-child-link">
                                                        {{ $childcategory->name }}
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        @endforeach
    </div>
</nav>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Toggle ALL CATEGORIES dropdown
        const allCategoriesBtn = document.getElementById('allCategoriesBtn');
        const categorySidebarDropdown = document.getElementById('categorySidebarDropdown');

        if (allCategoriesBtn && categorySidebarDropdown) {
            allCategoriesBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                this.classList.toggle('active');
                categorySidebarDropdown.classList.toggle('active');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!allCategoriesBtn.contains(e.target) && !categorySidebarDropdown.contains(e
                        .target)) {
                    allCategoriesBtn.classList.remove('active');
                    categorySidebarDropdown.classList.remove('active');
                }
            });
        }

        // Toggle sidebar categories
        const sidebarCategoryLinks = document.querySelectorAll('[data-category-id]');
        sidebarCategoryLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const categoryId = this.getAttribute('data-category-id');
                const submenu = document.getElementById('sidebarsubmenu-' + categoryId);

                if (submenu) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Close other open submenus
                    const allSubmenus = document.querySelectorAll('.sidebar-submenu');
                    allSubmenus.forEach(sub => {
                        if (sub !== submenu) {
                            sub.classList.remove('active');
                        }
                    });

                    // Close all sidebar category links
                    sidebarCategoryLinks.forEach(l => {
                        if (l !== this) {
                            l.classList.remove('active');
                        }
                    });

                    // Toggle active class
                    this.classList.toggle('active');

                    // Toggle submenu
                    submenu.classList.toggle('active');
                }
            });
        });

        // Toggle sidebar child categories
        const sidebarSubcategoryLinks = document.querySelectorAll('[data-sidebar-subcategory-id]');
        sidebarSubcategoryLinks.forEach(link => {
            const subcategoryId = link.getAttribute('data-sidebar-subcategory-id');
            const childMenu = document.getElementById('sidebarchild-' + subcategoryId);

            if (childMenu) {
                link.addEventListener('click', function(e) {
                    const arrow = this.querySelector('i.bi-chevron-right');
                    if (arrow) {
                        e.preventDefault();
                        e.stopPropagation();

                        childMenu.classList.toggle('active');

                        // Rotate arrow
                        if (childMenu.classList.contains('active')) {
                            arrow.style.transform = 'rotate(90deg)';
                        } else {
                            arrow.style.transform = 'rotate(0deg)';
                        }
                    }
                });
            }
        });

        // Toggle navigation child categories (in hover dropdowns)
        const navSubcategoryLinks = document.querySelectorAll('[data-subcategory-id]');
        navSubcategoryLinks.forEach(link => {
            const subcategoryId = link.getAttribute('data-subcategory-id');
            const childMenu = document.getElementById('navchild-' + subcategoryId);

            if (childMenu) {
                link.addEventListener('click', function(e) {
                    const arrow = this.querySelector('i.bi-chevron-right');
                    if (arrow) {
                        e.preventDefault();
                        e.stopPropagation();

                        childMenu.classList.toggle('active');

                        // Rotate arrow
                        if (childMenu.classList.contains('active')) {
                            arrow.style.transform = 'rotate(90deg)';
                        } else {
                            arrow.style.transform = 'rotate(0deg)';
                        }
                    }
                });
            }
        });

        // Highlight active menu item based on current URL
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-menu-link');
        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            } else if (currentPath === '/' || currentPath === '') {
                // Keep HOME active on homepage
                if (link.textContent.trim() === 'HOME') {
                    link.classList.add('active');
                }
            }
        });
    });
</script>

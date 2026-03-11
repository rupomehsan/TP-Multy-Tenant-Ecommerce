@php
    $categories = DB::table('categories')->where('show_on_navbar', 1)->limit(10)->get();
@endphp

<style>
    .sidebar-header {
        cursor: pointer;
        user-select: none;
    }

    .menu-button {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .menu-icon {
        flex-shrink: 0;
    }

    .menu-arrow {
        margin-left: auto;
        font-size: 14px;
        color: #666;
    }

    .submenu-button {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .submenu-link {
        flex: 1;
        text-decoration: none;
    }

    .submenu-arrow {
        margin-left: auto;
        font-size: 12px;
        color: #666;
        flex-shrink: 0;
    }

    .child-submenu-link {
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
</style>


<div class="header__bottom">
    <div class="container-fluid">
        <div
            class="header__bottom--inner position__relative d-none d-lg-flex justify-content-start gap-5 align-items-center">
            <div class="sidebar-header d-flex justify-content-between align-items-center" id="sidebarToggle">
                BROWSE CATEGORIES <i>☰</i>
            </div>
            <div class="header__menu">
                <nav class="header__menu--navigation">
                    <ul class="d-flex">
                        <li class="header__menu--items">
                            <a class="header__menu--link"
                                @if (Request::path() == '/') style="font-weight: 600" @endif
                                href="{{ url('/') }}"> Home </a>
                        </li>
                        <li class="header__menu--items">
                            <a class="header__menu--link"
                                @if (Request::path() == 'shop') style="font-weight: 600" @endif
                                href="{{ url('shop') }}"> Shop </a>
                        </li>
                        <li class="header__menu--items">
                            <a class="header__menu--link"
                                @if (Request::path() == 'shop') style="font-weight: 600" @endif
                                href="{{ url('shop') }}/?category=&filter=packages"> Package </a>
                        </li>

                        {{-- @foreach ($categories as $category)
                                    @if ($category->show_on_navbar == 1)
                                        @php
                                            $subcategories = DB::table('subcategories')
                                                ->where('category_id', $category->id)
                                                ->get();
                                        @endphp

                                        <li class="header__menu--items">
                                            <a class="header__menu--link"
                                                @if (str_contains(Request::fullUrl(), '=' . $category->slug)) style="font-weight: 600" @endif
                                                href="{{ url('shop') }}?category={{ $category->slug }}">
                                                {{ $category->name }}

                                                @if (count($subcategories) > 0)
                                                    <svg class="menu__arrowdown--icon"
                                                        xmlns="http://www.w3.org/2000/svg" width="12"
                                                        height="7.41" viewBox="0 0 12 7.41">
                                                        <path d="M16.59,8.59,12,13.17,7.41,8.59,6,10l6,6,6-6Z"
                                                            transform="translate(-6 -8.59)" fill="currentColor"
                                                            opacity="0.7" />
                                                    </svg>
                                                @endif
                                            </a>

                                            @if (count($subcategories) > 0)
                                                <ul class="header__sub--menu">
                                                    @foreach ($subcategories as $subcategory)
                                                        <li class="header__sub--menu__items">
                                                            <a href="{{ url('shop') }}?category={{ $category->slug }}&subcategory_id={{ $subcategory->id }}"
                                                                class="header__sub--menu__link">{{ $subcategory->name }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endif
                                @endforeach --}}
                        {{-- <li class="header__menu--items">
                                    <a class="header__menu--link"
                                        href="{{ route('PhotoAlbum', [
                                            'sort' => request('sort', 'desc'),
                                            'category' => request('category'),
                                            'subcategory_id' => request('subcategory_id'),
                                        ]) }}">
                                        Album
                                    </a>
                                </li> --}}

                        <li class="header__menu--items">
                            <a class="header__menu--link"
                                @if (Request::path() == 'video-gallery') style="font-weight: 600" @endif
                                href="{{ url('/video-gallery') }}">Video Gallery</a>
                        </li>

                        <li class="header__menu--items">
                            <a class="header__menu--link"
                                @if (Request::path() == 'outlet') style="font-weight: 600" @endif
                                href="{{ url('/outlet') }}">Outlet</a>
                        </li>
                        <li class="header__menu--items">
                            <a class="header__menu--link"
                                @if (Request::path() == 'blogs') style="font-weight: 600" @endif
                                href="{{ url('blogs') }}"> Blog </a>
                        </li>
                        @if ($custom_pages->count() > 0)
                            <li class="header__menu--items">
                                <a class="header__menu--link"
                                    href="{{ url('custom-page') }}/{{ $custom_pages[0]->slug }}">
                                    Custom Page
                                    <svg class="menu__arrowdown--icon" xmlns="http://www.w3.org/2000/svg" width="12"
                                        height="7.41" viewBox="0 0 12 7.41">
                                        <path d="M16.59,8.59,12,13.17,7.41,8.59,6,10l6,6,6-6Z"
                                            transform="translate(-6 -8.59)" fill="currentColor" opacity="0.7" />
                                    </svg>
                                </a>
                                <ul class="header__sub--menu">
                                    @foreach ($custom_pages as $page)
                                        <li class="header__sub--menu__items">
                                            <a href="{{ url('custom-page') }}/{{ $page->slug }}"
                                                class="header__sub--menu__link">{{ ucfirst($page->page_title) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                        {{-- <li class="header__menu--items">
                                    <a class="header__menu--link" @if (Request::path() == 'contact') style="font-weight: 600" @endif href="{{ url('contact') }}">Contact </a>
                                </li> --}}
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>


<!-- Main Content Container -->
<div class="container-fluid mb-3">
    <div class="row g-4">
        <div class="col-lg-3">
            <!-- Unified Sidebar for All Devices -->
            <div class="sidebar" id="mainSidebar">

                <!-- Menu Content -->
                <div class="sidebar-content" id="sidebarContent"
                    style="display: {{ request()->is('/') ? 'block' : 'none' }};">
                    @foreach ($categories as $category)
                        @if ($category->show_on_navbar == 1)
                            @php
                                $subcategories = DB::table('subcategories')->where('category_id', $category->id)->get();
                            @endphp
                            <div class="menu-item">
                                <div class="menu-button" data-toggle="submenu-{{ $category->id }}">
                                    <span class="menu-icon">
                                        @if ($category->name == 'Fruits & Vegetables')
                                            <i class="bi bi-apple"></i>
                                        @elseif ($category->name == 'Meats & Seafood')
                                            <i class="bi bi-fish"></i>
                                        @elseif ($category->name == 'Breakfast & Dairy')
                                            <i class="bi bi-egg"></i>
                                        @elseif ($category->name == 'Beverages')
                                            <i class="bi bi-cup"></i>
                                        @elseif ($category->name == 'Breads & Bakery')
                                            <i class="bi bi-cake"></i>
                                        @elseif ($category->name == 'Frozen Foods')
                                            <i class="bi bi-snow"></i>
                                        @elseif ($category->name == 'Biscuits & Snacks')
                                            <i class="bi bi-cookie"></i>
                                        @elseif ($category->name == 'Grocery & Staples')
                                            <i class="bi bi-basket"></i>
                                        @else
                                            <i class="bi bi-box"></i>
                                        @endif
                                    </span>
                                    <span>{{ $category->name }}</span>
                                    @if (count($subcategories) > 0)
                                        <i class="bi bi-chevron-right menu-arrow"></i>
                                    @endif
                                </div>
                                @if (count($subcategories) > 0)
                                    <div class="submenu" id="submenu-{{ $category->id }}">
                                        @foreach ($subcategories as $subcategory)
                                            @php
                                                $childcategories = DB::table('child_categories')
                                                    ->where('subcategory_id', $subcategory->id)
                                                    ->get();
                                            @endphp
                                            <div class="submenu-item">
                                                @if (count($childcategories) > 0)
                                                    <div class="submenu-button"
                                                        data-toggle="childmenu-{{ $subcategory->id }}">
                                                        <a href="{{ url('shop') }}?category={{ $category->slug }}&subcategory_id={{ $subcategory->id }}"
                                                            class="submenu-link">
                                                            {{ $subcategory->name }} <i
                                                                class="bi bi-chevron-right submenu-arrow"
                                                                style="float: right"></i>
                                                        </a>

                                                    </div>
                                                    <div class="child-submenu" id="childmenu-{{ $subcategory->id }}">
                                                        @foreach ($childcategories as $childcategory)
                                                            <div class="child-submenu-item">
                                                                <a href="{{ url('shop') }}?category={{ $category->slug }}&subcategory_id={{ $subcategory->id }}&childcategory_id={{ $childcategory->id }}"
                                                                    class="child-submenu-link">
                                                                    <i class="bi bi-dash-lg"></i>
                                                                    {{ $childcategory->name }}
                                                                </a>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <a href="{{ url('shop') }}?category={{ $category->slug }}&subcategory_id={{ $subcategory->id }}"
                                                        class="submenu-link">
                                                        {{ $subcategory->name }}
                                                    </a>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Toggle main category sidebar
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarContent = document.getElementById('sidebarContent');

        if (sidebarToggle && sidebarContent) {
            sidebarToggle.addEventListener('click', function() {
                sidebarContent.style.display = sidebarContent.style.display === 'none' ? 'block' :
                    'none';
            });
        }

        // Toggle submenu functionality
        document.querySelectorAll('[data-toggle^="submenu-"]').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-toggle');
                const submenu = document.getElementById(targetId);
                if (submenu) {
                    submenu.style.display = submenu.style.display === 'block' ? 'none' :
                        'block';
                }
            });
        });

        // Toggle child submenu functionality
        document.querySelectorAll('[data-toggle^="childmenu-"]').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-toggle');
                const childmenu = document.getElementById(targetId);
                if (childmenu) {
                    childmenu.style.display = childmenu.style.display === 'block' ? 'none' :
                        'block';
                }
            });
        });
    });
</script>

<aside class="app-sidebar sticky" id="sidebar">
    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        <a href="{{ route('dashboard') }}" class="header-logo">
            <img src="{{ asset($settings ? $settings->logo_light : '') }}" alt="logo" class="desktop-logo">
            <img src="{{ asset($settings ? $settings->logo_dark : '') }}" alt="logo" class="toggle-logo">
            <img src="{{ asset($settings ? $settings->logo_light : '') }}" alt="logo" class="desktop-dark">
            <img src="{{ asset($settings ? $settings->logo_light : '') }}" alt="logo" class="toggle-dark">
            <img src="{{ asset($settings ? $settings->logo_dark : '') }}" alt="logo" class="desktop-white">
            <img src="{{ asset($settings ? $settings->logo_dark : '') }}" alt="logo" class="toggle-white">
        </a>
    </div>
    <!-- End::main-sidebar-header -->

    <!-- Start::main-sidebar -->
    <div class="main-sidebar" id="sidebar-scroll">

        <!-- Start::nav -->
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path> </svg>
            </div>

            <ul class="main-menu">

                <li class="slide__category"><span class="category-name">Main</span></li>

                @can('view dashboard')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="{{ route('dashboard') }}"
                        class="side-menu__item {{ Request::is('dashboard') ? 'active' : '' }}">
                        <i class="bx bxs-dashboard side-menu__icon"></i>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
                </li>
                @endcan


                <li class="slide__category"><span class="category-name">Inventory</span></li>
                <!-- Proudct -->
                @can('view products')
                <li class="slide">
                    <a href="{{ route('products.index') }}" class="side-menu__item {{ Request::is('products*') && !Request::is('products/create') ? 'active' : '' }}">
                        <i class="bx bx-cube side-menu__icon"></i>
                        <span class="side-menu__label">Products List</span>
                    </a>
                </li>
                @endcan
                @can('create products')
                <li class="slide">
                    <a href="{{ route('products.create') }}" class="side-menu__item {{ Request::is('products/create') ? 'active' : '' }}">
                        <i class="bx bxs-duplicate side-menu__icon"></i>
                        <span class="side-menu__label">Create Product</span>
                    </a>
                </li>
                @endcan
                @can('view settings')
                <li class="slide">
                    <a href="{{ route('expired-products.index') }}" class="side-menu__item {{ Request::is('expired-products*') ? 'active' : '' }}">
                        <i class="bx bx-timer side-menu__icon"></i>
                        <span class="side-menu__label">Expired Products</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <li class="slide">
                    <a href="{{ route('low-stocks.index') }}" class="side-menu__item {{ Request::is('low-stocks*') ? 'active' : '' }}">
                        <i class="bx bx-trending-up side-menu__icon"></i>
                        <span class="side-menu__label">Low Stocks</span>
                    </a>
                </li>
                @endcan

                @can('view categories')
                <li class="slide">
                    <a href="{{ route('categories.index') }}" class="side-menu__item {{ Request::is('categories*') ? 'active' : '' }}">
                        <i class="bx bx-slider-alt side-menu__icon"></i>
                        <span class="side-menu__label">Category List</span>
                    </a>
                </li>
                @endcan

                @can('view brands')
                <li class="slide">
                    <a href="{{ route('brands.index') }}" class="side-menu__item {{ Request::is('brands*') ? 'active' : '' }}">
                        <i class="bx bx-leaf side-menu__icon"></i>
                        <span class="side-menu__label">Brands List</span>
                    </a>
                </li>
                @endcan

                @can('view tags')
                <li class="slide">
                    <a href="{{ route('tags.index') }}" class="side-menu__item {{ Request::is('tags*') ? 'active' : '' }}">
                        <i class="bx bx-purchase-tag side-menu__icon"></i>
                        <span class="side-menu__label">Tag List</span>
                    </a>
                </li>
                @endcan

                @can('view units')
                <li class="slide">
                    <a href="{{ route('units.index') }}" class="side-menu__item {{ Request::is('units*') ? 'active' : '' }}">
                        <i class="bx bxl-unity side-menu__icon"></i>
                        <span class="side-menu__label">Units</span>
                    </a>
                </li>
                @endcan

                @can('view attributes')
                <li class="slide">
                    <a href="{{ route('attributes.index') }}" class="side-menu__item {{ Request::is('attributes*') ? 'active' : '' }}">
                        <i class="bx bx-paste side-menu__icon"></i>
                        <span class="side-menu__label">Variant Attributes</span>
                    </a>
                </li>
                @endcan

                @can('view warranties')
                <li class="slide">
                    <a href="{{ route('warranties.index') }}" class="side-menu__item {{ Request::is('warranties*') ? 'active' : '' }}">
                        <i class="bx bx-alarm-exclamation side-menu__icon"></i>
                        <span class="side-menu__label">Warranties</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <li class="slide">
                    <a href="{{ route('label-print.index') }}" class="side-menu__item {{ Request::is('label-print*') ? 'active' : '' }}">
                        <i class="bx bx-barcode-reader side-menu__icon"></i>
                        <span class="side-menu__label">Label Print</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <li class="slide__category"><span class="category-name">Stock</span></li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="{{ route('stock-manage.index') }}" class="side-menu__item {{ Request::is('stock-manage*') ? 'active' : '' }}">
                        <i class="bx bx-layer side-menu__icon"></i>
                        <span class="side-menu__label">Manage Stock</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="{{ route('stock-adjustment.index') }}" class="side-menu__item {{ Request::is('stock-adjustment*') ? 'active' : '' }}">
                        <i class="bx bx-equalizer side-menu__icon"></i>
                        <span class="side-menu__label">Stock Adjustment</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="{{ route('stock-transfer.index') }}" class="side-menu__item {{ Request::is('stock-transfer*') ? 'active' : '' }}">
                        <i class="bx bx-transfer side-menu__icon"></i>
                        <span class="side-menu__label">Stock Transfer</span>
                    </a>
                </li>
                @endcan


                <li class="slide__category"><span class="category-name">Sales</span></li>
                @can('view orders')
                <li class="slide">
                    <a href="{{ route('orders.index') }}"
                        class="side-menu__item {{ Request::is('orders*') ? 'active' : '' }}">
                        <i class="bx bx-shopping-bag side-menu__icon"></i>
                        <span class="side-menu__label">Online Orders</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bxs-notepad side-menu__icon"></i>
                        <span class="side-menu__label">Invoices</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bx-trending-down side-menu__icon"></i>
                        <span class="side-menu__label">Sales Return</span>
                    </a>
                </li>
                @endcan

                @can('view sale requisition')
                <li class="slide">
                    <a href="{{ route('sale-requisitions.index') }}"
                        class="side-menu__item {{ Request::is('sale-requisitions*') ? 'active' : '' }}">
                        <i class="bx bx-paste side-menu__icon"></i>
                        <span class="side-menu__label">Quotation</span>
                    </a>
                </li>
                @endcan

                @can('view sale approve')
                <li class="slide">
                    <a href="{{ route('sale-approve.index') }}"
                        class="side-menu__item {{ Request::is('sale-approve*') ? 'active' : '' }}">
                        <i class="bx bx-badge-check side-menu__icon"></i>
                        <span class="side-menu__label">Sale Approve</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bx-laptop side-menu__icon"></i>
                        <span class="side-menu__label">POS</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <li class="slide__category"><span class="category-name">Purchases</span></li>
                @endcan
                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bx-shopping-bag side-menu__icon"></i>
                        <span class="side-menu__label">Purchases</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bxs-shopping-bags side-menu__icon"></i>
                        <span class="side-menu__label">Purchases Order</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bxs-share side-menu__icon"></i>
                        <span class="side-menu__label">Purchase Return</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <li class="slide__category"><span class="category-name">Finance & Accounts</span></li>
                @endcan
                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bxs-credit-card side-menu__icon"></i>
                        <span class="side-menu__label">Expenses</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bx-money-withdraw side-menu__icon"></i>
                        <span class="side-menu__label">Income</span>
                    </a>
                </li>
                @endcan

                <li class="slide__category"><span class="category-name">Peoples</span></li>
                @can('view customers')
                <li class="slide">
                    <a href="{{ route('customers.index') }}"
                        class="side-menu__item {{ Request::is('customers*') ? 'active' : '' }}">
                        <i class="bx bxs-user-account side-menu__icon"></i>
                        <span class="side-menu__label">Customers</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bx-user-check side-menu__icon"></i>
                        <span class="side-menu__label">Suppliers</span>
                    </a>
                </li>
                @endcan

                @can('view stores')
                <li class="slide">
                    <a href="{{ route('stores.index') }}"
                        class="side-menu__item {{ Request::is('stores*') ? 'active' : '' }}">
                        <i class="bx bx-store side-menu__icon"></i>
                        <span class="side-menu__label">Stores</span>
                    </a>
                </li>
                @endcan

                <li class="slide__category"><span class="category-name">HRM</span></li>
                @can('view employees')
                <li class="slide">
                    <a href="{{ route('employees.index') }}"
                        class="side-menu__item {{ Request::is('employees*') ? 'active' : '' }}">
                        <i class="bx bx-group side-menu__icon"></i>
                        <span class="side-menu__label">Employees</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bx-compass side-menu__icon"></i>
                        <span class="side-menu__label">Departments</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bx-git-merge side-menu__icon"></i>
                        <span class="side-menu__label">Designation</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bx-shuffle side-menu__icon"></i>
                        <span class="side-menu__label">Shifts</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bx-time-five side-menu__icon"></i>
                        <span class="side-menu__label">Attendence</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bx-calendar side-menu__icon"></i>
                        <span class="side-menu__label">Leaves</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bx-calendar-heart side-menu__icon"></i>
                        <span class="side-menu__label">Holidays</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide has-sub">
                    <a href="javascript:void(0);" class="side-menu__item ">
                        <i class="bx bx-dollar-circle side-menu__icon"></i>
                        <span class="side-menu__label">Payroll</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="#" class="side-menu__item ">
                                Employee Salary
                            </a>
                        </li>
                        <li class="slide">
                            <a href="#" class="side-menu__item ">
                                Payslip
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                <li class="slide__category"><span class="category-name">Service</span></li>

                {{-- @can('view service tickets') --}}
                <li class="slide">
                    <a href="{{ route('service-tickets.index') }}"
                    class="side-menu__item {{ Request::is('service-tickets*') ? 'active' : '' }}">
                        <i class="ri-customer-service-2-line side-menu__icon"></i>
                        <span class="side-menu__label">Service tickets</span>
                    </a>
                </li>

                <li class="slide">
                    <a href="{{ route('service-tickets.create') }}"
                    class="side-menu__item {{ Request::is('service-tickets/create') ? 'active' : '' }}">
                        <i class="ri-add-circle-line side-menu__icon"></i>
                        <span class="side-menu__label">Add ticket</span>
                    </a>
                </li>
                {{-- @endcan --}}


                @can('view settings')
                <li class="slide__category"><span class="category-name">Reports</span></li>
                @endcan
                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bx-bar-chart-alt-2 side-menu__icon"></i>
                        <span class="side-menu__label">Sales Report</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bx-task side-menu__icon"></i>
                        <span class="side-menu__label">Purchase Report</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bx-line-chart side-menu__icon"></i>
                        <span class="side-menu__label">Inventory Report</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bx-pie-chart-alt side-menu__icon"></i>
                        <span class="side-menu__label">Supplier Report</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bxs-doughnut-chart side-menu__icon"></i>
                        <span class="side-menu__label">Profit & Loss</span>
                    </a>
                </li>
                @endcan

                @can('view seo')
                <li class="slide__category"><span class="category-name">Content (CMS)</span></li>
                @endcan

                <li class="slide">
                    <a href="{{ route('home-slides.index') }}" class="side-menu__item {{ Request::is('home-slides*') ? 'active' : '' }}">
                        <i class="bx bx-images side-menu__icon"></i>
                        <span class="side-menu__label">Home Slides</span>
                    </a>
                </li>

                <li class="slide">
                    <a href="{{ route('promotion-banners.index') }}" class="side-menu__item {{ Request::is('promotion-banners*') ? 'active' : '' }}">
                        <i class="bx bx-collection side-menu__icon"></i>
                        <span class="side-menu__label">Promotion Banners</span>
                    </a>
                </li>

                <li class="slide">
                    <a href="{{ route('page-builder.admin.pages.index') }}" class="side-menu__item {{ Request::is('page-builder/pages*') ? 'active' : '' }}">
                        <i class="bx bx-window-alt side-menu__icon"></i>
                        <span class="side-menu__label">Pages</span>
                    </a>
                </li>

                @can('view seo')
                <li class="slide">
                    <a href="{{ route('settings.seo.index') }}" class="side-menu__item {{ Request::is('seo-pages*') ? 'active' : '' }}">
                        <i class="bx bx-layer-plus side-menu__icon"></i>
                        <span class="side-menu__label">Seo Pages</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="{{ route('blogs.index') }}" class="side-menu__item {{ Request::is('blogs*') ? 'active' : '' }}">
                        <i class="bx bx-layout side-menu__icon"></i>
                        <span class="side-menu__label">Blog</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide has-sub">
                    <a href="javascript:void(0);" class="side-menu__item ">
                        <i class="bx bx-world side-menu__icon"></i>
                        <span class="side-menu__label">Location</span>
                        <i class="fe fe-chevron-right side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1">
                        <li class="slide">
                            <a href="#" class="side-menu__item ">
                                Countries
                            </a>
                        </li>
                        <li class="slide">
                            <a href="#" class="side-menu__item ">
                                Cities
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                <li class="slide__category"><span class="category-name">User Management</span></li>

                @can('view users')
                <li class="slide">
                    <a href="{{ route('users.index') }}" class="side-menu__item {{ Request::is('users*') ? 'active' : '' }}">
                        <i class="bx bx-check-shield side-menu__icon"></i>
                        <span class="side-menu__label">Users</span>
                    </a>
                </li>
                @endcan

                @can('view roles')
                <li class="slide">
                    <a href="{{ route('roles.index') }}" class="side-menu__item {{ Request::is('roles*') ? 'active' : '' }}">
                        <i class="bx bx-fingerprint side-menu__icon"></i>
                        <span class="side-menu__label">Roles & Permissions</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bx-user-x side-menu__icon"></i>
                        <span class="side-menu__label">Delete Account Request</span>
                    </a>
                </li>
                @endcan

                <li class="slide__category"><span class="category-name">Settings</span></li>

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="{{ route('setting.index') }}" class="side-menu__item {{ Request::is('setting*') ? 'active' : '' }}">
                        <i class="bx bx-cog side-menu__icon"></i>
                        <span class="side-menu__label">General Settings</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bx-globe side-menu__icon"></i>
                        <span class="side-menu__label">Website Settings</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bx-tv side-menu__icon"></i>
                        <span class="side-menu__label">System Settingss</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bx-dollar-circle side-menu__icon"></i>
                        <span class="side-menu__label">Financial Settings</span>
                    </a>
                </li>
                @endcan

                @can('view settings')
                <!-- Dashboard - Always visible -->
                <li class="slide">
                    <a href="#" class="side-menu__item">
                        <i class="bx bx-category-alt side-menu__icon"></i>
                        <span class="side-menu__label">Other Settings</span>
                    </a>
                </li>
                @endcan

                <!-- Developer API -->
                @can('view developer api')
                <li class="slide">
                    <a href="{{ route('developer-api.index') }}"
                        class="side-menu__item {{ Request::is('developer-api*') ? 'active' : '' }}">
                        <i class="bx bx-code-alt side-menu__icon"></i>
                        <span class="side-menu__label">Developer Api</span>
                    </a>
                </li>
                @endcan



            </ul>
            <div class="slide-right" id="slide-right">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                    viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                </svg>
            </div>
        </nav>
        <!-- End::nav -->

    </div>
    <!-- End::main-sidebar -->

</aside>

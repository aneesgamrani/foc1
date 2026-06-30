<div
    x-data="{
        asideOpen: window.innerWidth >= 992,
        minimized: false,
        accessOpen: {{ request()->routeIs('roles.*') || request()->routeIs('users.*') ? 'true' : 'false' }},
        reportOpen: {{ request()->routeIs('reports.*') ? 'true' : 'false' }},
        isMobile: window.innerWidth < 992,
        init() {
            window.addEventListener('resize', () => {
                this.isMobile = window.innerWidth < 992;
                if (!this.isMobile) {
                    this.$refs.aside.classList.remove('drawer-on');
                    this.$refs.backdrop.classList.remove('show');
                }
            });
        },
        toggleAside() {
            if (this.isMobile) {
                this.$refs.aside.classList.toggle('drawer-on');
                this.$refs.backdrop.classList.toggle('show');
            } else {
                this.minimized = !this.minimized;
                document.body.classList.toggle('aside-minimized', this.minimized);
            }
        },
        closeDrawer() {
            this.$refs.aside.classList.remove('drawer-on');
            this.$refs.backdrop.classList.remove('show');
        }
    }"
>
    {{-- Backdrop (mobile) --}}
    <div class="kt-aside-backdrop" x-ref="backdrop" @click="closeDrawer()"></div>

    {{-- ====================================================
         SIDEBAR
         ==================================================== --}}
    <aside class="kt-aside" x-ref="aside" id="kt_aside">

        {{-- Logo --}}
        <div class="kt-aside-logo">
            <a href="{{ route('dashboard') }}" wire:navigate class="d-flex align-items-center gap-3 text-decoration-none">
                <div class="logo-icon">
                    <img src="{{ asset('assets/foc_logo.png') }}" alt="{{ config('app.name') }}" style="width:38px;height:38px;object-fit:contain;border-radius:80%;">
                </div>
                <div>
                    <div class="brand-name">{{ config('app.name', 'FOC Portal') }}</div>
                    <div class="brand-sub">Admin Panel</div>
                </div>
            </a>
            <button class="minimize-btn" @click="minimized = !minimized; document.body.classList.toggle('aside-minimized', minimized)" title="Toggle sidebar">
                <i class="bi bi-layout-sidebar-reverse fs-13"></i>
            </button>
        </div>

        {{-- Navigation --}}
        <div class="kt-aside-nav">

            <div class="kt-menu-heading">Main Menu</div>

            <div class="kt-menu-item">
                <a href="{{ route('dashboard') }}" wire:navigate
                   class="kt-menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="bi bi-speedometer2"></i></span>
                    <span class="menu-title">Dashboard</span>
                </a>
            </div>

            <div class="kt-menu-item">
                <a href="{{ route('profile.index') }}" wire:navigate
                   class="kt-menu-link {{ request()->routeIs('profile*') ? 'active' : '' }}">
                    <span class="menu-icon"><i class="bi bi-person-circle"></i></span>
                    <span class="menu-title">My Profile</span>
                </a>
            </div>

            @canany(['role-list', 'user-list'])
                <div class="kt-menu-heading">Administration</div>

                <div class="kt-menu-item">
                    <button type="button"
                            class="kt-menu-link {{ request()->routeIs('roles.*') || request()->routeIs('users.*') ? 'open' : '' }}"
                            @click="accessOpen = !accessOpen">
                        <span class="menu-icon"><i class="bi bi-shield-lock"></i></span>
                        <span class="menu-title">Access Control</span>
                        <i class="bi bi-chevron-right menu-arrow" :style="accessOpen ? 'transform:rotate(90deg)' : ''"></i>
                    </button>

                    <div class="kt-menu-sub" x-show="accessOpen" x-cloak>
                        @can('role-list')
                            <div class="kt-menu-item">
                                <a href="{{ route('roles.index') }}" wire:navigate
                                   class="kt-menu-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                    <span class="menu-icon"><i class="bi bi-diagram-3"></i></span>
                                    <span class="menu-title">Roles</span>
                                </a>
                            </div>
                        @endcan

                        @can('user-list')
                            <div class="kt-menu-item">
                                <a href="{{ route('users.index') }}" wire:navigate
                                   class="kt-menu-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                    <span class="menu-icon"><i class="bi bi-people"></i></span>
                                    <span class="menu-title">Users</span>
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>
            @endcanany

            {{-- Reporting --}}
            @can('report-list')
            <div class="kt-menu-heading">Reporting</div>

            <div class="kt-menu-item">
                <button type="button"
                        class="kt-menu-link {{ request()->routeIs('reports.*') ? 'open' : '' }}"
                        @click="reportOpen = !reportOpen">
                    <span class="menu-icon"><i class="bi bi-file-earmark-text"></i></span>
                    <span class="menu-title">Reports</span>
                    <i class="bi bi-chevron-right menu-arrow" :style="reportOpen ? 'transform:rotate(90deg)' : ''"></i>
                </button>
                <div class="kt-menu-sub" x-show="reportOpen" x-cloak>
                    <div class="kt-menu-item">
                        <a href="{{ route('reports.index') }}" wire:navigate
                           class="kt-menu-link {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                            <span class="menu-icon"><i class="bi bi-list-ul"></i></span>
                            <span class="menu-title">All Reports</span>
                        </a>
                    </div>
                    @can('report-create')
                    <div class="kt-menu-item">
                        <a href="{{ route('reports.index') }}" wire:navigate
                           class="kt-menu-link {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                            <span class="menu-icon"><i class="bi bi-plus-circle"></i></span>
                            <span class="menu-title">New Report</span>
                        </a>
                    </div>
                    @endcan
                </div>
            </div>
            @endcan

        </div>

        {{-- Sidebar Footer --}}
        <div class="kt-aside-footer">
            <div class="kt-aside-footer-brand">{{ config('app.name', 'FOC Portal') }}</div>
            <div class="kt-aside-footer-powered">
                Powered by <strong>OneNetwork Pvt Ltd</strong><br>IT Dte HQ-FWO
            </div>
            <div class="kt-aside-footer-year">&copy; {{ date('Y') }}</div>
        </div>

    </aside>

    {{-- ====================================================
         TOP HEADER
         ==================================================== --}}
    <header class="kt-header" id="kt_header">
        <div class="d-flex align-items-center gap-3">
            {{-- Toggle button --}}
            <button class="kt-header-btn" @click="toggleAside()" title="Toggle sidebar">
                <i class="bi bi-list fs-18"></i>
            </button>

            {{-- Page title --}}
            <div class="kt-header-title">
                <h1 class="page-title">
                    @if (request()->routeIs('dashboard'))  Dashboard
                    @elseif (request()->routeIs('profile*')) My Profile
                    @elseif (request()->routeIs('roles.*')) Roles
                    @elseif (request()->routeIs('users.*')) Users
                    @elseif (request()->routeIs('reports.*')) Reports
                    @else {{ config('app.name') }}
                    @endif
                </h1>
                <nav>
                    <ol class="kt-breadcrumb">
                        <li><a href="{{ route('dashboard') }}" wire:navigate>Home</a></li>
                        @if (!request()->routeIs('dashboard'))
                            <li class="active">
                            @if (request()->routeIs('profile*')) Profile
                                @elseif (request()->routeIs('roles.*')) Roles
                                @elseif (request()->routeIs('users.*')) Users
                                @elseif (request()->routeIs('reports.*')) Reports
                                @endif
                            </li>
                        @endif
                    </ol>
                </nav>
            </div>
        </div>

        {{-- Header right --}}
        <div class="kt-header-actions">

            {{-- Notifications (decorative) --}}
            <button class="kt-header-btn" title="Notifications">
                <i class="bi bi-bell"></i>
                <span class="badge-dot"></span>
            </button>

            {{-- Search (decorative) --}}
            <button class="kt-header-btn" title="Search">
                <i class="bi bi-search"></i>
            </button>
            <button type="button" class="kt-header-btn" id="kt_theme_toggle" title="Toggle theme">
                <i class="bi bi-sun  d-none-light" id="sun_icon"></i>
                <i class="bi bi-moon-stars  d-none-dark" id="moon_icon"></i>
            </button>
            <div style="width:1px; height:24px; background:#EFF2F5; margin:0 4px;"></div>

            {{-- User dropdown --}}
            <div class="dropdown" x-data="{ open: false }" @click.outside="open = false">
                <button class="kt-header-user" type="button" @click="open = !open" :aria-expanded="open">
                    <div class="h-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div class="d-none d-md-block text-start">
                        <div class="h-name">{{ auth()->user()->name }}</div>
                        <div class="h-role">
                            @foreach (auth()->user()->getRoleNames()->take(1) as $role){{ ucfirst($role) }}@endforeach
                        </div>
                    </div>
                    <i class="bi bi-chevron-down d-none d-md-block" style="font-size:10px; color:#A1A5B7;" :style="open ? 'transform:rotate(180deg)' : ''" x-transition></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end show" x-show="open" x-cloak
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    style="min-width:230px; display:none !important;"
                    :style="open ? 'display:block !important;' : 'display:none !important;'">
                    <li>
                        <div class="dropdown-user-header">
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                                <div>
                                    <div class="fw-semibold fs-13" style="color:#181C32;">{{ auth()->user()->name }}</div>
                                    <div class="fs-12 text-muted">{{ auth()->user()->email }}</div>
                                    <div class="mt-1 d-flex flex-wrap gap-1">
                                        @forelse (auth()->user()->getRoleNames() as $role)
                                            <span class="kt-badge kt-badge-primary kt-badge-pill" style="font-size:10px;padding:2px 7px;">{{ ucfirst($role) }}</span>
                                        @empty
                                            <span class="kt-badge kt-badge-secondary" style="font-size:10px;padding:2px 7px;">No role</span>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <a href="{{ route('profile.index') }}" wire:navigate class="dropdown-item" @click="open = false">
                            <i class="bi bi-person-gear fs-14"></i> My Profile
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <button wire:click="logout" class="dropdown-item text-danger w-100 text-start" @click="open = false">
                            <i class="bi bi-box-arrow-right fs-14"></i> Sign Out
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </header>
</div>

<div class="page-header">
    <div class="toggle-sidebar" id="toggle-sidebar"><i class="bi bi-list"></i></div>

    @if (isset($title))
        @include('components.breadcrumb', ['title' => $title ?? ''])
    @endif

    <div class="header-actions-container">

        <!-- Search container start -->
        <div class="search-container">

            <!-- Search input group start -->
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search anything">
                <button class="btn" type="button">
                    <i class="bi bi-search"></i>
                </button>
            </div>
            <!-- Search input group end -->

        </div>
        <!-- Search container end -->

        <a href="javascript:void(0);" class="leads d-none d-xl-flex" onclick="document.getElementById('mark-as-seen-form').submit();">
            @if ($newOrdersCount > 0)
                <div class="lead-details">
                    Bạn có <span class="count">{{ $newOrdersCount }}</span> đơn hàng mới
                </div>
            @endif
            <span class="lead-icon">
                <i class="bi bi-bell-fill animate__animated animate__swing animate__infinite infinite"></i>
                <b class="dot animate__animated animate__heartBeat animate__infinite"></b>
            </span>
        </a>

        <!-- Header actions start -->
        <ul class="header-actions">
            <li class="dropdown d-none d-md-block">
                <a href="#" id="countries" data-toggle="dropdown" aria-haspopup="true">
                    <img src="{{ asset('theme/admin/assets/images/flags/1x1/br.svg') }}" class="flag-img"
                        alt="Admin Panels" />
                </a>
                <div class="dropdown-menu dropdown-menu-end mini" aria-labelledby="countries">
                    <div class="country-container">
                        <a href="index.html">
                            <img src="{{ asset('theme/admin/assets/images/flags/1x1/us.svg') }}"
                                alt="Clean Admin Dashboards" />
                        </a>
                        <a href="index.html">
                            <img src="{{ asset('theme/admin/assets/images/flags/1x1/in.svg') }}"
                                alt="Google Dashboards" />
                        </a>
                        <a href="index.html">
                            <img src="{{ asset('theme/admin/assets/images/flags/1x1/gb.svg') }}"
                                alt="AI Admin Dashboards" />
                        </a>
                        <a href="index.html">
                            <img src="{{ asset('theme/admin/assets/images/flags/1x1/tr.svg') }}"
                                alt="Modern Dashboards" />
                        </a>
                        <a href="index.html">
                            <img src="{{ asset('theme/admin/assets/images/flags/1x1/ca.svg') }}"
                                alt="Best Admin Dashboards" />
                        </a>
                    </div>
                </div>
            </li>
            <li class="dropdown">
                <a href="#" id="userSettings" class="user-settings" data-toggle="dropdown" aria-haspopup="true">
                    <span class="user-name d-none d-md-block">{{ Auth::user()->name }}</span>
                    <span class="avatar">
                        @if (Auth::user()->image)
                            <img src="{{ asset('storage/' . Auth::user()->image) }}" alt="Admin Avatar"
                                class="img-thumbnail">
                        @endif
                        <span class="status online"></span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userSettings">
                    <div class="header-profile-actions">
                        <a href="{{ route('admin.profile') }}">Profile</a>
                        <a href="">Settings</a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                            style="display: inline;">
                            @csrf
                            <a href="#" class="logout-btn">Đăng Xuất</a>
                        </form>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>

@section('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        document.querySelector('.logout-btn').addEventListener('click', function(e) {
            e.preventDefault();
            const form = document.getElementById('logout-form');
            Swal.fire({
                position: 'top',
                title: 'Logout?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Có',
                cancelButtonText: 'Hủy',
                timer: 3500
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endsection

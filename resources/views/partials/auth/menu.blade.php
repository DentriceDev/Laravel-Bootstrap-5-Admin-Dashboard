<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="{{ route('dashboard') }}">
        <span class="ms-1 font-weight-bold">{{ trans('panel.site_title') }}</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">

    <div class="collapse navbar-collapse w-auto max-height-vh-100 h-100" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link {{ request()->is("dashboard") ? "active" : "" }}" href="{{ route("dashboard") }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-primary text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-fw fa-tachometer-alt"></i>
            </div>
            <span class="nav-link-text ms-1">{{ trans('global.dashboard') }}</span>
          </a>
        </li>

        @if(file_exists(app_path('Http/Controllers/Auth/ManageAccountController.php')))
            @can('profile_edit')
                <li class="nav-item">
                <a class="nav-link {{ request()->is('user/account') || request()->is('user/account/*') ? 'active' : '' }}" href="{{ route('user.account.edit') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-primary text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-fw fas fa-key c-sidebar-nav-icon"></i>
                    </div>
                    <span class="nav-link-text ms-1">{{ trans('global.my_profile') }}</span>
                </a>
                </li>
            @endcan
        @endif

        @can('user_management_access')
          <li class="nav-item mt-3">
            <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">{{ trans('cruds.userManagement.title') }}</h6>
          </li>

          @can('permission_access')
            <li class="nav-item">
              <a href="{{ route("permissions.index") }}" class="nav-link {{ request()->is("permissions") || request()->is("permissions/*") ? "active" : "" }}">
                <div class="icon icon-shape icon-sm shadow border-radius-md bg-primary text-center me-2 d-flex align-items-center justify-content-center">
                  <i class="fa-fw fas fa-unlock-alt c-sidebar-nav-icon"></i>
                </div>
                <span class="nav-link-text ms-1">{{ trans('cruds.permission.title') }}</span>
              </a>
            </li>
          @endcan

          @can('role_access')
            <li class="nav-item">
              <a href="{{ route("roles.index") }}" class="nav-link {{ request()->is("roles") || request()->is("roles/*") ? "active" : "" }}">
                <div class="icon icon-shape icon-sm shadow border-radius-md bg-primary text-center me-2 d-flex align-items-center justify-content-center">
                  <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon"></i>
                </div>
                <span class="nav-link-text ms-1">{{ trans('cruds.role.title') }}</span>
              </a>
            </li>
          @endcan

          @can('user_access')
            <li class="nav-item">
              <a href="{{ route("users.index") }}" class="nav-link {{ request()->is("users") || request()->is("users/*") ? "active" : "" }}">
                <div class="icon icon-shape icon-sm shadow border-radius-md bg-primary text-center me-2 d-flex align-items-center justify-content-center">
                  <i class="fa-fw fas fa-user c-sidebar-nav-icon"></i>
                </div>
                <span class="nav-link-text ms-1">{{ trans('cruds.user.title') }}</span>
              </a>
            </li>
          @endcan

        @endcan
      </ul>
    </div>
  </aside>

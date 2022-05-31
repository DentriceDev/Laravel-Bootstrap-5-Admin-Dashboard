<main class="main-content mt-1 border-radius-lg">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-md"><a class="opacity-5 text-dark" href="javascript:;">Pages</a>
                    </li>
                    <li class="breadcrumb-item text-sm text-dark active text-capitalize" aria-current="page">
                        {{ str_replace('.', ' >', Route::currentRouteName()) }}
                    </li>
                </ol>
                <h6 class="font-weight-bolder mb-0 text-capitalize">
                    {{ str_replace('.', ' > ', Route::currentRouteName()) }}
                </h6>
            </nav>

            <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 d-flex justify-content-end" id="navbar">

                <ul class="navbar-nav justify-content-end">
                    <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                        <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                            <div class="sidenav-toggler-inner">
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                            </div>
                        </a>
                    </li>

                    @livewire('notifications-menu')

                    <li class="nav-item dropdown ps-4 pe-2 d-flex align-items-center">
                        <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-fw fas fa-user fa-lg c-sidebar-nav-icon"></i>
                        </a>
                        <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                            @if(file_exists(app_path('Http/Controllers/Auth/ManageAccountController.php')))
                            @can('profile_edit')
                            <li>
                                <a class="dropdown-item border-radius-md {{ request()->is('user/account') || request()->is('user/account/*') ? 'active' : '' }}" href="{{ route('user.account.edit') }}">
                                    <div class="d-flex py-1">
                                        <div class="avatar avatar-sm bg-gradient-primary  me-3  my-auto">
                                            <i class="fa-fw fas fa-key c-sidebar-nav-icon"></i>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="text-sm font-weight-normal mb-1">
                                                {{ trans('global.my_profile') }}
                                            </h6>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            @endcan
                            @endif

                            <li>
                                <a class="dropdown-item border-radius-md" href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                                    <div class="d-flex py-1">
                                        <div class="avatar avatar-sm bg-gradient-primary me-3  my-auto">
                                            <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt"></i>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="text-sm font-weight-normal mb-1">
                                                {{ trans('global.logout') }}
                                            </h6>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

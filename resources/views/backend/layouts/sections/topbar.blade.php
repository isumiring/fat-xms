
<header class="main-header">
    <!-- Logo -->
    @if ($site_info['site_logo'] != '' && file_exists(upload_path('sites/'. $site_info['site_logo'])))
    <div class="page-logo">
        <a href="{{ route(backend_path('.index')) }}" class="logo">
            <img src="{{ upload_url('sites/'. $site_data['site_logo']) }}" alt="Main Logo" class="img-responsive img-sitelogo"/>
        </a>
    </div>
    <!-- /.page-logo -->
    @else
    <a href="{{ route(backend_path('.index')) }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>FAT</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>FAT</b> XMS</span>
    </a>
    @endif
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>

        <span class="app-title">CONTENT MANAGEMENT SYSTEM - {{ $site_info['site_settings']['app_header'] }}</span>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        @if (auth_user()->avatar != '' && file_exists(upload_path('users/'. auth_user()->avatar)))
                        <img src="{{ upload_url('users/tmb_'. auth_user()->avatar) }}" class="user-image" alt="{{ auth_user()->username }}">
                        <?php endif; ?>
                        <span class="hidden-xs">Hi! {{ auth_user()->name }}</span>&nbsp;
                        <i class="fa fa-user"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            @if (auth_user()->avatar != '' && file_exists(upload_path('users/'. auth_user()->avatar)))
                            <img src="{{ upload_url('users/tmb_'. auth_user()->avatar) }}" class="img-circle" alt="{{ auth_user()->username }}">
                            <?php endif; ?>
                            <p>
                                {{ auth_user()->name }}
                                <small>Member since: {{ Carbon\Carbon::parse(auth_user()->created_at)->format('F, Y') }}</small>
                                <small>Last login: {{ Carbon\Carbon::parse(auth_user()->last_login_at)->format('d-m-Y H:i') }}</small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ route(backend_path('.user.profile')) }}" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ url(backend_url('/logout')) }}" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                    <!--/.dropdown-menu-->
                </li>
                <!--/.user-menu-->
            </ul>
            <!--/.nav-->
        </div>
    </nav>
</header>
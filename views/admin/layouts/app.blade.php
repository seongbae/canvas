<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{config('app.name')}}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link rel="icon" type="image/png" href="{{ asset('img/admin-favicon.png')}}">
  <link rel="stylesheet" href="{{ URL::asset('css/vendor.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('css/admin.css') }}">

  <!-- Load styles from child views -->
  @stack('styles')

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <form class="form-inline ml-3" action="{{ route('admin.search') }}" method="POST">
      @csrf
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" name="query" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="/admin/account" aria-expanded="false">
          <img src="{{Auth::user()->photo}}" alt="user" class="rounded-circle" width="30">
          <span class="ml-2 font-medium">{{Auth::user()->name}}</span>
          <span class="fas fa-angle-down ml-2"></span>
        </a>
        
        <div class="dropdown-menu dropdown-menu-right">
          <a href="/admin/account" class="dropdown-item">
            <i class="fas fa-user mr-2"></i> My Account
          </a>
          <div class="dropdown-divider"></div>
            <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="fas fa-sign-out-alt mr-2"></i> Log out
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/admin" class="brand-link">
      <img src="{{ asset('img/canvas-c.png')}}" alt="Canvas Logo" class="brand-image"
           style="opacity: .8">
      <span class="brand-text font-weight-light">{{config('app.name')}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{Auth::user()->photo}}" class="img-circle elevation-2" alt="{{Auth::user()->name}}">
        </div>
        <div class="info">
          <a href="/admin/account" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="/admin/pages" class="nav-link {{ (request()->is('admin/pages')) ? 'active' : '' }}">
              <i class="fas fa-file-alt nav-icon"></i>
              <p>Pages</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/admin/media" class="nav-link {{ (request()->is('admin/media')) ? 'active' : '' }}">
              <i class="fas fa-images nav-icon"></i>
              <p>Media</p>
            </a>
          </li>
          @if ($moduleMenus)
            @foreach ($moduleMenus as $moduleMenu)
            @if (array_key_exists('group', $moduleMenu))
            <li class="nav-item has-treeview {{ (request()->is($moduleMenu['group']['url'])) ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ (request()->is($moduleMenu['group']['url'])) ? 'active' : '' }}">
                <i class="{{$moduleMenu['group']['icon']}} nav-icon"></i>
                <p>
                  {{$moduleMenu['index']['name']}}
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview ml-2">
                @if ($moduleMenu['index'])
                <li class="nav-item">
                  <a href="/{{$moduleMenu['index']['url']}}" class="nav-link {{ (request()->is($moduleMenu['index']['url'])) ? 'active' : '' }}">
                    <i class="{{$moduleMenu['index']['icon']}} nav-icon"></i>
                    <p>{{$moduleMenu['index']['name']}}</p>
                  </a>
                </li>
                @endif
                @if ($moduleMenu['new'])
                <li class="nav-item">
                  <a href="/{{$moduleMenu['new']['url']}}" class="nav-link {{ (request()->is($moduleMenu['new']['url'])) ? 'active' : '' }}">
                    <i class="fas fa-plus nav-icon"></i>
                    <p>{{$moduleMenu['new']['name']}}</p>
                  </a>
                </li>
                @endif
                @if (array_key_exists('related', $moduleMenu))
                <li class="nav-item">
                  <a href="/{{$moduleMenu['related']['url']}}" class="nav-link {{ (request()->is($moduleMenu['related']['url'])) ? 'active' : '' }}">
                    <i class="{{$moduleMenu['related']['icon']}} nav-icon"></i>
                    <p>{{$moduleMenu['related']['name']}}</p>
                  </a>
                </li>
                @endif
              </ul>
            </li>
            @else
            <li class="nav-item">
              <a href="/{{$moduleMenu['index']['url']}}" class="nav-link {{ (request()->is($moduleMenu['index']['url'])) ? 'active' : '' }}">
                <i class="{{$moduleMenu['index']['icon']}} nav-icon"></i>
                <p>{{$moduleMenu['index']['name']}}</p>
              </a>
            </li>
            @endif
            @endforeach
          @endif
          @if (Auth::user()->can('manage-users'))
          <li class="nav-item has-treeview {{ (request()->is('admin/users*')) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ (request()->is('admin/users*')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-friends"></i>
              <p>
                Users
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview ml-2">
              <li class="nav-item">
                <a href="/admin/users" class="nav-link {{ (request()->is('admin/users')) ? 'active' : '' }}">
                  <i class="fas fa-user-friends nav-icon"></i>
                  <p>Users</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/users/create" class="nav-link {{ (request()->is('admin/users/create')) ? 'active' : '' }}">
                  <i class="fas fa-plus nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/users/roles" class="nav-link {{ (request()->is('admin/users/roles')) ? 'active' : '' }}">
                  <i class="fas fa-user-tag nav-icon"></i>
                  <p>Roles</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview {{ (request()->is('admin/logs*')) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ (request()->is('admin/logs*')) ? 'active' : '' }}">
              <i class="nav-icon far fa-edit"></i>
              <p>
                Logs
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview ml-2">
              <li class="nav-item">
                <a href="/admin/logs/system" class="nav-link {{ (request()->is('admin/logs/system')) ? 'active' : '' }}">
                  <i class="far fa-edit nav-icon" ></i>
                  <p>System</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/logs/activity" class="nav-link {{ (request()->is('admin/logs/activity')) ? 'active' : '' }}">
                  <i class="far fa-edit nav-icon"></i>
                  <p>Activity</p>
                </a>
              </li>
            </ul>
          </li>
          @endif
          @if (Auth::user()->can('manage-site-settings'))
          <li class="nav-item">
            <a href="/admin/settings" class="nav-link {{ (request()->is('admin/settings')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-cogs"></i>
              <p>Settings</p>
            </a>
          </li>
          @endif

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="app">
    <!-- Content Header (Page header) -->
    <div class="content-header p-3">
        <!-- <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li>
            </ol>
          </div>
        </div> -->
        @if(flash()->message)
            <div class="alert {{ flash()->class }}">
                {{ flash()->message }}
            </div>
        @endif
          <div class="row justify-content-center">
              <div class="col-md-12">
                @if (count($errors) > 0)
                  @foreach ($errors->all() as $error)
                  <div class="alert alert-danger alert-dismissible fade show" id="formMessage" role="alert">
                      {{ $error }}
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  @endforeach
                @endif
              @yield('content')
              </div>
          </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; {{date("Y")}} <a href="https://www.lnidigital.com">LNI Digital Marketing</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> {{option('version')}}
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->


<!-- Tempusdominus Bootstrap 4 -->
<!-- <script src="/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script> -->

<script src="{{ mix('js/vendor.js') }}"></script>
<script src="{{ mix('js/app.js') }}"></script>
<script src="{{ asset('js/buttons.server-side.js') }}"></script>

 @stack('scripts')
</body>
</html>

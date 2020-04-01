@extends(themePath('layouts.app'), ['banner'=>'none'])
@section('content')
<div class="jumbotron">
  <div class="container">
    <div class="row">
      <div class="col-6 mx-auto col-md-6 order-md-2">
      <img src="{{ asset('img/astronaut.png')}}" class="img-fluid">
    </div>
    <div class="col-md-6 order-md-1 text-center text-md-left pr-md-5">
      <h1 class="mb-3 bd-text-purple-bright">CMS Starter Kit</h1>
      <p class="lead">
        Canvas is an open source CMS starter kit built with Laravel.  It provides a starting point for building an advanced website or your own content management system.
      </p>
      <p class="lead mb-4">
        Canvas comes with admin dashboard for managing users, roles & permissions, media items, simple pages, and system logs.  You can use Canvas to build simple websites or build more advanced websites.  It also has support for building modular components.
      </p>
      <div class="row mx-n2">
        <div class="col-md px-2">
          <a href="https://github.com/seongbae/canvas" class="btn btn-lg btn-primary w-100 mb-3" onclick="ga('send', 'event', 'Jumbotron actions', 'Get started', 'Get started');">Check out Github</a>
        </div>
        <div class="col-md px-2">
          <a href="https://www.seongbae.com/blog/canvas-cms-starter-kit" class="btn btn-lg btn-outline-secondary w-100 mb-3" onclick="ga('send', 'event', 'Jumbotron actions', 'Download', 'Download 4.4.1');">Learn More</a>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<section>
<div class="container marketing ">
  <div class="row">
  <div class="col-lg-4">
    <i class="fas fa-feather-alt fa-5x m-3"></i>
    <h2>Lightweight</h2>
    <p>Donec sed odio dui. Etiam porta sem malesuada magna mollis euismod. Nullam id dolor id nibh ultricies vehicula ut id elit. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Praesent commodo cursus magna.</p>
    </div><!-- /.col-lg-4 -->
    <div class="col-lg-4">
      <i class="fas fa-cubes fa-5x m-3"></i>
      <h2>Modular</h2>
      <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
      </div><!-- /.col-lg-4 -->
      <div class="col-lg-4">
        <i class="fas fa-wrench fa-5x m-3"></i>
        <h2>Basic Scaffolding</h2>
        <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh.</p>
        </div><!-- /.col-lg-4 -->
        
        </div><!-- /.row -->
        <!-- /END THE FEATURETTES -->
</div>
</section>
<section id="extra-features" class="extra-features bg-light">
      <div class="container">
        <header>
          <h2>Features</h2>
          <div class="row">
            <p class="lead col-lg-8 mx-auto"></p>
          </div>
        </header>
        <div class="grid row">
          <div class="item col-lg-4 col-md-6">
            <div class="icon"> <i class="fas fa-cubes "></i></div>
            <h3 class="h5">Admin Dashboard</h3>
            <p>Admin dashboard out of box integrated with Laravel</p>
          </div>
          <div class="item col-lg-4 col-md-6">
            <div class="icon"> <i class="fas fa-cubes "></i></div>
            <h3 class="h5">User Management</h3>
            <p>Create, edit, delete users.</p>
          </div>
          <div class="item col-lg-4 col-md-6">
            <div class="icon"> <i class="fas fa-cubes "></i></div>
            <h3 class="h5">User Registration</h3>
            <p>User registration, login, password reset</p>
          </div>
          <div class="item col-lg-4 col-md-6">
            <div class="icon"> <i class="fas fa-cubes "></i></div>
            <h3 class="h5">Roles & Permissions</h3>
            <p>Configure user roles and permissions to manage access to resources.</p>
          </div>
          <div class="item col-lg-4 col-md-6">
            <div class="icon"> <i class="fas fa-cubes "></i></div>
            <h3 class="h5">Module Support</h3>
            <p>Build additional features using module system.</p>
          </div>
          <div class="item col-lg-4 col-md-6">
            <div class="icon"> <i class="fas fa-cubes "></i></div>
            <h3 class="h5">Page Manager</h3>
            <p>Create and manage pages.</p>
          </div>
          <div class="item col-lg-4 col-md-6">
            <div class="icon"> <i class="fas fa-cubes "></i></div>
            <h3 class="h5">Media Manager</h3>
            <p>Upload images.</p>
          </div>
          <div class="item col-lg-4 col-md-6">
            <div class="icon"> <i class="fas fa-cubes "></i></div>
            <h3 class="h5">Logs</h3>
            <p>View user activity and system logs from admin dashboard.</p>
          </div>
        </div>
      </div>
    </section>
@endsection
<nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
{{--          @if (Auth::user()->can('manage-users'))--}}
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
{{--          @endif--}}
{{--          @if (Auth::user()->can('manage-site-settings'))--}}
{{--          <li class="nav-item">--}}
{{--            <a href="/admin/settings" class="nav-link {{ (request()->is('admin/settings')) ? 'active' : '' }}">--}}
{{--              <i class="nav-icon fas fa-cogs"></i>--}}
{{--              <p>Settings</p>--}}
{{--            </a>--}}
{{--          </li>--}}
{{--          @endif--}}

        </ul>
      </nav>
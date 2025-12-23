<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary" data-bs-theme="blue">
  <div class="sidebar-brand">
    <a href="{{ route('dashboard') }}" class="brand-link">
      <img src="{{ asset('assets/img/AdminLTELogo.png') }}" alt="Logo" class="brand-image opacity-75 shadow"/>
      <span class="brand-text fw-light">{{ config('constants.PROJECT_NAME') }}</span>
    </a>
  </div>

  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation">

        <!-- Dashboard -->
        <li class="nav-item">
          <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Contacts -->
        <li class="nav-item {{ request()->is('admin/contacts*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ request()->is('admin/contacts*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-person-lines-fill"></i>
            <p>
              Contacts
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.contacts.index') }}" class="nav-link {{ request()->routeIs('admin.contacts.index') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Contact List</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Settings -->
        <li class="nav-item {{ request()->is('admin/settings*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-gear"></i>
            <p>
              Settings
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Setting List</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.settings.create') }}" class="nav-link {{ request()->routeIs('admin.settings.create') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Add Setting</p>
              </a>
            </li>
          </ul>
        </li>


        <!-- Logout -->
        <li class="nav-item">
          <a href="{{ route('admin_logout') }}" class="nav-link">
            <i class="nav-icon bi bi-box-arrow-right"></i>
            <p>Logout</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>
<!--end::Sidebar-->

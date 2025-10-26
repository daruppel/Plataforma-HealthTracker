<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="<?= base_url('/'); ?>" class="brand-link">
    <span class="brand-text font-weight-light">HealthTracker v2</span>
  </a>

  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
        <li class="nav-item">
          <a href="<?= base_url('/dashboard'); ?>" class="nav-link active">
            <i class="nav-icon fas fa-home"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-user-md"></i>
            <p>Planes de Cuidado</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>Pacientes</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-chart-line"></i>
            <p>Estadísticas</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <section class="content pt-3">
    <div class="container-fluid">

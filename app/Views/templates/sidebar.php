<?php $rol = session()->get('role_id'); ?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="<?= base_url('/'); ?>" class="brand-link">
    <span class="brand-text font-weight-light">HealthTracker</span>
  </a>

  <div class="sidebar">
    <!-- User info -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="<?= base_url('assets/img/user-avatar.png') ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <a href="#" class="d-block">
                <?= session()->get('name'), ' ' ,session()->get('lastname'); ?><br>
                <small class="text-muted"><?= session()->get('role_desc'); ?></small>
            </a>
        </div>
    </div>
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
        <li class="nav-item">
          <a href="<?= base_url('/dashboard'); ?>" class="nav-link <?= (url_is('dashboard*') || url_is('/')) ? 'active' : '' ?>">
            <i class="nav-icon fas fa-home"></i>
            <p>Inicio</p>
          </a>
        </li>
        <!-- Menú para Administradores --> 
        <?php if ($rol === '1'): ?>
          <li class="nav-item">
            <a href="<?= base_url('/admin/users'); ?>" class="nav-link <?= url_is('admin/users*') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-user"></i>
              <p>Usuarios</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('/admin/medical-entities'); ?>" class="nav-link <?= url_is('admin/medical-entities*') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-hospital"></i>
              <p>Entidades médicas</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('/admin/medical-diagnosis'); ?>" class="nav-link <?= url_is('admin/medical-diagnosis*') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-stethoscope"></i>
              <p>Tipos de diagnosticos</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('/admin/care-plan-templates'); ?>" class="nav-link <?= url_is('admin/care-plan-templates*') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-clipboard-list"></i>
              <p>Planes Estandarizados</p>
            </a>
          </li>
        <?php endif; ?>
        <!-- Menú para Médicos -->
        <?php if ($rol === '2'): ?>
          <li class="nav-item">
            <a href="<?= base_url('/medical_staff/patients') ?>" class="nav-link <?= url_is('medical_staff/patients*') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-users"></i>
              <p>Pacientes</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('/medical_staff/diagnosis'); ?>" class="nav-link <?= url_is('medical_staff/diagnosis*') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-stethoscope"></i>
              <p>Diagnosticos</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('/medical_staff/care-plan'); ?>" class="nav-link <?= url_is('medical_staff/care-plan*') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-user-md"></i>
              <p>Planes de Cuidado</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('/medical_staff/statistics'); ?>" class="nav-link <?= url_is('medical_staff/statistics*') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>Estadísticas</p>
            </a>
          </li>
        <?php endif; ?>
        <!-- Menú para Pacientes -->
        <?php if ($rol === '3'): ?>
           <li class="nav-item">
            <a href="<?= base_url('/paciente/cumplimiento'); ?>" class="nav-link <?= url_is('paciente/cumplimiento*') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-clipboard-check"></i>
              <p>Mi Plan de Cuidado</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('/paciente/mediciones'); ?>" class="nav-link <?= url_is('paciente/mediciones*') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>Estadísticas</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('/paciente/care-plan-history'); ?>" class="nav-link <?= url_is('paciente/care-plan-history*') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-file-medical-alt"></i>
              <p>Historial de Planes</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('/paciente/documentacion'); ?>" class="nav-link <?= url_is('paciente/documentacion*') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-folder-open"></i>
              <p>Documentación Médica</p>
            </a>
          </li>
        <?php endif; ?>
         <li class="nav-item">
            <a href="#" class="nav-link text-danger" data-toggle="modal" data-target="#logoutModal">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>Cerrar sesión</p>
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

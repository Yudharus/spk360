<?php
require_once('../config/koneksi.php');

if (!isset($_SESSION[md5('user')]) || $_SESSION[md5('user')] == '') {
  echo "<script>document.location='../login.php';</script>";
} else if ($_SESSION[md5('level')] != 0) {
  echo "<script>document.location='../index.php';</script>";
}

if (isset($_GET['logout'])) {
  $_SESSION[md5('user')] = '';
  $_SESSION[md5('nama')] = '';
  $_SESSION[md5('level')] = '';
  unset($_SESSION[md5('user')]);
  unset($_SESSION[md5('nama')]);
  unset($_SESSION[md5('level')]);
  echo "<script>document.location='../login.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Sistem Pendukung Keputusan Metode 360 Degree</title>

  <!-- Custom fonts for this template-->
  <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <link href="../assets/vendor/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">

  <!-- <script src="../assets/vendor/jquery/jquery.min.js"></script> -->
  <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
  <script src="../assets/vendor/jquery/jquery.min.js" type="text/javascript"></script>
	<script src="../assets/vendor/d3-chart/d3.v5.min.js" type="text/javascript"></script>


</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php?p=home">
        <div class="sidebar-brand-text mx-3">PT. BAYER INDONESIA</div>
      </a>

      <div class="container">
        <div class="d-flex align-items-center justify-content-center">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <span class="text-uppercase mr-2 d-none d-lg-inline text-gray-600 small">
              <?= $_SESSION[md5('nama')]; ?>
            </span>
            <img class="img-profile rounded-circle" style="width: 40px; height: 40px;" src="../assets/img/user.png">
          </a>
          <!-- Dropdown - User Information -->
          <div class="dropdown-menu dropdown-menu-center shadow animated--fade-in" aria-labelledby="userDropdown">
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
              <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
              Keluar
            </a>
          </div>
        </div>
      </div>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item <?php $p = $_GET['p'];
                          if ($p == 'home') {
                            echo "active";
                          } ?>">
        <a class="nav-link" href="index.php?p=home">
          <i class="fas fa-fw fa-home"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        MASTER DATA
      </div>

      <li class="nav-item <?php $p = $_GET['p'];
                          if ($p == 'jenisalternatif') {
                            echo "active";
                          } ?>">
        <a class="nav-link" href="index.php?p=jenisalternatif">
          <i class="fas fa-fw fa-users-cog"></i>
          <span>Data Jenis Alternatif</span></a>
      </li>

      <li class="nav-item <?php $p = $_GET['p'];
                          if ($p == 'alternatif') {
                            echo "active";
                          } ?>">
        <a class="nav-link" href="index.php?p=alternatif">
          <i class="fas fa-fw fa-users"></i>
          <span>Data Alternatif</span></a>
      </li>

      <li class="nav-item <?php $p = $_GET['p'];
                          if ($p == 'kriteria') {
                            echo "active";
                          } ?>">
        <a class="nav-link" href="index.php?p=kriteria">
          <i class="fas fa-fw fa-cube"></i>
          <span>Data Kriteria</span></a>
      </li>

      <li class="nav-item <?php $p = $_GET['p'];
                          if ($p == 'subkriteria') {
                            echo "active";
                          } ?>">
        <a class="nav-link" href="index.php?p=subkriteria">
          <i class="fas fa-fw fa-cubes"></i>
          <span>Data Sub Kriteria</span></a>
      </li>

      <li class="nav-item <?php $p = $_GET['p'];
                          if ($p == 'periode') {
                            echo "active";
                          } ?>">
        <a class="nav-link" href="index.php?p=periode">
          <i class="fas fa-fw fa-calendar-alt"></i>
          <span>Data Periode</span></a>
      </li>
      
      <div class="sidebar-heading">
        PENILAIAN
      </div>

      <?php if ($_SESSION[md5('level')] == 0) : ?>
        <li class="nav-item <?php $p = $_GET['p'];
                            if ($p == 'memilihpen') {
                              echo "active";
                            } ?>">
          <a class="nav-link" href="index.php?p=memilihpen">
            <i class="fas fa-fw fa-users-cog"></i>
            <span>Data Pemilihan Penilai</span></a>
        </li>
      <?php endif; ?>

      <li class="nav-item <?php $p = $_GET['p'];
                          if ($p == 'melakukanpen') {
                            echo "active";
                          } ?>">
        <a class="nav-link" href="index.php?p=melakukanpen">
          <i class="fas fa-fw fa-edit"></i>
          <span>Data Penilaian</span></a>
      </li>

      <li class="nav-item <?php $p = $_GET['p'];
                          if ($p == 'laporanpen') {
                            echo "active";
                          } ?>">
        <a class="nav-link" href="index.php?p=laporanpen">
          <i class="fas fa-fw fa-print"></i>
          <span>Data Laporan</span></a>
      </li>


      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">


          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn text-info d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">



          </ul>

        </nav>
        <!-- End of Topbar -->

        <div class="container-fluid">

          <?php
          if (isset($_GET['p'])) {
            $dir = 'pages';
            $page = $_GET['p'] . '.php';
            $hal = scandir($dir);
            if (in_array($page, $hal)) {
              include $dir . '/' . $page;
            } else {
              include 'pages/404.php';
            }
          } else {
            include 'pages/home.php';
          }
          ?>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Yakin ingin Keluar?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Pilih "Keluar" untuk mengakhiri sesi masuk ini.</div>
        <div class="modal-footer">
          <button class="btn btn-warning" type="button" data-dismiss="modal"><i class="fas fa-fw fa-times mr-1"></i> Batal</button>
          <a class="btn btn-info" href="index.php?logout"><i class="fas fa-fw fa-sign-out-alt mr-1"></i> Keluar</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../assets/js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="../assets/vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="../assets/js/demo/chart-area-demo.js"></script>
  <script src="../assets/js/demo/chart-pie-demo.js"></script>

  <!-- Page level plugins -->
  <script src="../assets/vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="../assets/js/demo/datatables-demo.js"></script>

  <script src="../assets/vendor/bootstrap-select/bootstrap-select.min.js"></script>

  <script>
    $(function() {
      $('[data-toggle="tooltip"]').tooltip()
    })
  </script>

</body>

</html>
<?php
require_once('config/koneksi.php');


if (!isset($_SESSION[md5('user')]) || $_SESSION[md5('user')] == '') {
	echo "<script>document.location='login.php';</script>";
} else if ($_SESSION[md5('level')] == 0) {
	echo "<script>document.location='admin/index.php';</script>";
}

if (isset($_GET['logout'])) {
	$_SESSION[md5('user')] = '';
	$_SESSION[md5('nama')] = '';
	$_SESSION[md5('level')] = '';
	unset($_SESSION[md5('user')]);
	unset($_SESSION[md5('nama')]);
	unset($_SESSION[md5('level')]);
	echo "<script>document.location='login.php';</script>";
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
	<link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

	<!-- Custom styles for this template-->
	<link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

	<!-- Custom styles for this page -->
	<link href="assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
	<link href="assets/vendor/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">

	<script src="assets/vendor/jquery/jquery.min.js" type="text/javascript"></script>
	<script src="assets/vendor/d3-chart/d3.v5.min.js" type="text/javascript"></script>


</head>


<body id="page-top">

	<!-- Page Wrapper -->
	<div id="wrapper">

		<!-- Sidebar -->
		<ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion" id="accordionSidebar">

			<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php?p=home">
				<div class="sidebar-brand-text mx-3">PT. BAYER INDONESIA</div>
			</a>

			<div class="container">
				<div class="d-flex align-items-center justify-content-center">
					<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<span class="text-uppercase mr-2 d-none d-lg-inline text-gray-600 small">
							<?= $_SESSION[md5('nama')]; ?>
						</span>
						<img class="img-profile rounded-circle" style="width: 40px; height: 40px;" src="assets/img/user.png">
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

			<?php if ($_SESSION[md5('level')] == 3 || $_SESSION[md5('level')] == 2) : ?>
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

						<!-- Nav Item - Alerts -->
						<li class="nav-item dropdown no-arrow mx-1">
							<?php
							$nip_user = $_SESSION[md5('user')];
							$sql = "SELECT * FROM penilai a JOIN penilai_detail b ON a.id_penilai = b.id_penilai JOIN user d ON a.nip = d.nip WHERE b.nip = '$nip_user' AND b.id_penilai_detail NOT IN(SELECT c.id_penilai_detail FROM penilaian c WHERE c.id_penilai_detail = b.id_penilai_detail) GROUP BY a.id_penilai";
							$q = mysqli_query($con, $sql);
							$nr = mysqli_num_rows($q);

							if ($_SESSION[md5('level')] == 3 || $_SESSION[md5('level')] == 2) {
								$sql_a = "SELECT 
						  a.nip AS 'nip_dinilai',
						  d.nama_pegawai AS 'nama_dinilai',
						  b.nip AS 'nama_penilai',
						  e.nama_pegawai AS 'nama_penilai',
						  SUM(c.hasil_nilai) AS nilai
						FROM (penilai a JOIN user d ON a.nip = d.nip)
						JOIN (penilai_detail b  JOIN user e ON b.nip = e.nip) ON a.id_penilai = b.id_penilai
						LEFT JOIN penilaian c ON b.id_penilai_detail = c.id_penilai_detail
						GROUP BY a.nip, b.nip
						HAVING  ISNULL(SUM(c.hasil_nilai))";
								$q_a = mysqli_query($con, $sql_a);
								$nr_a = mysqli_num_rows($q_a);
							}

							?>
							<a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fas fa-bell fa-fw"></i>
								<!-- Counter - Alerts -->
								<?php if ($nr > 0 && isset($nr_a)) {
									$nr += $nr_a; ?>
									<span class="badge badge-info badge-counter"><?= $nr; ?></span>
								<?php } else if (isset($nr_a) && $nr_a > 0) { ?>
									<span class="badge badge-info badge-counter"><?= $nr_a; ?></span>
								<?php } else if ($nr > 0) { ?>
									<span class="badge badge-info badge-counter"><?= $nr; ?></span>
								<?php } ?>
							</a>

							<!-- Dropdown - Alerts -->
							<div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
								<?php if ($nr > 0 && isset($nr_a)) { ?>
									<h6 class="dropdown-header bg-info border border-info">
										Pemberitahuan
									</h6>
									<div style="height: 400px; overflow-y: scroll;">
										<div class="dropdown-item disabled font-weight-bold">
											Anda belum melakukan penilaian:
										</div>
										<?php
										while ($row = mysqli_fetch_array($q)) {
										?>
											<a class="dropdown-item d-flex align-items-center" href="index.php?p=melakukanpen&id=<?= $row['id_penilai']; ?>">
												<div class="mr-3">
													<div class="icon-circle bg-info">
														<i class="fas fa-user text-white"></i>
													</div>
												</div>
												<div>
													<span class="font-weight-bold"><?= $row['nama_pegawai']; ?></span>
												</div>
											</a>
										<?php
										}
										?>

										<div class="dropdown-item disabled font-weight-bold">
											Guru yang belum melakukan penilaian:
										</div>
										<?php
										while ($row = mysqli_fetch_array($q_a)) {
										?>
											<div class="dropdown-item d-flex align-items-center">
												<div class="mr-3">
													<div class="icon-circle bg-primary">
														<i class="fas fa-user text-white"></i>
													</div>
												</div>
												<div>
													<?= '<b>' . $row['nama_penilai'] . '</b> kepada <b>' . $row['nama_dinilai'] . '</b>'; ?>
												</div>
											</div>
										<?php
										}
										?>
									</div>

								<?php
								} else if ($nr > 0) {
								?>

									<h6 class="dropdown-header bg-info border border-info">
										Pemberitahuan
									</h6>
									<div style="height: 400px; overflow-y: scroll;">
										<div class="dropdown-item disabled font-weight-bold">
											Anda belum melakukan penilaian:
										</div>
										<?php
										while ($row = mysqli_fetch_array($q)) {
										?>
											<a class="dropdown-item d-flex align-items-center" href="index.php?p=melakukanpen&id=<?= $row['id_penilai']; ?>">
												<div class="mr-3">
													<div class="icon-circle bg-info">
														<i class="fas fa-user text-white"></i>
													</div>
												</div>
												<div>
													<span class="font-weight-bold"><?= $row['nama_pegawai']; ?></span>
												</div>
											</a>
										<?php
										}
										?>
									</div>
								<?php
								} else if ($nr > 0) {
								?>
									<h6 class="dropdown-header bg-info border border-info">
										Pemberitahuan
									</h6>
									<div style="height: 400px; overflow-y: scroll;">
										<div class="dropdown-item disabled font-weight-bold">
											Guru yang belum melakukan penilaian:
										</div>
										<?php
										while ($row = mysqli_fetch_array($q_a)) {
										?>
											<div class="dropdown-item d-flex align-items-center">
												<div class="mr-3">
													<div class="icon-circle bg-primary">
														<i class="fas fa-user text-white"></i>
													</div>
												</div>
												<div>
													<?= '<b>' . $row['nama_penilai'] . '</b> kepada <b>' . $row['nama_dinilai'] . '</b>'; ?>
												</div>
											</div>
										<?php
										}
										?>
									</div>
								<?php
								}
								?>
							</div>
						</li>


						<!-- <div class="topbar-divider d-none d-sm-block"></div> -->

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
	<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

	<!-- Core plugin JavaScript-->
	<script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

	<!-- Custom scripts for all pages-->
	<script src="assets/js/sb-admin-2.min.js"></script>

	<!-- Page level plugins -->
	<script src="assets/vendor/chart.js/Chart.min.js"></script>

	<!-- Page level plugins -->
	<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>
	<script src="assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

	<!-- Page level custom scripts -->
	<script src="assets/js/demo/datatables-demo.js"></script>

	<script src="assets/vendor/bootstrap-select/bootstrap-select.min.js"></script>

	<script>
		$(function() {
			$('[data-toggle="tooltip"]').tooltip()
		})
	</script>

</body>

</html>
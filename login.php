<?php
    require_once('config/koneksi.php'); 
    if(isset($_POST['nip'])){
      $nip = isset($_POST['nip'])?mysqli_real_escape_string($con,htmlspecialchars($_POST['nip'])):"";
      $password = isset($_POST['password'])?mysqli_real_escape_string($con, htmlspecialchars($_POST['password'])):"";

      $sql = "SELECT * FROM user a JOIN jenis_user b ON a.id_jenis_user = b.id_jenis_user WHERE a.nip = '$nip' AND a.password = '$password' ";
      $q = mysqli_query($con, $sql);
      $nr = mysqli_num_rows($q);
      if($nr>0){
         $row = mysqli_fetch_array($q);

         $nama = $row['nama_pegawai'];
         $level = $row['level'];

         $_SESSION["flash"]["type"] = "success";
         $_SESSION["flash"]["head"] = "Login Berhasil";
         $_SESSION["flash"]["msg"] = "Selamat Datang $nama!";
         $_SESSION[md5('user')] = $row['nip']; 
         $_SESSION[md5('nama')] = $nama; 
         $_SESSION[md5('level')] = $level;
         if($level == 0){
            echo "<script>document.location='admin/index.php?p=home';</script>";
         }else{
            echo "<script>document.location='index.php?p=home';</script>";
         }
      }else{
         $_SESSION["flash"]["type"] = "info";
         $_SESSION["flash"]["head"] = "Login Gagal";
         $_SESSION["flash"]["msg"] = "NIP/Password Salah!";
         echo "<script>document.location='login.php';</script>";
        //header("location:login.php");
      }
   }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />

        <title>Sistem Informasi Manajemen Penilaian Kinerja Pegawai PT. Bayer Indonesia</title>

        <!-- Custom fonts for this template-->
        <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />

        <!-- Custom styles for this template-->
        <link href="assets/css/sb-admin-2.min.css" rel="stylesheet" />
    </head>

    <body class="bg-gradient-info">
        <!-- <nav class="navbar navbar-expand-lg navbar-dark bg-white shadow-lg pb-3 pt-3 font-weight-bold">
            <div class="container">
                <a class="navbar-brand text-info" style="font-weight: 900;" href="index.php"> <i class="fa fa-database mr-2 rotate-n-15"></i> Sistem Pendukung Keputusan Metode 360 Degree</a>
            </div>
        </nav> -->

        <div class="container">
            <!-- Outer Row -->
            <div class="row d-plex justify-content-center mt-5">

                <div class="col-xl-5 col-lg-5 col-md-5 mt-5">
                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h1 class="h3 text-gray-900 font-weight-bold mb-2"> Sistem Informasi Manajemen Penilaian Kinerja Pegawai PT. Bayer Indonesia </h1>
                                            <h1 class="h4 text-gray-900 mb-4">Login Account</h1>
                                        </div>
										 <?php if(isset($_SESSION["flash"])){ ?>
										<div class="alert alert-<?= $_SESSION["flash"]["type"]; ?> alert-dismissible alert_model" role="alert">
										  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										  </button>
										  <?= $_SESSION["flash"]["head"]; ?>! <?= $_SESSION["flash"]["msg"]; ?>
										</div>
										<?php $_SESSION['tmp_count']+=1; } else{$_SESSION['tmp_count'] = 1;}
										  if($_SESSION['tmp_count']>2){
											unset($_SESSION['flash']);
										  }
										 ?>
                                        <form class="user" action="" method="post">
                                            <div class="form-group">
                                                <input required autocomplete="off" type="text" class="form-control form-control-user" id="exampleInputNIP" placeholder="NIP" name="nip" />
                                            </div>
                                            <div class="form-group">
                                                <input required autocomplete="off" type="password" class="form-control form-control-user" id="exampleInputPassword" name="password" placeholder="Password" />
                                            </div>
                                            <button type="submit" class="btn btn-info btn-user btn-block"><i class="fas fa-fw fa-sign-in-alt mr-1"></i> Masuk</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap core JavaScript-->
        <script src="assets/vendor/jquery/jquery.min.js"></script>
        <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="assets/js/sb-admin-2.min.js"></script>
    </body>
</html>

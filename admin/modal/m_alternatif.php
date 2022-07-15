<?php
	include '../../config/koneksi.php';
	if(isset($_POST['btnSimpan'])){
		$btn = $_POST['btnSimpan'];
		
		$nip = isset($_POST['nip'])?mysqli_real_escape_string($con,htmlspecialchars($_POST['nip'])):"";
		$id_jenis_user = isset($_POST['id_jenis_user'])?mysqli_real_escape_string($con,htmlspecialchars($_POST['id_jenis_user'])):"";
		$password = isset($_POST['password'])?mysqli_real_escape_string($con,htmlspecialchars($_POST['password'])):"";
		$nama_pegawai = isset($_POST['nama_pegawai'])?mysqli_real_escape_string($con,htmlspecialchars($_POST['nama_pegawai'])):"";
		$status_pegawai = isset($_POST['status_pegawai'])?mysqli_real_escape_string($con,htmlspecialchars($_POST['status_pegawai'])):"";
		$alamat = isset($_POST['alamat'])?mysqli_real_escape_string($con,htmlspecialchars($_POST['alamat'])):"";
		$tempat_lahir = isset($_POST['tempat_lahir'])?mysqli_real_escape_string($con,htmlspecialchars($_POST['tempat_lahir'])):"";
		$tgl_lahir = isset($_POST['tgl_lahir'])?mysqli_real_escape_string($con,htmlspecialchars($_POST['tgl_lahir'])):"";
		$jenis_kelamin = isset($_POST['jenis_kelamin'])?mysqli_real_escape_string($con,htmlspecialchars($_POST['jenis_kelamin'])):"";
		$status_nikah = isset($_POST['status_nikah'])?mysqli_real_escape_string($con,htmlspecialchars($_POST['status_nikah'])):"";
		$no_telp = isset($_POST['no_telp'])?mysqli_real_escape_string($con,htmlspecialchars($_POST['no_telp'])):"";
		$area_kerja = isset($_POST['area_kerja'])?mysqli_real_escape_string($con,htmlspecialchars($_POST['area_kerja'])):"";


		if($btn=="Tambah"){
			$sql = "INSERT INTO user (nip, id_jenis_user, password, nama_pegawai, status_pegawai, alamat, tempat_lahir, tgl_lahir, jenis_kelamin, status_nikah, no_telp, area_kerja) VALUES('$nip', '$id_jenis_user', '$password', '$nama_pegawai', '$status_pegawai', '$alamat', '$tempat_lahir', '$tgl_lahir', '$jenis_kelamin', '$status_nikah', '$no_telp', '$area_kerja') ";
		}else{
			$sql = "UPDATE user SET id_jenis_user = '$id_jenis_user', password = '$password', nama_pegawai = '$nama_pegawai', status_pegawai = '$status_pegawai', alamat = '$alamat', tempat_lahir = '$tempat_lahir', tgl_lahir = '$tgl_lahir', jenis_kelamin = '$jenis_kelamin', status_nikah = '$status_nikah', no_telp = '$no_telp', area_kerja = '$area_kerja' WHERE nip = '$nip'";
		}
		$query = mysqli_query($con, $sql);
		if($query){
			$_SESSION["flash"]["type"] = "success";
			$_SESSION["flash"]["head"] = "Sukses,";
			$_SESSION["flash"]["msg"] = "data berhasil disimpan!";
		}else{
			$_SESSION["flash"]["type"] = "danger";
			$_SESSION["flash"]["head"] = "Gagal,";
			$_SESSION["flash"]["msg"] = "data gagal disimpan! ".mysqli_error($con);
		}
		header("location:../index.php?p=alternatif");
	}

	if(isset($_POST['btnDelete'])){
		$nip = isset($_POST['id_delete'])?mysqli_real_escape_string($con, htmlspecialchars($_POST['id_delete'])):"";
		$sql = "DELETE  FROM user WHERE nip = $nip";
		$query = mysqli_query($con, $sql);
		if($query){
			$_SESSION["flash"]["type"] = "success";
			$_SESSION["flash"]["head"] = "Sukses,";
			$_SESSION["flash"]["msg"] = "data berhasil dihapus!";
		}else{
			$_SESSION["flash"]["type"] = "danger";
			$_SESSION["flash"]["head"] = "Gagal,";
			$_SESSION["flash"]["msg"] = "data gagal dihapus! ".mysqli_error($con);
		}
		header("location:../index.php?p=alternatif");
	}

	if(isset($_GET['nip'])){
		$nip = isset($_GET['nip'])?mysqli_real_escape_string($con, htmlspecialchars($_GET['nip'])):"";
		$sql = "SELECT * FROM user a JOIN jenis_user b ON a.id_jenis_user = b.id_jenis_user WHERE a.nip = $nip";
		$q = mysqli_query($con, $sql);
		$data = [];
		while ($row = mysqli_fetch_assoc($q)) {
			$data = $row; 
		}
		echo json_encode($data);
	}

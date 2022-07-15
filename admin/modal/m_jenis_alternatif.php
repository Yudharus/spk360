<?php
	include '../../config/koneksi.php';
	if(isset($_POST['btnSimpan'])){
		$btn = $_POST['btnSimpan'];
		
		$id_jenis_user = isset($_POST['id_jenis_user'])?mysqli_real_escape_string($con,htmlspecialchars($_POST['id_jenis_user'])):"";
		$jabatan = isset($_POST['jabatan'])?mysqli_real_escape_string($con,htmlspecialchars($_POST['jabatan'])):"";
		$level = isset($_POST['level'])?mysqli_real_escape_string($con,htmlspecialchars($_POST['level'])):"";

		if($btn=="Tambah"){
			$sql = "INSERT INTO jenis_user (jabatan, level) VALUES('$jabatan', '$level') ";
		}else{
			$sql = "UPDATE jenis_user SET jabatan = '$jabatan', level = '$level' WHERE id_jenis_user = '$id_jenis_user'";
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
		header("location:../index.php?p=jenisalternatif");
	}

	

?>
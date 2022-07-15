<?php
	include '../../config/koneksi.php';
	if(isset($_POST['btnSimpan'])){
		$btn = $_POST['btnSimpan'];
		
		$id_periode = isset($_POST['id_periode'])?mysqli_real_escape_string($con, htmlspecialchars($_POST['id_periode'])):"";
		$tahun_ajar = isset($_POST['tahun_ajar'])?mysqli_real_escape_string($con, htmlspecialchars($_POST['tahun_ajar'])):"";
		$semester = isset($_POST['semester'])?mysqli_real_escape_string($con, htmlspecialchars($_POST['semester'])):"";
		$status_periode = 1;

		$atasan = isset($_POST['atasan'])?mysqli_real_escape_string($con, htmlspecialchars($_POST['atasan'])):"";
		$rekan = isset($_POST['rekan'])?mysqli_real_escape_string($con, htmlspecialchars($_POST['rekan'])):"";
		$diri = isset($_POST['diri'])?mysqli_real_escape_string($con, htmlspecialchars($_POST['diri'])):"";

		$tot = $atasan+$rekan+$diri;
		echo "$tot = $atasan+$rekan+$diri";

		if($tot==100){
			
			$setting = "$atasan;$rekan;$diri";

			if($btn=="Tambah"){
				

				$ssq = "SELECT * FROM periode WHERE tahun_ajar = $tahun_ajar AND LOWER(semester) = LOWER('$semester')";
				$q = mysqli_query($con,$ssq);
				if(mysqli_num_rows($q)>0){
					$sql = "";
				}else{
					$ssq = "UPDATE periode SET status_periode = 0";
					mysqli_query($con, $ssq);
					$sql = "INSERT INTO periode (tahun_ajar, semester, status_periode, setting) VALUES( '$tahun_ajar', '$semester', '$status_periode', '$setting') ";
					
				}
			}else{
				$sql = "UPDATE periode SET tahun_ajar = '$tahun_ajar', semester = '$semester', setting='$setting' WHERE id_periode = '$id_periode'";
			}

			$query = mysqli_query($con, $sql);
			if($query){
				$_SESSION["flash"]["type"] = "success";
				$_SESSION["flash"]["head"] = "Sukses,";
				$_SESSION["flash"]["msg"] = "data berhasil disimpan!";
			}else{
				$_SESSION["flash"]["type"] = "danger";
				$_SESSION["flash"]["head"] = "Gagal,";
				$_SESSION["flash"]["msg"] = "data gagal disimpan! ";//.mysqli_error();
			}
			header("location:../index.php?p=periode");
		}else{
			
			$_SESSION["flash"]["type"] = "danger";
			$_SESSION["flash"]["head"] = "Gagal,";
			$_SESSION["flash"]["msg"] = "data gagal disimpan! ";//.mysqli_error();
			header("location:../index.php?p=periode");
		}
	}

	if(isset($_POST['btnDelete'])){
		$id_periode = isset($_POST['id_delete'])?mysqli_real_escape_string($con, htmlspecialchars($_POST['id_delete'])):"";
		$sql = "DELETE  FROM periode WHERE id_periode = $id_periode";
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
		header("location:../index.php?p=periode");
	}

?>
<?php

include '../config/koneksi.php';

// menentukan nip penilai 
if ($_POST['nip_dinilai']) {

	$nip_dinilai = $_POST['nip_dinilai'];
	$nip_penilai = $_POST['nip_penilai'];

	$sql = "SELECT * FROM penilai a JOIN penilai_detail b  ON a.id_penilai = b.id_penilai WHERE a.nip = '$nip_dinilai' AND b.nip = '$nip_penilai' ";
	$q = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($q);

	$id_penilaian_detail = $row['id_penilai_detail'];
	$sql = "SELECT * FROM penilaian WHERE id_penilai_detail = $id_penilaian_detail ";
	$q = mysqli_query($con, $sql);
	$jumlah_penilai = mysqli_num_rows($q);
	if ($jumlah_penilai > 0) {
		if (!mysqli_query($con, "DELETE FROM penilaian WHERE id_penilai_detail = $id_penilaian_detail")) {
			$_SESSION["flash"]["type"] = "danger";
			$_SESSION["flash"]["head"] = "Terjadi Kesalahan";
			$_SESSION["flash"]["msg"] = "Data gagal disimpan! ";

			header("location:../index.php?p=melakukanpen");
		}
	}



	$sql = "INSERT INTO penilaian (id_penilai_detail, id_isi, hasil_nilai) VALUES ";
	$i = 0;
	$level = $_SESSION[md5('level')];
	$hasil = 0;
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 10) == 'kompetensi') {
			//echo "$k = $v <br>";
			$id_isi = explode("_", $k)[1];
			// var_dump($id_isi);
			// die();
			if ($i == 0) {
				if ($level == 0) {
					switch ($id_isi) {
						case 26: {
								$hasil = $v * (10 / 100);
							}
							break;
						case 27: {
								$hasil = $v * (5 / 100);
							}
							break;
						case 28: {
								$hasil = $v * (5 / 100);
							}
							break;
						case 29: {
								$hasil = $v * (10 / 100);
							}
							break;
						case 30: {
								$hasil = $v * (10 / 100);
							}
							break;
						case 31: {
								$hasil = $v * (10 / 100);
							}
							break;
						case 32: {
								$hasil = $v * (20 / 100);
							}
							break;
						case 33: {
								$hasil = $v * (10 / 100);
							}
							break;
						case 34: {
								$hasil = $v * (10 / 100);
							}
							break;
						case 35: {
								$hasil = $v * (10 / 100);
							}
							break;
					}
				} else if ($level == 1 || $level == 3) {
					switch ($id_isi) {
						case 29: {
								$hasil = $v * (10 / 100);
							}
							break;
						case 30: {
								$hasil = $v * (10 / 100);
							}
							break;
						case 31: {
								$hasil = $v * (10 / 100);
							}
							break;
						case 32: {
								$hasil = $v * (20 / 100);
							}
							break;
						case 33: {
								$hasil = $v * (20 / 100);
							}
							break;
						case 34: {
								$hasil = $v * (15 / 100);
							}
							break;
						case 35: {
								$hasil = $v * (15 / 100);
							}
							break;
					}
				}
				$sql .= "($id_penilaian_detail, $id_isi, $hasil)";
			} else {
				if ($level == 0) {
					switch ($id_isi) {
						case 26: {
								$hasil = $v * (10 / 100);
							}
							break;
						case 27: {
								$hasil = $v * (5 / 100);
							}
							break;
						case 28: {
								$hasil = $v * (5 / 100);
							}
							break;
						case 29: {
								$hasil = $v * (10 / 100);
							}
							break;
						case 30: {
								$hasil = $v * (10 / 100);
							}
							break;
						case 31: {
								$hasil = $v * (10 / 100);
							}
							break;
						case 32: {
								$hasil = $v * (10 / 100);
							}
							break;
						case 33: {
								$hasil = $v * (10 / 100);
							}
							break;
						case 34: {
								$hasil = $v * (10 / 100);
							}
							break;
						case 35: {
								$hasil = $v * (10 / 100);
							}
							break;
					}
				} else if ($level == 1 || $level == 3) {
					switch ($id_isi) {
						case 29: {
								$hasil = $v * (10 / 100);
							}
							break;
						case 30: {
								$hasil = $v * (10 / 100);
							}
							break;
						case 31: {
								$hasil = $v * (10 / 100);
							}
							break;
						case 32: {
								$hasil = $v * (20 / 100);
							}
							break;
						case 33: {
								$hasil = $v * (20 / 100);
							}
							break;
						case 34: {
								$hasil = $v * (15 / 100);
							}
							break;
						case 35: {
								$hasil = $v * (15 / 100);
							}
							break;
					}
				}
				$sql .= ", ($id_penilaian_detail, $id_isi, $hasil)";
			}
			$i++;
		}
	}
	$insert = mysqli_query($con, $sql);
	if ($insert) {

		$_SESSION["flash"]["type"] = "success";
		$_SESSION["flash"]["head"] = "Sukses,";
		$_SESSION["flash"]["msg"] = "data berhasil disimpan!";
	} else {

		$_SESSION["flash"]["type"] = "danger";
		$_SESSION["flash"]["head"] = "Gagal,";
		$_SESSION["flash"]["msg"] = "data gagal disimpan! ";
	}

	header("location:../index.php?p=melakukanpen");
}

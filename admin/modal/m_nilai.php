<?php

include '../../config/koneksi.php';

if ($_POST['nip_dinilai']) {

	$nip_dinilai = $_POST['nip_dinilai'];
	$nip_penilai = $_POST['nip_penilai'];

	$sql = "SELECT * FROM penilai a JOIN penilai_detail b  ON a.id_penilai = b.id_penilai WHERE a.nip = '$nip_dinilai' AND b.nip = '$nip_penilai' ";
	$q = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($q);

	$id_penilaian_detail = $row['id_penilai_detail'];
	$id_penilai = $row['id_penilai'];
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
	$jumlah = 0;
	$sqls = "SELECT COUNT(id_penilai) as total from penilai_detail where id_penilai = $id_penilai";
	$count = mysqli_query($con,$sqls);
	while ($jumlah = mysqli_fetch_array($count)) {
		$rowspan = $jumlah['total'];
	}
	$sql_dinilai = "SELECT penilai.nip, jenis_user.level FROM `penilai` JOIN penilai_detail USING(id_penilai) JOIN user JOIN jenis_user WHERE user.nip = penilai.nip AND jenis_user.id_jenis_user = user.id_jenis_user AND penilai.id_penilai = $id_penilai GROUP BY id_penilai";
	$dinilai = mysqli_query($con, $sql_dinilai);
	while($item = mysqli_fetch_array($dinilai)){
		$level_dinilai = $item['level'];
	}
	foreach ($_POST as $k => $v) {
		if (substr($k, 0, 10) == 'kompetensi') {
			//echo "$k = $v <br>";
			$id_isi = explode("_", $k)[1];
			// var_dump($id_isi);
			// die();
			if ($i == 0) {
				if ($level < $level_dinilai) {
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
				} else if ($level == $level_dinilai) {
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
				}else if ($level > $level_dinilai) {
					switch ($id_isi) {
						case 33: {
								$hasil = $v * (40 / 100);
							}
							break;
						case 34: {
								$hasil = $v * (30 / 100);
							}
							break;
						case 35: {
								$hasil = $v * (30 / 100);
							}
							break;
					}
				}
				$sql .= "($id_penilaian_detail, $id_isi, $hasil)";
			} else {
				if ($level < $level_dinilai) {
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
				} else if ($level == $level_dinilai) {
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
				}else if ($level > $level_dinilai) {
					switch ($id_isi) {
						case 33: {
								$hasil = $v * (40 / 100);
							}
							break;
						case 34: {
								$hasil = $v * (30 / 100);
							}
							break;
						case 35: {
								$hasil = $v * (30 / 100);
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

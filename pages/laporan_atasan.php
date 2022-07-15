<?php if (!isset($_GET['detail'])) : ?>

	<div class="card shadow mb-4">

		<div class="card-body">
			<div class="d-flex justify-content-between align-items-center mt-2 mb-5 mx-1">
				<h4 class="m-0 font-weight-bold"> </i> Detail Laporan Penilaian Periode <?= get_tahun_ajar(); ?></h6>
					<!-- <a class="btn btn-primary" target="blank" href="pages/pdf.php"><i class="fa fa-print"></i> Cetak Data</a> -->
			</div>
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead class="bg-info text-white">
					<tr align="center">
						<th width="5%">No</th>
						<th>NIP</th>
						<th>Nama</th>
						<th>Total Nilai</th>
						<th width="15%">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$id_periode = get_tahun_ajar_id();
					$i = 0;
					$sql = "SELECT
								d.nip,
								d.nama_pegawai,
								SUM(a.hasil_nilai) as nilai,
								COUNT(a.id_nilai) as jml
							FROM penilaian a
							JOIN penilai_detail b ON a.id_penilai_detail = b.id_penilai_detail
							JOIN penilai c ON b.id_penilai = c.id_penilai
							JOIN user d ON c.nip = d.nip
							WHERE c.id_periode = $id_periode
							GROUP BY d.nip
							HAVING COUNT(a.id_nilai) = (
															SELECT 
															(
																(SELECT COUNT(*) FROM penilai p
																JOIN penilai_detail pd ON p.id_penilai = pd.id_penilai
																WHERE p.nip = d.nip)
																*
																(SELECT COUNT(*) FROM isi_kompetensi)
															) as tot
															FROM dual
														)
							ORDER BY nilai DESC";
					$q = mysqli_query($con, $sql);
					while ($row = mysqli_fetch_array($q)) {
					?>
						<tr align="center">
							<td><?= ++$i; ?></td>
							<td><?= $row['nip']; ?></td>
							<td><?= $row['nama_pegawai']; ?></td>
							<td><?= get_tot_nilai($row['nip'], get_tahun_ajar_id()); ?>
							</td>
							<td>
								<div class="btn-group" role="group">
									<a data-toggle="tooltip" data-placement="bottom" title="Detail Data" href="index.php?p=laporanpen&detail=<?= $row['nip'] ?>" class="btn btn-success btn-sm"><i class="fa fa-eye"></i></a>
									<!-- <a data-toggle="tooltip" data-placement="bottom" title="Cetak Data" href="pages/pdf.php?detail=<?= $row['nip'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-print"></i></a> -->
								</div>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>

<?php else :
	$nip_user = $_GET['detail'];
	$id_penilai = isset($_GET['id']) ? mysqli_real_escape_string($con, htmlspecialchars($_GET['id'])) : "";
	$sql = "SELECT * FROM user a JOIN jenis_user b ON a.id_jenis_user = b.id_jenis_user WHERE a.nip = '$nip_user' ";
	$q = mysqli_query($con, $sql);
	$row  = mysqli_fetch_array($q);
?>


	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-print"></i> Data Laporan</h1>

		<div>
			<a class="btn btn-primary" target="blank" href="pages/pdf.php?detail=<?= $row['nip'] ?>"><i class="fa fa-print"></i> Cetak Data</a>
			<a href="index.php?p=laporanpen" class="btn btn-secondary btn-icon-split"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
				<span class="text">Kembali</span>
			</a>
		</div>
	</div>

	<div class="card shadow mb-4">

		<div class="card-body">
			<div class="d-flex justify-content-start align-items-center mt-2 mb-5 mx-1">
				<h4 class="m-0 font-weight-bold">Detail Data</h6>
			</div>
			<table class="table table-bordered" width="100%">
				<tr>
					<th width="30%" class="bg-light">NIP</th>
					<td> <?= $row['nip']; ?></td>
				</tr>
				<tr>
					<th class="bg-light">Nama</th>
					<td> <?= $row['nama_pegawai']; ?></td>
				</tr>
				<tr>
					<th class="bg-light">Jabatan</th>
					<td> <?= $row['jabatan']; ?></td>
				</tr>
			</table>
		</div>
	</div>

	<?php
	$sql = "SELECT 
					a.id_kompetensi,
					a.nama_kompetensi,
					a.bobot_kompetensi,
					COUNT(b.id_isi) as jml
				FROM jenis_kompetensi a
				JOIN isi_kompetensi b ON a.id_kompetensi = b.id_kompetensi
				GROUP BY a.id_kompetensi";
	$q = mysqli_query($con, $sql);

	$data_kompetensi = [];

	while ($row = mysqli_fetch_array($q)) {
		${"b_" . $row['nama_kompetensi']} = $row['bobot_kompetensi'];
		${"jml_" . $row['nama_kompetensi']} = $row['bobot_kompetensi'];
		$data_kompetensi[] = $row;
	}
	?>
	<div class="card shadow mb-4">

		<div class="card-body">
			<div class="d-flex justify-content-start align-items-center mt-2 mb-4 mx-1">
				<h4 class="m-0 font-weight-bold"></i> Detail Laporan Penilaian Periode <?= get_tahun_ajar(); ?></h6>
			</div>
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead class="bg-info text-white">
					<tr align="center">
						<th rowspan="2" class="align-middle">No</th>
						<th rowspan="2" class="align-middle">NIP</th>
						<th rowspan="2" class="align-middle">Nama</th>
						<th rowspan="2" class="align-middle">Jabatan</th>
						<th colspan="3">Kompetensi</th>
						<th rowspan="2" class="align-middle">Total</th>
					</tr>
					<tr align="center">
						<?php
						foreach ($data_kompetensi as $key => $value) {
							echo "<th>$value[nama_kompetensi]</th>";
						}
						?>
					</tr>
				</thead>
				<tbody>
					<?php
					$sql = "SELECT * FROM penilai a JOIN penilai_detail b ON a.id_penilai = b.id_penilai WHERE a.nip = '$nip_user' ";
					$q = mysqli_query($con, $sql);
					$id_penilai_detail = '0';
					$i = 0;
					while ($row = mysqli_fetch_array($q)) {
						if ($i == 0) {
							$id_penilai_detail = $row['id_penilai_detail'];
						} else {
							$id_penilai_detail .= ", " . $row['id_penilai_detail'];
						}
						$i++;
					}
					$id_periode = get_tahun_ajar_id();
					$komp = '';
					foreach ($data_kompetensi as $key => $value) {
						$komp .= "SUM( IF(tbnilai.nama_kompetensi = '$value[nama_kompetensi]', tbnilai.nilai, 0) ) AS '$value[nama_kompetensi]', ";
					}

					$sql = "SELECT 
							tbnilai.nip_penilai,
							tbnilai.penilai,
							tbnilai.level,
							tbnilai.jabatan,
							$komp
							1
						FROM 
						(SELECT 
							a.id_nilai, 
							h.nip as nip_dinilai,
							h.nama_pegawai as 'dinilai',
							e.nip as nip_penilai, 
							e.nama_pegawai as 'penilai',
							f.jabatan,
							f.level,
							c.id_kompetensi,
							c.nama_kompetensi,
							c.bobot_kompetensi,
							SUM(a.hasil_nilai) as nilai
						FROM penilaian a 
						JOIN isi_kompetensi b ON a.id_isi = b.id_isi
						JOIN jenis_kompetensi c ON b.id_kompetensi = c.id_kompetensi
						JOIN (penilai_detail d JOIN user e ON d.nip = e.nip JOIN jenis_user f ON f.id_jenis_user = e.id_jenis_user) ON d.id_penilai_detail = a.id_penilai_detail 
						JOIN (penilai g JOIN user h ON g.nip = h.nip ) ON d.id_penilai = g.id_penilai
						WHERE a.id_penilai_detail IN ($id_penilai_detail) AND g.id_periode = $id_periode
						GROUP BY a.id_penilai_detail, c.id_kompetensi
						ORDER BY 4) as tbnilai
						GROUP BY tbnilai.penilai";

					$q = mysqli_query($con, $sql);
					$nno = 0;
					echo "<br>";
					$tot_arr['atasan'] = 0;
					$tot_arr['guru'] = 0;
					$tot_arr['sendiri'] = 0;
					while ($row = mysqli_fetch_array($q)) {
						echo "<tr align='center'>";
						echo "<td>" . ++$nno . "</td>";
						echo "<td>$row[nip_penilai]</td>";
						echo "<td>$row[penilai]</td>";
						echo "<td>$row[jabatan]</td>";

						$tot = 0;
						foreach ($data_kompetensi as $key => $value) {
							$nil = ($row[$value['nama_kompetensi']] / $value['jml']) * 1;
							echo "<td>" . number_format($nil, 2) . "</td>";
							$tot += $nil * ($value['bobot_kompetensi'] / 100);
						}

						if ($row['level'] == 2 || $row['level'] == 3) {
							$tot_arr['atasan'] += $tot;
						} else if ($row['level'] == 1 && $row['nip_penilai'] != $nip_user) {
							$tot_arr['guru'] += $tot;
						} else {
							$tot_arr['sendiri'] += $tot;
						}

						echo "<td>" . number_format($tot, 2) . "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="7">Total Nilai Kinerja</th>
						<th>
							<?php
							$sql = "SELECT * FROM periode WHERE id_periode = $id_periode";
							$q = mysqli_query($con, $sql);
							$row = mysqli_fetch_array($q);
							if ($row['setting'] != '') {
								$set = explode(";", $row['setting']);

								$set[0] = $set[0] / 100;
								$set[1] = $set[1] / 100;
								$set[2] = $set[2] / 100;
							} else {
								$set[0] = 0.5;
								$set[1] = 0.3;
								$set[2] = 0.2;
							}

							$ak = ($tot_arr['atasan'] * $set[0]) + ($tot_arr['guru'] * $set[1]) + ($tot_arr['sendiri'] * $set[2]);
							$ak = ($tot_arr['atasan'] * 0.5) + ($tot_arr['guru'] * 0.3) + ($tot_arr['sendiri'] * 0.2);
							echo number_format($ak, 2);
							?>
						</th>
					</tr>
				</tfoot>
			</table>
		<?php endif; ?>
		</div>
	</div>
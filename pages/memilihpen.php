
<?php if (isset($_SESSION["flash"])) { ?>
	<div class="alert alert-<?= $_SESSION["flash"]["type"]; ?> alert-dismissible alert_model" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<?= $_SESSION["flash"]["head"]; ?> <?= $_SESSION["flash"]["msg"]; ?>
	</div>
<?php unset($_SESSION['flash']);
} ?>

<div class="card shadow mb-4">
	<!-- /.card-header -->

	<div class="card-body">
		<div class="d-flex justify-content-between align-items-center mt-2 mb-5 mx-1">
			<h4 class="m-0 font-weight-bold"> Daftar Data Pemilihan Penilai</h6>
				<button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModalCenter"><i class="fa fa-plus"></i> Tambah Data</button>
		</div>
		<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
			<thead class="bg-info text-white">
				<tr align="center">
					<th width="5%">No</th>
					<th>Pegawai Dinilai</th>
					<th>Pegawai Penilai</th>
					<th width="15%">Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 0;
				$fi = 0;
				$id_pen = "";
				$idper = get_tahun_ajar_id();
				$sql = "SELECT a.*, b.id_penilai_detail, b.nip as 'nip_dinilai', c.nama_pegawai as 'penilai', d.nama_pegawai as 'dinilai', e.jabatan FROM penilai a JOIN penilai_detail b ON a.id_penilai = b.id_penilai  JOIN user c ON a.nip = c.nip JOIN user d ON b.nip = d.nip JOIN jenis_user e ON d.id_jenis_user = e.id_jenis_user WHERE a.id_periode = $idper ORDER BY a.nip, e.level DESC";
				$q = mysqli_query($con, $sql);
				while ($row = mysqli_fetch_array($q)) {
					if ($row['nip'] != $row['nip_dinilai']) {
						$ket = $row['jabatan'];
					} else {
						$ket = "Bawahan";
					}

					if ($fi == 0) {
						$odd = '';
						if ($i % 2 == 0) {
							$odd = 'class="tr_odd"';
						}
						$id_pen = $row['id_penilai'];
				?>

						<tr <?= $odd; ?>>
							<td rowspan="6" class="align-middle text-center"><?= ++$i; ?></td>
							<td rowspan="6" class="align-middle"><?= $row['penilai'] . '<br><small>NIP : ' . $row['nip'] . '</small>'; ?></td>
							<td><?= $row['dinilai'] . ' (' . $ket . ') <br><small>NIP : ' . $row['nip_dinilai'] . '</small>'; ?></td>
							<td rowspan="6" class="align-middle text-center">
								<div class="btn-group" role="group">
									<button data-toggle="tooltip" data-placement="bottom" title="Edit Data" class="btn btn-warning btn-sm  btn_ubah" data-id="<?= $row['id_penilai']; ?>"><i class="fa fa-edit"></i></button>
									<button data-toggle="tooltip" data-placement="bottom" title="Hapus Data" class="btn btn-danger btn-sm btn_hapus" data-id="<?= $row['id_penilai']; ?>"><i class="fa fa-trash"></i></button>
								</div>
							</td>
						</tr>
					<?php } else { ?>
						<tr <?= $odd; ?>>
							<td><?= $row['dinilai'] . ' (' . $ket . ') <br><small>NIP : ' . $row['nip_dinilai'] . '</small>'; ?></td>
						</tr>
				<?php }
					$fi++;
					if ($fi >= 6) {
						$fi = 0;
					}
				}
				?>
			</tbody>
		</table>
	</div>
</div>



<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenterTitle"><i class="fa fa-users-cog"></i> Data Pemilihan Penilai</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?php
			echo '<script>';
			$i = 0;
			$sql_guru = "SELECT * FROM user a JOIN jenis_user b ON a.id_jenis_user = b.id_jenis_user WHERE b.level = 1 AND (SELECT COUNT(*) FROM penilai c WHERE c.nip = a.nip ) = 0";
			$q = mysqli_query($con, $sql_guru);
			echo 'var data_guru = [';
			while ($row = mysqli_fetch_array($q)) {
				if ($i != 0) {
					echo ",";
				}
				echo '{ nip : "' . $row['nip'] . '", ';
				echo ' nama : "' . $row['nama_pegawai'] . '"}';

				$i++;
			}
			echo '];';

			$i = 0;
			$sql_guru = "SELECT * FROM user a JOIN jenis_user b ON a.id_jenis_user = b.id_jenis_user WHERE b.LEVEL = 1 AND (SELECT COUNT(*) FROM penilai_detail c WHERE c.nip = a.nip)<5";
			$q = mysqli_query($con, $sql_guru);
			echo 'var data_guru_pen2 = [';
			while ($row = mysqli_fetch_array($q)) {
				if ($i != 0) {
					echo ",";
				}
				echo '{ nip : "' . $row['nip'] . '", ';
				echo ' nama : "' . $row['nama_pegawai'] . '"}';

				$i++;
			}
			echo '];';
			echo '</script>';
			?>

			<form action="modal/m_penilai.php" method="post">
				<div class="modal-body">
					<input type="hidden" name="txt_id_penilai" id="txt_id_penilai" value="" />
					<input type="hidden" name="tahun_ajar" value="<?= get_tahun_ajar_id(); ?>" />
					<div class="form-group row">
						<span class="label-text col-md-4 col-form-label text-md-left">Pegawai Dinilai</span>
						<div class="col-md-8">
							<select name="penilai" id="cb_guru_penilai" class="form-control" required>

							</select>
						</div>
					</div>
					<div class="form-group row">
						<span class="label-text col-md-4 col-form-label text-md-left">Pegawai Penilai 1</span>
						<div class="col-md-8">
							<select name="guru_1" id="cb_guru_dinilai_1" class="form-control" required>

							</select>
						</div>
					</div>
					<div class="form-group row">
						<span class="label-text col-md-4 col-form-label text-md-left">Pegawai Penilai 2</span>
						<div class="col-md-8">
							<select name="guru_2" id="cb_guru_dinilai_2" class="form-control" required>

							</select>
						</div>
					</div>
					<div class="form-group row">
						<span class="label-text col-md-4 col-form-label text-md-left">Pegawai Penilai 3</span>
						<div class="col-md-8">
							<select name="guru_3" id="cb_guru_dinilai_3" class="form-control" required>

							</select>
						</div>
					</div>
				</div>
				<input type="hidden" name="btnSimpan" class="btnSimpan" value="Tambah">
				<div class="modal-footer">
					<button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
					<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade hapusdata" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xs">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-trash"></i> Hapus Data</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post" action="modal/m_penilai.php">
				<div class="modal-body">
					<input type="hidden" name="id_delete" id="id_delete">
					Apakah anda yakin ingin menghapus data ini ?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
					<button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Hapus</button>
				</div>
			</form>
		</div>
	</div>
</div>


<script type="text/javascript">
	var guru_penilaian = '';
	var guru_dinilai_1 = data_guru_pen2;
	var guru_dinilai_2 = guru_dinilai_1;
	$(document).ready(function() {

		$("#exampleModalCenter").on('hidden.bs.modal', function(event) {
			document.location = "index.php?p=memilihpen";
		});
		$("#exampleModalCenter").on('show.bs.modal', function(event) {
			guru_penilaian = '';
			data_guru.forEach(isi_guru);
			$("#cb_guru_penilai").html('');
			$("#cb_guru_penilai").append('<option value="">--Pilih Pegawai--</value>');
			$("#cb_guru_penilai").append(guru_penilaian);
		});

		$("#cb_guru_penilai").change(function() {
			var v = $(this).val();
			var ind = get_index(v);
			guru_dinilai_1.splice(ind, 1);
			guru_penilaian = '';
			guru_dinilai_1.forEach(isi_guru);
			$("#cb_guru_dinilai_1").html('');
			$("#cb_guru_dinilai_1").append('<option value="">--Pilih Pegawai--</value>');
			$("#cb_guru_dinilai_1").append(guru_penilaian);
		});


		$("#cb_guru_dinilai_1").change(function() {
			var v = $(this).val();
			var ind = get_index(v);
			guru_dinilai_2 = guru_dinilai_1;
			guru_dinilai_2.splice(ind, 1);
			guru_penilaian = '';
			guru_dinilai_2.forEach(isi_guru);
			$("#cb_guru_dinilai_2").html('');
			$("#cb_guru_dinilai_2").append('<option value="">--Pilih Pegawai--</value>');
			$("#cb_guru_dinilai_2").append(guru_penilaian);
		});


		$("#cb_guru_dinilai_2").change(function() {
			var v = $(this).val();
			var ind = get_index(v);
			var guru_dinilai_3 = guru_dinilai_2;
			guru_dinilai_3.splice(ind, 1);
			guru_penilaian = '';
			guru_dinilai_3.forEach(isi_guru);
			$("#cb_guru_dinilai_3").html('');
			$("#cb_guru_dinilai_3").append('<option value="">--Pilih Pegawai--</value>');
			$("#cb_guru_dinilai_3").append(guru_penilaian);
		});


		$(".btn_hapus").click(function() {
			var daid = $(this).attr("data-id");
			$(".hapusdata").modal("show");
			$("#id_delete").val(daid);
		});


		$(".btn_ubah").click(function() {
			var daid = $(this).attr("data-id");
			var _url = "modal/m_penilai.php?id_penilai=" + daid;
			$("#exampleModalCenter").modal("show");
			$(".btnSimpan").val("Ubah");
			$.ajax({
				url: _url,
				success: function(result) {
					var res = JSON.parse(result);

					$("#txt_id_penilai").val(res.id_penilai);

					$("#cb_guru_penilai").html("");
					$("#cb_guru_penilai").append("<option value='" + res.nip + "'>" + get_nama(res.nip, data_guru_pen2) + "</option>");
					$("#cb_guru_penilai").attr("readonly", true);
					guru_penilaian = '';

					var ind = get_index(res.nip);
					guru_dinilai_1.splice(ind, 1);
					guru_dinilai_1.forEach(isi_guru);
					$("#cb_guru_penilai_1").html("");
					$("#cb_guru_dinilai_1").append('<option value="">--Pilih Pegawai--</value>');
					$("#cb_guru_dinilai_1").append(guru_penilaian);
					$("#cb_guru_dinilai_1>option[value=" + res.penilai1 + "]").attr("selected", true);


					guru_dinilai_2 = guru_dinilai_1;
					ind = get_index(res.penilai1);
					guru_dinilai_2.splice(ind, 1);
					guru_penilaian = '';
					guru_dinilai_2.forEach(isi_guru);
					$("#cb_guru_penilai_2").html("");
					$("#cb_guru_dinilai_2").append('<option value="">--Pilih Pegawai--</value>');
					$("#cb_guru_dinilai_2").append(guru_penilaian);
					$("#cb_guru_dinilai_2>option[value=" + res.penilai2 + "]").attr("selected", true);


					var guru_dinilai_3 = guru_dinilai_2;
					ind = get_index(res.penilai2);
					guru_dinilai_3.splice(ind, 1);
					guru_penilaian = '';
					guru_dinilai_3.forEach(isi_guru);
					$("#cb_guru_penilai_2").html("");
					$("#cb_guru_dinilai_3").append('<option value="">--Pilih Pegawai--</value>');
					$("#cb_guru_dinilai_3").append(guru_penilaian);
					$("#cb_guru_dinilai_3>option[value=" + res.penilai3 + "]").attr("selected", true);
				}
			});
		});


	});

	function isi_guru(value) {
		guru_penilaian = guru_penilaian + "<option value='" + value.nip + "'>" + value.nama + "</option>";
	}

	function get_index(nip) {
		for (var i = 0; i < data_guru_pen2.length; i++) {
			if (data_guru_pen2[i].nip == nip) {
				return i;
			}
		}
		return -1;
	}

	function get_nama(nip, arr) {
		for (var i = 0; i < arr.length; i++) {
			if (arr[i].nip == nip) {
				return arr[i].nama;
			}
		}
		return "";
	}
</script>

<?php
// get data (controller)
$btn = "Tambah";
$icn = "plus";
if (isset($_GET['ubah'])) {
	$nip = isset($_GET['nip']) ? mysqli_real_escape_string($con, htmlspecialchars($_GET['nip'])) : "";
	$sql = "SELECT * FROM user a JOIN jenis_user b ON a.id_jenis_user = b.id_jenis_user WHERE a.nip = $nip";
	$q = mysqli_query($con, $sql);
	$data = [];
	while ($row = mysqli_fetch_assoc($q)) {

		$nip = $row['nip'];
		$jabatan =  $row['jabatan'];
		$$jabatan = $row['jabatan'];
		$password = $row['password'];
		$nama_pegawai = $row['nama_pegawai'];
		$status_pegawai = $row['status_pegawai'];
		$alamat = $row['alamat'];
		$tempat_lahir = $row['tempat_lahir'];
		$tgl_lahir = $row['tgl_lahir'];
		$jenis_kelamin = $row['jenis_kelamin'];
		$row['jenis_kelamin'] = $row['jenis_kelamin'];
		$status_nikah = $row['status_nikah'];
		$row['status_nikah'] = $row['status_nikah'];
		$no_telp = $row['no_telp'];
		$area_kerja = $row['area$area_kerja'];
		$btn = "Ubah";
		$icn = "edit";
	}

?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#exampleModal').modal('show');

			$('#exampleModal').on('hidden.bs.modal', function(e) {
				document.location = 'index.php?p=alternatif';
			});
		});
	</script>
<?php
}
// tampil data
?>


<!-- CRUD Tampil  -->
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
			<h4 class="m-0 font-weight-bold"> Data Alternatif</h6>
				<button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> Tambah Data</button>
		</div>
		<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
			<thead class="bg-info text-white">
				<tr align="center">
					<th width="25%">NIP</th>
					<th width="25%">Nama</th>
					<th width="25%">Jabatan</th>
					<th width="20%">Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$sql = "SELECT * FROM user a JOIN jenis_user b ON a.id_jenis_user = b.id_jenis_user";
				$q = mysqli_query($con, $sql);
				$i = 0;
				while ($row = mysqli_fetch_array($q)) {
				?>
					<tr align="center">
						<td><?= $row['nip']; ?></td>
						<td><?= $row['nama_pegawai']; ?></td>
						<td><?= $row['jabatan']; ?></td>
						
						<td>
							<div class="btn-group" role="group">
								<button data-toggle="tooltip" data-placement="bottom" title="Detail Data" class="btn btn-success btn-sm btn_info" id="<?= $row['nip']; ?>"><i class="fa fa-eye"></i></button>
								<a data-toggle="tooltip" data-placement="bottom" title="Edit Data" href="index.php?p=alternatif&ubah=true&nip=<?= $row['nip']; ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
								<button data-toggle="tooltip" data-placement="bottom" title="Hapus Data" class="btn btn-danger btn-sm btn_hapus" id="<?= $row['nip']; ?>"><i class="fa fa-trash"></i></button>
							</div>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<!-- CRUD Tampil  -->

<!-- Modal -->
<div class="modal fade" id="exampleModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-<?= $icn; ?>"></i> <?= $btn; ?> Data</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form class="form-horizontal" method="post" action="modal/m_alternatif.php">
				<div class="modal-body">
					<input type="hidden" name="btnSimpan" value="<?= $btn; ?>">
					<div class="form-group row">
						<label for="nip" class="col-sm-2 col-form-label font-weight-bold">NIP</label>
						<div class="col-sm-10">
							<input autocomplete="off" required type="text" class="form-control id=" nip" name="nip" <?= isset($nip) ? 'value="' . $nip . '" readonly' : ""; ?>>
						</div>
					</div>
					<div class="form-group row">
						<label for="nama_pegawai" class="col-sm-2 col-form-label font-weight-bold">Nama</label>
						<div class="col-sm-10">
							<input autocomplete="off" required type="text" class="form-control" id="nama_pegawai" name="nama_pegawai" value="<?= isset($nama_pegawai) ? $nama_pegawai : ""; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="password" class="col-sm-2 col-form-label font-weight-bold">Password</label>
						<div class="col-sm-10">
							<input autocomplete="off" required type="text" class="form-control" id="password" name="password" value="<?= isset($password) ? $password : ""; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label for="id_jenis_user" class="col-sm-2 col-form-label font-weight-bold">Jabatan</label>
						<div class="col-sm-10">
							<select required class="form-control" id="id_jenis_user" name="id_jenis_user">
								<?php
								$jb = mysqli_query($con, "SELECT * FROM jenis_user");
								while ($rj = mysqli_fetch_array($jb)) {
								?>
									<option value="<?= $rj['id_jenis_user'] ?>" <?= isset($rj['jabatan']) ? "selected" : '' ?>><?= $rj['jabatan']; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="status_pegawai" class="col-sm-2 control-form-label font-weight-bold">Status Pegawai</label>
						<div class="col-sm-10">
							<input autocomplete="off" required type="text" class="form-control" id="status_pegawai" name="status_pegawai" value="<?= isset($status_pegawai) ? $status_pegawai : ""; ?>">
						</div>
					</div>

					<div class="form-group row">
						<label for="alamat" class="col-sm-2 control-form-label font-weight-bold">Alamat</label>
						<div class="col-sm-10">
							<textarea required class="form-control" id="alamat" name="alamat" rows="10"><?= isset($alamat) ? $alamat : ""; ?></textarea>
						</div>
					</div>

					<div class="form-group row">
						<label for="tempat_lahir" class="col-sm-2 control-form-label font-weight-bold">Tempat Lahir</label>
						<div class="col-sm-10">
							<input autocomplete="off" required type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="<?= isset($tempat_lahir) ? $tempat_lahir : ""; ?>">
						</div>
					</div>

					<div class="form-group row">
						<label for="tgl_lahir" class="col-sm-2 control-form-label font-weight-bold">Tanggal Lahir</label>
						<div class="col-sm-10">
							<input required type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" value="<?= isset($tgl_lahir) ? $tgl_lahir : ""; ?>" placeholder="Tgl Lahir">
						</div>
					</div>

					<div class="form-group row">
						<label for="jenis_kelamin" class="col-sm-2 control-form-label font-weight-bold">Jenis Kelamin</label>
						<div class="col-sm-10">
							<input type="radio" id="jenis_kelamin_l" name="jenis_kelamin" value="L" <?= isset($L) ? "checked" : ""; ?>> Laki-laki
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" id="jenis_kelamin_p" name="jenis_kelamin" value="P" <?= isset($P) ? "checked" : ""; ?>> Perempuan
						</div>
					</div>

					<div class="form-group row">
						<label for="status_nikah" class="col-sm-2 control-form-label font-weight-bold">Status Nikah</label>
						<div class="col-sm-10">
							<input type="radio" id="status_nikah_b" name="status_nikah" value="B" <?= isset($B) ? "checked" : ""; ?>> Belum Nikah
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" id="status_nikah_n" name="status_nikah" value="N" <?= isset($N) ? "checked" : ""; ?>> Sudah Nikah
						</div>
					</div>

					<div class="form-group row">
						<label for="no_telp" class="col-sm-2 control-form-label font-weight-bold">No Telp</label>
						<div class="col-sm-10">
							<input autocomplete="off" required type="text" class="form-control" id="no_telp" name="no_telp" value="<?= isset($no_telp) ? $no_telp : ""; ?>">
						</div>
					</div>

					<div class="form-group row">
						<label for="area_kerja" class="col-sm-2 control-form-label font-weight-bold">Area Kerja</label>
						<div class="col-sm-10">
							<input autocomplete="off" required type="text" class="form-control" id="area_kerja" name="area_kerja" value="<?= isset($area_kerja) ? $area_kerja : ""; ?>">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
					<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> <?= $btn; ?></button>
				</div>
			</form>
		</div>
	</div>
</div>


<!-- Crud -->
<div class="modal fade infolengkap" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-eye"></i> Detail Data</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-bordered">
					<tr>
						<th width="30%" class="bg-light">NIP</th>
						<td id="td_nip"></td>
					</tr>

					<tr>
						<th class="bg-light">Jabatan</th>
						<td id="td_jabatan"></td>
					</tr>

					<tr>
						<th class="bg-light">Password</th>
						<td id="td_password"></td>
					</tr>

					<tr>
						<th class="bg-light">Nama Guru</th>
						<td id="td_nama_pegawai"></td>
					</tr>

					<tr>
						<th class="bg-light">Status Guru</th>
						<td id="td_status_pegawai"></td>
					</tr>

					<tr>
						<th class="bg-light">Alamat</th>
						<td id="td_alamat"></td>
					</tr>

					<tr>
						<th class="bg-light">Tempat, Tgl Lahir</th>
						<td id="td_ttl"></td>
					</tr>

					<tr>
						<th class="bg-light">Jenis Kelamin</th>
						<td id="td_jk"></td>
					</tr>

					<tr>
						<th class="bg-light">Status Nikah</th>
						<td id="td_status_nikah"></td>
					</tr>

					<tr>
						<th class="bg-light">No Telp</th>
						<td id="td_notelp"></td>
					</tr>
					<tr>
						<th class="bg-light">Area Kerja</th>
						<td id="td_area_kerja"></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
<!-- crud -->

<div class="modal fade hapusdata" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="HapusDataModal">
	<div class="modal-dialog modal-xs">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-trash"></i> Hapus Data</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post" action="modal/m_alternatif.php">
				<div class="modal-body">
					<input type="hidden" name="id_delete" id="id_delete">
					<input type="hidden" name="btnDelete" value="Hapus">
					Apakah anda yakin ingin menghapus data ini ?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
					<button type="submit" class="btn btn-info"><i class="fa fa-trash"></i> Hapus</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- ngirim data dr button  -->
<script type="text/javascript">
	$(document).ready(function() {
		$(".btn_info").click(function() {
			var id = $(this).attr("id");
			var _url = "modal/m_alternatif.php?nip=" + id;
			$.ajax({
				url: _url,
				success: function(result) {
					var res = JSON.parse(result);
					console.log(res);
					$("#td_nip").html(res.nip);
					$("#td_jabatan").html(res.jabatan);
					$("#td_password").html(res.password);
					$("#td_nama_pegawai").html(res.nama_pegawai);
					$("#td_status_pegawai").html(res.status_pegawai);
					$("#td_alamat").html(res.alamat);
					$("#td_ttl").html(res.tempat_lahir + ", " + res.tgl_lahir);
					$("#td_jk").html(res.jenis_kelamin == "L" ? "Laki-laki" : "Perempuan");
					$("#td_status_nikah").html(res.status_nikah == "B" ? "Belum Nikah" : "Sudah Nikah");
					$("#td_notelp").html(res.no_telp);
					$("#td_area_kerja").html(res.area_kerja);
				}
			});
			$('.infolengkap').modal('show');
		});
		$(".btn_hapus").click(function() {
			var id = $(this).attr("id");
			$("#id_delete").val(id);
			$('.hapusdata').modal('show');
		});
	});
</script>
<!-- ngirim data dr button  -->
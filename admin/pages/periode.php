<?php
if (isset($_GET['setaktif'])) {
	$id_periode = isset($_GET['id_periode']) ? mysqli_real_escape_string($con, htmlspecialchars($_GET['id_periode'])) : "";
	$sql = "UPDATE periode SET status_periode = 0";
	$up = mysqli_query($con, $sql);
	if ($up) {
		if (mysqli_query($con, "UPDATE periode SET status_periode = 1 WHERE id_periode = $id_periode")) {
			$_SESSION["flash"]["type"] = "success";
			$_SESSION["flash"]["head"] = "Sukses,";
			$_SESSION["flash"]["msg"] = "data berhasil disimpan!";
			echo "<script>document.location='index.php?p=periode';</script>";
		}
	}
	$_SESSION["flash"]["type"] = "danger";
	$_SESSION["flash"]["head"] = "Gagal,";
	$_SESSION["flash"]["msg"] = "data gagal disimpan! " . mysqli_error($con);
	echo "<script>document.location='index.php?p=periode';</script>";
}
?>


<?php
$btn = "Tambah";
$icn = "plus";
if (isset($_GET['ubah'])) {
	$id_periode = isset($_GET['id_periode']) ? mysqli_real_escape_string($con, htmlspecialchars($_GET['id_periode'])) : "";
	$sql = "SELECT * FROM periode WHERE id_periode = $id_periode";
	$q = mysqli_query($con, $sql);
	$data = [];
	while ($row = mysqli_fetch_assoc($q)) {
		$id_periode = $row['id_periode'];
		$tahun_ajar = $row['tahun_ajar'];
		$semester = $row['semester'];
		$btn = "Ubah";
		$icn = "edit";
		if ($row['setting'] != '') {
			$set = explode(';', $row['setting']);
			$atasan = $set[0];
			$rekan = $set[1];
			$diri = $set[2];
		}
	}

?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#exampleModal').modal('show');

			$('#exampleModal').on('hidden.bs.modal', function(e) {
				document.location = 'index.php?p=periode';
			});
		});
	</script>
<?php
}
?>


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
			<h4 class="m-0 font-weight-bold"> Daftar Data Periode</h6>
				<button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> Tambah Data</button>
		</div>
		<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
			<thead class="bg-info text-white">
				<tr align="center">
					<th width="5%">No</th>
					<th>Periode</th>
					<th>Penilaian</th>
					<th>Status</th>
					<th width="15%">Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$sql = "SELECT * FROM periode";
				$q = mysqli_query($con, $sql);
				$i = 0;
				while ($row = mysqli_fetch_array($q)) {
					$setting = '';
					if ($row['setting'] != '') {
						$set = explode(';', $row['setting']);
						$setting = "Atasan = $set[0]% <br>Rekan Kerja = $set[1]% <br>Bawahan = $set[2]%";
					}

				?>
					<tr align="center">
						<td><?= ++$i; ?></td>
						<td><?= $row['tahun_ajar']; ?></td>
						<td><?= $setting; ?></td>
						<td>
							<?php if ($row['status_periode'] == 0) { ?>
								<span class="badge badge-danger">Tidak Aktif</span>
							<?php } else { ?>
								<span class="badge badge-success">Aktif</span>
							<?php } ?>
						</td>
						<td>
							<div class="btn-group" role="group">
								<?php if ($row['status_periode'] == 0) : ?>
									<a data-toggle="tooltip" data-placement="bottom" title="Aktifkan Data" href="index.php?p=periode&setaktif=true&id_periode=<?= $row['id_periode']; ?>" class="btn btn-success btn-sm"><i class="fa fa-check"></i></a>
								<?php endif; ?>
								<a data-toggle="tooltip" data-placement="bottom" title="Edit Data" href="index.php?p=periode&ubah=true&id_periode=<?= $row['id_periode']; ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
							</div>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-<?= $icn; ?>"></i> <?= $btn; ?> Data</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form class="form-horizontal" method="post" action="modal/m_periode.php">
				<div class="modal-body">
					<input type="hidden" name="id_periode" <?= isset($id_periode) ? 'value="' . $id_periode . '"' : ""; ?>>
					<input type="hidden" name="btnSimpan" value="<?= $btn; ?>">

					<div class="form-group">
						<label class="font-weight-bold">Periode</label>
						<input autocomplete="off" type="text" name="tahun_ajar" id="tahun_ajar" value="<?= isset($tahun_ajar) ? $tahun_ajar : ""; ?>" required class="form-control" />
					</div>

					<div class="form-group">
						<label class="font-weight-bold">Presentase Atasan</label>
						<input autocomplete="off" type="number" min="0" max="100" name="atasan" id="atasan" value="<?= isset($atasan) ? $atasan : ""; ?>" required class="form-control" />
					</div>

					<div class="form-group">
						<label class="font-weight-bold">Presentase Rekan Kerja</label>
						<input autocomplete="off" type="number" min="0" max="100" name="rekan" id="rekan" value="<?= isset($rekan) ? $rekan : ""; ?>" required class="form-control" />
					</div>

					<div class="form-group">
						<label class="font-weight-bold">Presentase Bawahan</label>
						<input autocomplete="off" type="number" min="0" max="100" name="diri" id="diri" value="<?= isset($diri) ? $diri : ""; ?>" required class="form-control" />
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
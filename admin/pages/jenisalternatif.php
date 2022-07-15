<!-- crud jenis alternatif -->
<?php
$btn = "Tambah";
$icn = "plus";
if (isset($_GET['ubah'])) {
	$id_jenis_user = isset($_GET['id_jenis_user']) ? mysqli_real_escape_string($con, htmlspecialchars($_GET['id_jenis_user'])) : "";
	$sql = "SELECT * FROM jenis_user WHERE id_jenis_user = $id_jenis_user";
	$q = mysqli_query($con, $sql);
	$data = [];
	while ($row = mysqli_fetch_assoc($q)) {
		$id_jenis_user = $row['id_jenis_user'];
		$jabatan = $row['jabatan'];
		$level = $row['level'];
		$btn = "Ubah";
		$icn = "edit";
	}

?>
<!-- crud jenis alternatif -->

<!-- buka popup modal -->
	<script type="text/javascript">
		$(document).ready(function() {
			$('#exampleModal').modal('show');

			$('#exampleModal').on('hidden.bs.modal', function(e) {
				document.location = 'index.php?p=jenisalternatif';
			});
		});
	</script>
<?php
}
?>
<!-- buka popup modal -->


<!-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-users-cog"></i> Data Jenis Alternatif</h1>

	<button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> Tambah Data</button>
</div> -->

<!-- button tambah -->
<?php if (isset($_SESSION["flash"])) { ?>
	<div class="alert alert-<?= $_SESSION["flash"]["type"]; ?> alert-dismissible alert_model" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<?= $_SESSION["flash"]["head"]; ?> <?= $_SESSION["flash"]["msg"]; ?>
	</div>
<?php unset($_SESSION['flash']);
} ?>
<!-- button tambah -->

<div class="card shadow mb-4">
	<!-- /.card-header -->

	<div class="card-body">
		<div class="d-flex justify-content-between align-items-center mt-2 mb-5 mx-1">
			<h4 class="m-0 font-weight-bold"> Daftar Data Jenis Alternatif</h6>
			<button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> Tambah Data</button>
		</div>
		<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
			<thead class="bg-info text-white">
				<tr align="center">
					<th width="5%">No</th>
					<th>Jabatan</th>
					<th>Level</th>
					<th width="15%">Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$sql = "SELECT * FROM jenis_user";
				$q = mysqli_query($con, $sql);
				$i = 0;
				while ($row = mysqli_fetch_array($q)) {
				?>
					<tr align="center">
						<td><?= ++$i; ?></td>
						<td><?= $row['jabatan']; ?></td>
						<td><?= $row['level']; ?></td>
						<td>
							<a data-toggle="tooltip" data-placement="bottom" title="Edit Data" href="index.php?p=jenisalternatif&ubah=true&id_jenis_user=<?= $row['id_jenis_user']; ?>" id="<?= $row['id_jenis_user']; ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
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
			<form class="form-horizontal" method="post" action="modal/m_jenis_alternatif.php">
				<div class="modal-body">
					<input type="hidden" id="id_jenis_user" name="id_jenis_user" value="<?= isset($id_jenis_user) ? $id_jenis_user : ""; ?>">
					<input type="hidden" name="btnSimpan" value="<?= $btn; ?>">

					<div class="form-group">
						<label class="font-weight-bold">Jabatan</label>
						<input autocomplete="off" type="text" name="jabatan" id="jabatan" value="<?= isset($jabatan) ? $jabatan : ""; ?>" required class="form-control" />
					</div>

					<div class="form-group">
						<label class="font-weight-bold">Level</label>
						<input autocomplete="off" type="number" name="level" id="level" value="<?= isset($level) ? $level : ""; ?>" required class="form-control" />
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
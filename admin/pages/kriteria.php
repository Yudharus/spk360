<!--  crud -->
<?php
$btn = "Tambah";
$icn = "plus";
if (isset($_GET['ubah'])) {
	$id_kompetensi = isset($_GET['id_kompetensi']) ? mysqli_real_escape_string($con, htmlspecialchars($_GET['id_kompetensi'])) : "";
	$sql = "SELECT * FROM jenis_kompetensi WHERE id_kompetensi = $id_kompetensi";
	$q = mysqli_query($con, $sql);
	$data = [];
	while ($row = mysqli_fetch_assoc($q)) {
		$id_kompetensi = $row['id_kompetensi'];
		$nama_kompetensi = $row['nama_kompetensi'];
		$bobot_kompetensi = $row['bobot_kompetensi'];
		$btn = "Ubah";
		$icn = "edit";
	}

?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#exampleModal').modal('show');

			$('#exampleModal').on('hidden.bs.modal', function(e) {
				document.location = 'index.php?p=kriteria';
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
			<h4 class="m-0 font-weight-bold"> Daftar Data Kriteria</h6>
			<button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> Tambah Data</button>
		</div>
		<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
			<thead class="bg-info text-white">
				<tr align="center">
					<th width="5%">No</th>
					<th width="45%">Kriteria</th>
					<th width="15%">Bobot (%)</th>
					<th width="15%">Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$sql = "SELECT * FROM jenis_kompetensi";
				$q = mysqli_query($con, $sql);
				$i = 0;
				while ($row = mysqli_fetch_array($q)) {
				?>
					<tr align="center">
						<td><?= ++$i; ?></td>
						<td><?= $row['nama_kompetensi']; ?></td>
						<td><?= $row['bobot_kompetensi']; ?></td>
						<td>
							<div class="btn-group" role="group">
								<a data-toggle="tooltip" data-placement="bottom" title="Edit Data" href="index.php?p=kriteria&ubah=true&id_kompetensi=<?= $row['id_kompetensi']; ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
								<button data-toggle="tooltip" data-placement="bottom" title="Hapus Data" class="btn btn-danger btn-sm btn_hapus" id="<?= $row['id_kompetensi']; ?>"><i class="fa fa-trash"></i></button>
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
			<form class="form-horizontal" method="post" action="modal/m_kriteria.php">
				<div class="modal-body">
					<input type="hidden" id="id_kompetensi" name="id_kompetensi" <?= isset($id_kompetensi) ? 'value="' . $id_kompetensi . '" readonly' : ""; ?>>
					<input type="hidden" name="btnSimpan" value="<?= $btn; ?>">

					<div class="form-group">
						<label class="font-weight-bold">Kriteria</label>
						<input autocomplete="off" type="text" name="nama_kompetensi" id="nama_kompetensi" value="<?= isset($nama_kompetensi) ? $nama_kompetensi : ""; ?>" required class="form-control" />
					</div>

					<div class="form-group">
						<label class="font-weight-bold">Bobot</label>
						<input autocomplete="off" type="text" name="bobot_kompetensi" id="bobot_kompetensi" value="<?= isset($bobot_kompetensi) ? $bobot_kompetensi : ""; ?>" required class="form-control" />
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

<div class="modal fade hapusdata" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xs">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-trash"></i> Hapus Data</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post" action="modal/m_kriteria.php">
				<div class="modal-body">
					<input type="hidden" name="id_delete" id="id_delete">
					<input type="hidden" name="btnDelete" value="Hapus">
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
	$(document).ready(function() {
		$(".btn_hapus").click(function() {
			var id = $(this).attr("id");
			$("#id_delete").val(id);
			$('.hapusdata').modal('show');
		});

		$('#exampleModal').on('shown.bs.modal', function() {
			var _url = "modal/m_kriteria.php?sum";
			$.ajax({
				url: _url,
				success: function(result) {
					var sum = result;
					var max = 100 - sum;
					console.log(max);
					$("#bobot_kompetensi").attr("max", max);
				}
			});
		});
	});
</script>
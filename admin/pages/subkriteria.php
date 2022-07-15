<?php
$btn = "Tambah";
$icn = "plus";
if (isset($_GET['ubah'])) {
	$id_isi = isset($_GET['id_isi']) ? mysqli_real_escape_string($con, htmlspecialchars($_GET['id_isi'])) : "";
	$sql = "SELECT * FROM isi_kompetensi WHERE id_isi = $id_isi";
	$q = mysqli_query($con, $sql);
	$data = [];
	while ($row = mysqli_fetch_assoc($q)) {
		$id_isi = $row['id_isi'];
		$id_kompetensi = $row['id_kompetensi'];
		$$row['id_kompetensi'] = $row['id_kompetensi'];
		$isi_kompetensi = $row['isi_kompetensi'];
		$ket = $row['ket'];
		$btn = "Ubah";
		$icn = "edit";
	}

?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#exampleModal').modal('show');

			$('#exampleModal').on('hidden.bs.modal', function(e) {
				document.location = 'index.php?p=subkriteria';
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
			<h4 class="m-0 font-weight-bold"> Daftar Data Sub Kriteria</h6>
			<button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> Tambah Data</button>
		</div>
		<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
			<thead class="bg-info text-white">
				<tr align="center">
					<th width="5%">No</th>
					<th width="15%">Kriteria</th>
					<th>Sub Kriteria</th>
					<th width="15%">Penilai</th>
					<th width="15%">Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$sql = "SELECT * FROM isi_kompetensi a JOIN jenis_kompetensi b ON a.id_kompetensi = b.id_kompetensi ORDER BY b.id_kompetensi ASC";
				$q = mysqli_query($con, $sql);
				$i = 0;
				while ($row = mysqli_fetch_array($q)) {
				?>
					<tr align="center">
						<td><?= ++$i; ?></td>
						<td><?= $row['nama_kompetensi']; ?></td>
						<td align="left"><?= $row['isi_kompetensi']; ?></td>
						<td><?php
							$a = ['Atasan', 'Rekan Kerja', 'Bawahan'];
							$ret = '';
							if ($row['ket'] != '') {
								$ket = explode(",", $row['ket']);
								$b = [];
								foreach ($ket as $k => $v) {
									array_push($b, $a[$v]);
								}
								$ret = join(", ", $b);
							}
							echo $ret;
							?></td>
						<td>
							<div class="btn-group" role="group">
								<a data-toggle="tooltip" data-placement="bottom" title="Edit Data" href="index.php?p=subkriteria&ubah=true&id_isi=<?= $row['id_isi']; ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
								<button data-toggle="tooltip" data-placement="bottom" title="Hapus Data" class="btn btn-danger btn-sm btn_hapus" id="<?= $row['id_isi']; ?>"><i class="fa fa-trash"></i></button>
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
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-<?= $icn; ?>"></i> <?= $btn; ?> Data</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form class="form-horizontal" method="post" action="modal/m_subkriteria.php">
				<div class="modal-body">
					<input type="hidden" id="id_isi" name="id_isi" value="<?= isset($id_isi) ? $id_isi : ""; ?>">

					<div class="form-group">
						<label class="font-weight-bold">Kriteria</label>
						<select class="form-control" id="id_kompetensi" name="id_kompetensi" required>
							<option value="">--Pilih Kriteria--</option>
							<?php
							$jb = mysqli_query($con, "SELECT * FROM jenis_kompetensi");
							while ($rj = mysqli_fetch_array($jb)) {
							?>
								<option value="<?= $rj['id_kompetensi'] ?>" <?php isset($rj['id_kompetensi']) ? "selected" : '' ?>><?= $rj['nama_kompetensi']; ?></option>
							<?php } ?>
						</select>
					</div>

					<div class="form-group">
						<label class="font-weight-bold">Sub Kriteria</label>
						<textarea required class="form-control" id="isi_kompetensi" name="isi_kompetensi" rows="3"><?= isset($isi_kompetensi) ? $isi_kompetensi : ""; ?></textarea>
					</div>

					<div class="form-group">
						<label class="font-weight-bold">Penilai</label>
						<select required class="form-control sel-penilai" multiple id="ket" name="ket">
							<option value="0">Atasan</option>
							<option value="1">Rekan Kerja</option>
							<option value="2">Bawahan</option>
						</select>
					</div>
					<input type="hidden" name="penilai" id="penilai">
					<input type="hidden" name="btnSimpan" value="<?= $btn; ?>">
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
			<form method="post" action="modal/m_subkriteria.php">
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
		$('.sel-penilai').selectpicker();
		$(".sel-penilai").change(function() {
			var a = $(this).val();
			var b = a.join();
			$("#penilai").val(b);
		});
	});
</script>
<?php
	$nip_user = $_SESSION[md5('user')];//'2012091200113504';
			
	$sql = "SELECT * FROM user a JOIN jenis_user b ON a.id_jenis_user = b.id_jenis_user WHERE a.nip = '$nip_user'";
	$q = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($q);

?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-user"></i> Data Profile</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Detail Data Profile</h6>
    </div>

    <div class="card-body">
		<table width="100%" class="table table-bordered">
			<tr>
				<th width="30%" class="bg-light">NIP</th>
				<td><?= $row['nip']; ?></td>
			</tr>
			<tr>
				<th class="bg-light">Jabatan</th>
				<td><?= $row['jabatan']; ?></td>
			</tr>

			<tr>
				<th class="bg-light">Password</th>
				<td><?= $row['password']; ?></td>
			</tr>

			<tr>
				<th class="bg-light">Nama Guru</th>
				<td><?= $row['nama_pegawai']; ?></td>
			</tr>

			<tr>
				<th class="bg-light">Status Guru</th>
				<td><?= $row['status_pegawai']; ?></td>
			</tr>

			<tr>
				<th class="bg-light">Alamat</th>
				<td><?= $row['alamat']; ?></td>
			</tr>

			<tr>
				<th class="bg-light">Tempat</th>
				<td><?= $row['tempat_lahir']; ?></td>
			</tr>

			<tr>
				<th class="bg-light">Tanggal Lahir</th>
				<td><?= $row['tgl_lahir']; ?></td>
			</tr>

			<tr>
				<th class="bg-light">Jenis Kelamin</th>
				<td><?= $row['jenis_kelamin']; ?></td>
			</tr>

			<tr>
				<th class="bg-light">Status Nikah</th>
				<td><?= $row['status_nikah']; ?></td>
			</tr>

			<tr>
				<th class="bg-light">No Telp</th>
				<td><?= $row['no_telp']; ?></td>
			</tr>
		</table>
	</div>
</div>
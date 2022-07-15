<?php
if (!isset($_GET['id'])) {
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
                <h4 class="m-0 font-weight-bold"> Daftar Data Penilaian</h6>
                    <!-- <a class="btn btn-primary" href="assets/file/rubrik.pdf"><i class="fa fa-file-pdf"></i> Rubrik Penilaian</a> -->
            </div>
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-info text-white">
                    <tr align="center">
                        <th width="5%">No</th>
                        <th width="30%">NIP</th>
                        <th>Nama</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                <!-- tampil data detail data -->
                    <?php
                    $i = 0;
                    $nip_s = $_SESSION[md5('user')];
                    $sql = "SELECT a.id_penilai, a.nip, c.nama_pegawai, b.id_penilai_detail FROM penilai a JOIN penilai_detail b  ON a.id_penilai = b.id_penilai
							JOIN user c ON a.nip = c.nip WHERE b.nip = '$nip_s' ";
                    $q = mysqli_query($con, $sql);
                    //if(mysqli_num_rows($q)>0)
                    while ($row = mysqli_fetch_array($q)) {
                    ?>
                        <tr align="center">
                            <td><?= ++$i; ?></td>
                            <td><?= $row['nip']; ?></td>
                            <td><?= $row['nama_pegawai']; ?></td>
                            <td>
                                <?php
                                if (sudah($row['id_penilai_detail']) == 'sudah') {
                                ?>
                                    <a href="index.php?p=melakukanpen&id=<?= $row['id_penilai']; ?>" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Detail Penilaian">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                <?php
                                } else {
                                ?>
                                    <a href="index.php?p=melakukanpen&id=<?= $row['id_penilai']; ?>" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Isi Penilaian">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                <?php
                                }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                     <!-- tampil data detail data -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- menentukan level role -->
<?php } else { ?>
    <?php
    $nip_s = $_SESSION[md5('user')];
    $ssql = "SELECT * FROM user c JOIN jenis_user d ON c.id_jenis_user = d.id_jenis_user WHERE c.nip = '$nip_s'";
    $q = mysqli_query($con, $ssql);
    $rw = mysqli_fetch_array($q);

    if ($rw['level'] == '0') {
        $sebagai = 0;
    } else {
        $sebagai = 1;
    } 

    $id_penilai = isset($_GET['id']) ? mysqli_real_escape_string($con, htmlspecialchars($_GET['id'])) : "";
    $sql = "SELECT a.id_penilai, a.nip, b.nama_pegawai, c.jabatan FROM penilai a JOIN user b ON a.nip = b.nip JOIN jenis_user c ON b.id_jenis_user = c.id_jenis_user WHERE a.id_penilai = '$id_penilai'";
    $q = mysqli_query($con, $sql);
    $row  = mysqli_fetch_array($q);

    ?>
<!-- menentukan level role -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-edit"></i> Data Penilaian</h1>

        <a href="index.php?p=melakukanpen" class="btn btn-secondary btn-icon-split"><span class="icon text-white-50"><i class="fas fa-arrow-left"></i></span>
            <span class="text">Kembali</span>
        </a>
    </div>

    <div class="card shadow mb-4">
        <!-- /.card-header -->
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info"><i class="fa fa-table"></i> Detail Data</h6>
        </div>

        <div class="card-body">
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

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info"><i class="fa fa-table"></i> Detail Data Penilaian</h6>
        </div>

        <div class="card-body">
            <form class="form-horizontal" method="post" action="modal/m_nilai.php">
                <input type="hidden" name="nip_dinilai" value="<?= $row['nip']; ?>">
                <input type="hidden" name="nip_penilai" value="<?= $_SESSION[md5('user')]; ?>">
                <nav class="nav-justified">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        
                    <!-- // menampilkan kompetensi  -->

                        <!-- get data jenis kompetensi -->
                        <?php
                        $sql = "SELECT * FROM jenis_kompetensi";
                        $q = mysqli_query($con, $sql);
                        $i = 0;
                        $data_kompetensi = [];
                        while ($row = mysqli_fetch_array($q)) {
                            $data_kompetensi[$i]['id_kompetensi'] = $row['id_kompetensi'];
                            $data_kompetensi[$i]['nama_kompetensi'] = $row['nama_kompetensi'];
                            $data_kompetensi[$i]['bobot_kompetensi'] = $row['bobot_kompetensi'];
                            if ($i == 0) {
                        ?>
                                <a class="nav-item nav-link active text-info" id="nav-home-tab" data-toggle="tab" href="#nav-<?= $row['id_kompetensi']; ?>" role="tab" aria-controls="nav-home" aria-selected="true"><?= $row['nama_kompetensi']; ?></a>
                            <?php
                            } else {
                            ?>
                                <a class="nav-item nav-link text-info" id="nav-home-tab" data-toggle="tab" href="#nav-<?= $row['id_kompetensi']; ?>" role="tab" aria-controls="nav-home" aria-selected="true"><?= $row['nama_kompetensi']; ?></a>
                        <?php
                            }
                            $i++;
                        }
                        ?>
                        <!-- get data jenis kompetensi -->
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <?php
                    foreach ($data_kompetensi as $k => $v) {
                        if ($k == 0) {
                            $ext = "show active";
                        } else {
                            $ext = "";
                        }
                    ?>
                        <div class="tab-pane fade <?= $ext; ?>" id="nav-<?= $v['id_kompetensi']; ?>" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <br>
                            <div>
                                <table class="table table-bordered">
                                    <thead class="bg-info text-white">
                                        <tr align="center">
                                            <th width="5%">No</th>
                                            <th width="70%">Isi Kompetensi</th>
                                            <th>1</th>
                                            <th>2</th>
                                            <th>3</th>
                                            <th>4</th>
                                            <th>5</th>
                                            <th>6</th>
                                            <th>7</th>
                                            <th>8</th>
                                            <th>9</th>
                                            <th>10</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        $sq = "SELECT * FROM isi_kompetensi WHERE id_kompetensi = $v[id_kompetensi] AND ket LIKE '%$sebagai%' ";
                                        $qs = mysqli_query($con, $sq);
                                        while ($row = mysqli_fetch_array($qs)) {
                                        ?>
                                            <tr>
                                                <td class="text-center align-middle"><?= ++$i; ?></td>
                                                <td class="align-middle"><?= $row['isi_kompetensi']; ?></td>
                                                <td class="form-group">
                                                    <input class="form-control form-control-lg" style="width: auto;" type="radio" name="kompetensi_<?= $row['id_isi']; ?>" id="kompetensi_<?= $row['id_isi']; ?>_1" title="Tidak Mampu" value="1" required>
                                                </td>
                                                <td class="form-group">
                                                    <input class="form-control form-control-lg" style="width: auto;" type="radio" name="kompetensi_<?= $row['id_isi']; ?>" id="kompetensi_<?= $row['id_isi']; ?>_2" title="Kurang Mampu" value="2" required>
                                                </td>
                                                <td class="form-group">
                                                    <input class="form-control form-control-lg" style="width: auto;" type="radio" name="kompetensi_<?= $row['id_isi']; ?>" id="kompetensi_<?= $row['id_isi']; ?>_3" title="Mampu" value="3" required>
                                                </td>
                                                <td class="form-group">
                                                    <input class="form-control form-control-lg" style="width: auto;" type="radio" name="kompetensi_<?= $row['id_isi']; ?>" id="kompetensi_<?= $row['id_isi']; ?>_4" title="Sangat Mampu" value="4" required>
                                                </td>
                                                <td class="form-group">
                                                    <input class="form-control form-control-lg" style="width: auto;" type="radio" name="kompetensi_<?= $row['id_isi']; ?>" id="kompetensi_<?= $row['id_isi']; ?>_5" title="Tidak Mampu" value="5" required>
                                                </td>
                                                <td class="form-group">
                                                    <input class="form-control form-control-lg" style="width: auto;" type="radio" name="kompetensi_<?= $row['id_isi']; ?>" id="kompetensi_<?= $row['id_isi']; ?>_6" title="Kurang Mampu" value="6" required>
                                                </td>
                                                <td class="form-group">
                                                    <input class="form-control form-control-lg" style="width: auto;" type="radio" name="kompetensi_<?= $row['id_isi']; ?>" id="kompetensi_<?= $row['id_isi']; ?>_7" title="Mampu" value="7" required>
                                                </td>
                                                <td class="form-group">
                                                    <input class="form-control form-control-lg" style="width: auto;" type="radio" name="kompetensi_<?= $row['id_isi']; ?>" id="kompetensi_<?= $row['id_isi']; ?>_8" title="Sangat Mampu" value="8" required>
                                                </td>
                                                <td class="form-group">
                                                    <input class="form-control form-control-lg" style="width: auto;" type="radio" name="kompetensi_<?= $row['id_isi']; ?>" id="kompetensi_<?= $row['id_isi']; ?>_9" title="Mampu" value="9" required>
                                                </td>
                                                <td class="form-group">
                                                    <input class="form-control form-control-lg" style="width: auto;" type="radio" name="kompetensi_<?= $row['id_isi']; ?>" id="kompetensi_<?= $row['id_isi']; ?>_10" title="Sangat Mampu" value="10" required>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <?php
                $nip_s = $_SESSION[md5('user')];
                $sql = "SELECT * FROM penilai a JOIN penilai_detail b ON a.id_penilai = b.id_penilai JOIN penilaian c ON b.id_penilai_detail = c.id_penilai_detail WHERE a.id_penilai = '$id_penilai' AND b.nip = '$nip_s'";
                $q = mysqli_query($con, $sql);
                if (mysqli_num_rows($q) > 0) {
                    echo '<script>';
                    while ($row = mysqli_fetch_array($q)) {
                        echo '$("#kompetensi_' . $row['id_isi'] . '_' . $row['hasil_nilai'] . '").attr("checked",true);';
                    }
                    echo '</script>';
                }
                ?>
        </div>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
        </div>
        </form>
    <?php } ?>
    </div>


    <?php
    function sudah($idpdt = '')
    {
        global $con;
        $sql = "SELECT * FROM penilai a JOIN penilai_detail b ON a.id_penilai = b.id_penilai JOIN penilaian c ON b.id_penilai_detail = c.id_penilai_detail WHERE b.id_penilai_detail = $idpdt";
        $q = mysqli_query($con, $sql);
        if (mysqli_num_rows($q) > 0) {
            return 'sudah';
        } else {
            return '';
        }
    }
    ?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
    <section class="content">
        <form action="modal" method="post">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#jadwalModal"><i class="fa fa-plus"></i>Tambah Data</button>
        </form>
        <table class="table">
            <tr>
                <th>NO</th>
                <th>MATA KULIAH</th>
                <th>DOSEN</th>
                <th>JENIS TUGAS</th>
                <th>NAMA TUGAS</th>
                <th>DEADLINE</th>
                <th>NAMA FILE</th>
                <th>PENGUMPULAN</th>
                <th>KETERANGAN</th>
                <th colspan="2">AKSI</th>
            </tr>
            <?php
            $no = 1;
            foreach ($tb_tugas as $jdw) :
            ?>
                <tr>
                    <td><?php echo $no++ ?></td>
                    <td><?php echo $jdw->mata_kuliah ?></td>
                    <td><?php echo $jdw->dosen ?></td>
                    <td><?php echo $jdw->jenis_tugas ?></td>
                    <td><?php echo $jdw->nama_tugas ?></td>
                    <td><?php echo $jdw->deadline ?></td>
                    <td><?php echo $jdw->nama_file ?></td>
                    <td><?php echo $jdw->pengumpulan ?></td>
                    <td><?php echo $jdw->keterangan ?></td>
                    <td onClick="return confirm('Hapus data <?php echo $jdw->mata_kuliah;
                                                            echo " - ";
                                                            echo $jdw->nama_tugas;  ?>?')">
                        <?php echo anchor('user/hapus/' . $jdw->id, '<button type="button"
                        class="badge badge-danger"><i class="fa fa-trash"></i> Delete</button>'); ?>
                    </td>
                    <td onClick="return confirm('ubah data <?php echo $jdw->mata_kuliah;
                                                            echo " - ";
                                                            echo $jdw->nama_tugas;  ?>?')">
                        <?php echo anchor('user/editjadwal/' . $jdw->id, '<button type="button"
                        class="badge badge-primary"><i class="fa fa-edit"></i> Edit</button>'); ?>
                    </td>


                </tr>
            <?php endforeach; ?>
        </table>
    </section>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- Modal -->
<div class="modal fade" id="jadwalModal" tabindex="-1" aria-labelledby="jadwalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="jadwalModalLabel">Tambah Data</h4>
                <button type="button" class="btn-reset" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="<?php echo base_url() . 'user/tambah_aksi'; ?>">
                    <div class="form-groub">
                        <label>MATA KULIAH</label>
                        <input type="text" name="mata_kuliah" class="form-control">
                    </div>
                    <div class="form-groub">
                        <label>DOSEN</label>
                        <input type="text" name="dosen" class="form-control">
                    </div>
                    <div class="form-groub">
                        <label>JENIS TUGAS</label>
                        <input type="text" name="jenis_tugas" class="form-control">
                    </div>
                    <div class="form-groub">
                        <label>NAMA TUGAS</label>
                        <input type="text" name="nama_tugas" class="form-control">
                    </div>
                    <div class="form-groub">
                        <label>DEADLINE</label>
                        <input type="datetime-local" name="deadline" class="form-control">
                    </div>
                    <div class="form-groub">
                        <label>NAMA FILE</label>
                        <input type="text" name="nama_file" class="form-control">
                    </div>
                    <div class="form-groub">
                        <label>PENGUMPULAN</label>
                        <input type="text" name="pengumpulan" class="form-control">
                    </div>
                    <div class="form-groub">
                        <label>KETERANGAN</label>
                        <input type="text" name="keterangan" class="form-control">
                    </div>
                    <button type="reset" class="btn btn-danger" value="reset">Reset</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">kembali</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>

                </form>

            </div>
        </div>
    </div>
</div>
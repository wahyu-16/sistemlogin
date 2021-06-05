<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
    <section class="content">
        <?php foreach ($tb_tugas as $jdw) { ?>
            <form method="post" action="<?php echo base_url() . 'user/update'; ?>">
                <div class="form-groub">
                    <input type="hidden" name="id" class="form-control" value="<?php echo $jdw->id ?>">
                    <label>MATA KULIAH</label>
                    <input type="text" name="mata_kuliah" class="form-control" value="<?php echo $jdw->mata_kuliah ?>">
                </div>
                <div class="form-groub">
                    <label>DOSEN</label>
                    <input type="text" name="dosen" class="form-control" value="<?php echo $jdw->dosen ?>">
                </div>
                <div class="form-groub">
                    <label>JENIS TUGAS</label>
                    <input type="text" name="jenis_tugas" class="form-control" value="<?php echo $jdw->jenis_tugas ?>">
                </div>
                <div class="form-groub">
                    <label>NAMA TUGAS</label>
                    <input type="text" name="nama_tugas" class="form-control" value="<?php echo $jdw->nama_tugas ?>">
                </div>
                <div class="form-groub">
                    <label>DEADLINE</label>
                    <input type="datetime-local" name="deadline" class="form-control" value="<?php echo $jdw->deadline ?>">
                </div>
                <div class="form-groub">
                    <label>NAMA FILE</label>
                    <input type="text" name="nama_file" class="form-control" value="<?php echo $jdw->nama_file ?>">
                </div>
                <div class="form-groub">
                    <label>PENGUMPULAN</label>
                    <input type="text" name="pengumpulan" class="form-control" value="<?php echo $jdw->pengumpulan ?>">
                </div>
                <div class="form-groub">
                    <label>KETERANGAN</label>
                    <input type="text" name="keterangan" class="form-control" value="<?php echo $jdw->keterangan ?>">
                </div>
                <button type="reset" class="btn btn-danger" value="reset">Reset</button>
                <button type="submit" class="btn btn-primary">Simpan</button>

            </form>
        <?php } ?>

    </section>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
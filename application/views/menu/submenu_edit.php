<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
    <div class="row">
        <div class="col-lg-8">
            <?= form_open_multipart(); ?>
            <input type="hidden" name="id" value="<?= $smedit['id']; ?>">

            <div class="form-group">
                <input type="text" class="form-control" id="title" name="title" value="<?= $smedit['title']; ?>">
                <?= form_error('title', '<small class="text-danger pl-3">', '</small>'); ?>
            </div>

            <div class="form-groub">
                <select name="menu_id" id="menu-id" class="form-control">
                    <option value="">Select Menu</option>
                    <?php foreach ($menu as $m) : ?>
                        <option value="<?= $m['id']; ?>"><?= $m['menu']; ?></option>
                    <?php endforeach; ?>
                </select>
                <?= form_error('menu_id', '<small class="text-danger pl-3">', '</small>'); ?>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="url" name="url" value="<?= $smedit['url']; ?>">
                <?= form_error('url', '<small class="text-danger pl-3">', '</small>'); ?>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="icon" name="icon" value="<?= $smedit['icon']; ?>">
                <?= form_error('icon', '<small class="text-danger pl-3">', '</small>'); ?>
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="is_active" id="is_active" checked>
                    <label class="form-check-label" for="is_active">
                        Is Active ?
                    </label>
                </div>


            </div>
            <div class="form-groub row justify-content-end">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary">Edit Menu</button>
                </div>
            </div>

            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
<?= $this->extend('Modules\Admin\Views\layouts\master') ?>
<?= $this->section('body-contents') ?>

<div class="login-page  container-fluid login-bg wrapper">
    <div class='row justify-content-center wrapper'>
        <div class='mt-4 p-4 '>
            <!-- /.login-logo -->
            <div class="card shadow">
                <div class="card-header p-0 mt-2">
                    <h3 class="login-box-msg fw-bold"> Try another way to sign in</h3>
                </div>
                <div class="card-body login-card-body">

                    <p class="heading-username" style="color:#20b8a5"><?= $username ?></p>

                    <div class="col-12 row g-3 valid-err mb-3 ">
                        <?php foreach ($options as $k => $v) { ?>
                            <div class="border border-2 p-2">
                                <small> <a class="try-another-options text-secondary" href="<?= $try_another_url . "?auth_type=" . $k ?>"><?= $v ?></a></small>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <!-- /.login-card-body -->
            </div>
        </div>
    </div>
</div>
    <?= $this->endSection() ?>
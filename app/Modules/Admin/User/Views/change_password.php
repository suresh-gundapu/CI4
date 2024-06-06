<?= $this->extend('Modules\Admin\Views\layouts\master') ?>

<?= $this->section('body-contents') ?>


<div class="login-page wrapper container-fluid login-bg">
    <div class='row justify-content-center'>
    <div class='mt-4 p-4 ' >
            <!-- /.login-logo -->
            <div class="card shadow">
                <div class="card-header p-0 mt-2">
                    <h3 class="login-box-msg fw-bold"> Forgot Password</h3>
                </div>
                <div class="card-body login-card-body">

                    <p class="text-secondary text-center">
                        To reset your password, enter username you use to sign in </p>
                        <form class="row g-3 valid-err mt-3 " id="frgt-pwd-mdl" action="javascript:void(0)">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

                        <div class="col-sm-12 col-md-12 col-lg-12" id="username">
                            <div class="form-floating ">
                                <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>

                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="username" name="username" placeholder=" "  autocomplete="off"/>

                                        <label for="username">Username</label>
                                    </div>
                                </div>
                            </div>
                            <span id="username_err"></span>
                        </div>
                      
                        <div class="col-12  d-grid gap-2">
                            <button type="submit" class="btn btn-success frgt-pwd-mdl"> Send Password</button>
                        </div>
                </div>
                </form>
                <p class="mt-2 text-end me-2">
                    Back to <a href="<?= base_url() ?>admin">Login</a>
                </p>

            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
</div>


<script src="<?= base_url('js/login.js') ?>"></script>
<?= $this->endSection() ?>
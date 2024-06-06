<?= $this->extend('Modules\Admin\Views\layouts\master') ?>

<?= $this->section('body-contents') ?>

<div class="login-page wrapper container-fluid login-bg">
    <div class='row justify-content-center'>
        <div class='mt-4 col-md-8 col-lg-8 '>
                <!-- /.login-logo -->
                <div class="card shadow">
                    <div class="card-header p-0 mt-2">
                        <h3 class="login-box-msg fw-bold"> Login</h3>
                    </div>
                    <div class="card-body login-card-body">

                        <p class="text-secondary text-center">
                            Login to access your account.
                        </p>
                        <form class="row g-3 valid-err " id="form-login" action="javascript:void(0)">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

                            <div class="col-sm-12 col-md-12 col-lg-12" id="username">
                                <div class="form-floating ">
                                    <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="username" name="username" autocomplete="off" placeholder=" " />

                                            <label for="username">Username</label>
                                        </div>
                                    </div>
                                </div>
                                <span id="username_err"></span>

                            </div>
                            
                            <div class="col-sm-12 col-md-12 col-lg-12" >
                                <div class="form-floating ">
                                    <div class="input-group">
                                    <span class="input-group-text"> <i class="fa-solid fa-lock"></i></span>
                                        <div class="form-floating">
                                            <input type="password" class="form-control" id="password" name="password" placeholder=" " />
                                            <a id='pwd_show_hide' class='login-pwd-icon' href='javascript://'><i class='fa-solid fa-eye-slash ' id='pwd_icon' status='hide'></i></a>
                                            <label for="password">Password</label>
                                        </div>
                                    </div>
                                </div>
                                <span id="password_err"></span>
                            </div>
                            <div class="col-12  d-grid gap-2">
                        <button type="submit" class="btn btn-success login"> Login</button>
                    </div>
                    </div>
                    </form>
                    <p class="mt-2 text-end me-2">
                        <a class="form-label" href="<?= base_url() ?>admin/forgot-pwd">Forgot Password ?</a>
                    </p>

                </div>
                <!-- /.login-card-body -->
        </div>
    </div>
</div>

</div>


<script src="<?= base_url('js/login.js') ?>"></script>
<?= $this->endSection() ?>
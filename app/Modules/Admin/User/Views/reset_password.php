<?= $this->extend('Modules\Admin\Views\layouts\master') ?>
<?= $this->section('body-contents') ?>
<div class="login-page wrapper container-fluid login-bg">
    <div class='row justify-content-center '>
        <div class='mt-4 p-4 '>
            <!-- /.login-logo -->
            <div class="card shadow w-50 m-auto">
                <div class="card-header p-0 mt-2">
                    <h3 class="login-box-msg fw-bold"> Reset Password</h3>
                </div>
                <div class="card-body login-card-body">

                    <form class="row g-3 valid-err mt-3 " id="change-pwd-frm" action="javascript:void(0)">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                   
                        <div class="col-sm-12 col-md-12 col-lg-12" id="password">
                            <div class="form-floating ">
                                <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                    <div class="form-floating">
                                        <input type="password" class="form-control" id="password" name="password" placeholder=" " />

                                        <label for="password">New Password</label>
                                    </div>
                                </div>
                            </div>
                            <span id="password_err"></span>

                        </div>

                        <div class="col-sm-12 col-md-12 col-lg-12" id="retype_password">
                            <div class="form-floating ">
                                <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                    <div class="form-floating">
                                        <input type="password" class="form-control" id="retype_password" name="retype_password" placeholder=" " />

                                        <label for="retype_password">Re-type Password</label>
                                    </div>
                                </div>
                            </div>
                            <span id="retype_password_err"></span>

                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12" id="reset_code">
                            <div class="form-floating ">
                                <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-rotate-right"></i></span>
                                    <div class="form-floating">
                                        <input type="password" class="form-control" id="reset_code" name="reset_code" placeholder=" " />

                                        <label for="reset_code">Reset Code</label>
                                    </div>
                                </div>
                            </div>
                            <span id="reset_code_err"></span>

                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12  d-grid gap-2 mb-4">
                            <button type="submit" class="btn btn-success change-pwd-frm"> Reset Password</button>
                        </div>
                    </form>

                </div>


            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
</div>
<script src="<?= base_url('js/login.js') ?>"></script>
<?= $this->endSection() ?>
<?= $this->extend('Modules\Admin\Views\layouts\master') ?>

<?= $this->section('body-contents') ?>
<div class="login-page wrapper container-fluid login-bg">
    <div class='row justify-content-center'>
        <div class='mt-4 col-md-8 col-lg-8 '>
            <!-- /.login-logo -->
            <div class="card shadow w-75 m-auto">
                <div class="card-header p-0 mt-2">
                    <h3 class="login-box-msg fw-bold"> 2-Step Verification</h3>
                </div>
                <div class="card-body login-card-body">

                    <p class=" text-center" style="color:#20b8a5"><?= $username ?> </p>
                    <small><?= $title ?></small>

                    <form class="row g-3 valid-err mt-1 mb-3" name="authentication" id="google_authentication" action="javascript:void(0)">
                        <input type="hidden" name="auth_type" id="auth_type" value="<?= $auth_type ?>" />
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

                        <div class="col-sm-12 col-md-12 col-lg-12" id="2fa_code">
                            <div class="form-floating ">
                                <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-location-pin-lock"></i></span>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="2fa_code" id="2fa_code" placeholder="<?= $placeholder ?>" autocomplete="off" />
                                        <label for="2fa_code"><?= $placeholder ?></label>
                                    </div>
                                </div>
                            </div>
                            <span id="2fa_code_err"></span>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">

                                <input class="form-check-input" type="checkbox" value="Yes" name="dont_ask_again" id="dont_ask_again" <?php if ($dont_ask_again == "Yes") { ?>checked="checked" <?php } ?>>
                                <label for="dont_ask_again" class="form-check-label"><small>Don't ask again on this computer / device</small></label>

                            </div>

                        </div>
                        <div class="col-12  d-grid gap-2">
                            <button type="submit" class="btn btn-success login-btn " id="loginBtn" onclick="return validateCode();"> Verify</button>
                        </div>
                        <div class="col-md-12 d-flex justify-content-between">
                            <div class="show-forgot-pwd left">
                                <a href="<?= $login_url ?>">Back to Login</a>
                            </div>
                            <div class="show-forgot-pwd right">
                                <a href="<?= $try_another_way_url ?>" href="">Try another way</a>
                            </div>
                        </div>

                        <div class="col-md-12 d-flex justify-content-center">
                            <?php if ($auth_type != "Google") { ?>
                                <div class="autentication-resend-otp">
                                    <a id="resend_otp" href="<?= $resend_otp_url ?>/<?= $auth_type ?>">Resend OTP</a>
                                    <img id="otp_img" style="display:none;" src="<?= base_url() ?>public/images/admin/loading.gif" />
                                </div>
                            <?php } ?>
                        </div>
                </div>
                </form>


            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#resend_otp").click(function() {
            $("#otp_img").removeAttr("style")
        });
    });
</script>

<script src="<?= base_url('js/otp_authentication.js') ?>"></script>

<?= $this->endSection() ?>
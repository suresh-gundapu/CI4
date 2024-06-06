<?= $this->extend('Modules\Front\Views\layouts\master') ?>
<?= $this->section('body-contents') ?>
<div class="container wrapper rounded bg-white mt-5 mb-5">
    <div class="row">
        <header class="pb-3  border-bottom">
            <h3 class="d-flex align-items-center text-dark text-decoration-none">
                <span class="fs-4">Welcome <span class="text-danger ms-2"><?= session()->get('userData')['userName'] ?> !!</span></span>
            </h3>
        </header>
        <div class="col-md-6 border-right card shadow border-1 m-4">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">Profile Settings</h4>
                </div>
                <form action="javascript:void(0)" id="profile-frm" class="valid-err">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

                    <div class="row mt-2">
                        <div class="col-md-12"><label class="labels">Name</label><input type="text" class="form-control" name="name" placeholder="first name" value="<?= $user['vFirstName'] ?>"></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12"><label class="labels">Email</label><input type="email" class="form-control" name="email" placeholder="Email" value="<?= $user['vEmail'] ?>"></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12"><label class="labels">Mobile Number</label><input type="text" class="form-control" name="mobile_no" placeholder="Mobile Number" value="<?= $user['vPhonenumber'] ?>"></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12"><label class="labels">UserName</label><input type="text" class="form-control" name="user_name" placeholder="UserName" value="<?= $user['vUserName'] ?>"></div>
                    </div>

                    <div class="mt-5 text-center"><button class="btn btn-primary update_profile" type="submit">Update Profile</button>
                    </div>
                    <input type="hidden" name="user_id" value="<?= $user['iCustomerId'] ?>">

                </form>
            </div>
        </div>
        <div class="col-md-4 card shadow border-1 m-4">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">Change Password</h4>
                </div>
                <form action="javascript:void(0)" id="change-pwd-frm" class="valid-err">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

                    <div class="row mt-2">
                        <div class="col-md-12"><label class="labels">Current Password</label><input type="password" class="form-control" name="c_pass" placeholder="Current Password" value=""></div>
                        <input type="hidden" name="user_id" value="<?= $user['iCustomerId'] ?>">

                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12"><label class="labels">New Password</label><input type="password" class="form-control" name="n_pass" placeholder="New Password" value=""></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12"><label class="labels">Confirm Password</label><input type="password" class="form-control" name="confirm_pass" placeholder="Confirm Password" value=""></div>
                    </div>
                    <div class="mt-5 text-center"><button class="btn btn-primary profile-button update_password" type="submit">Update Password</button></div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('js/user_dashboard.js') ?>"></script>
<?= $this->endSection() ?>
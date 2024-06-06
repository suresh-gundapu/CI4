<?= $this->extend('Modules\Front\Views\layouts\master') ?>

<?= $this->section('body-contents') ?>


<div class="container wrapper rounded bg-white mt-5 mb-5">
    <div class="row">
      
        <div class="col-md-12 card shadow border-1 m-4">
            <div class="p-3 py-5">
                    <h4 class="text-center mb-4 border-bottom">Change Password</h4>
                <form action="javascript:void(0)" id="change-pwd-frm" class="valid-err w-50 m-auto">
                    <div class="row mt-2">
                        <div class="col-md-12"><label class="labels">New Password</label><input type="password" class="form-control" name="n_pass" placeholder="New Password" value=""></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12"><label class="labels">Confirm Password</label><input type="password" class="form-control" name="confirm_pass" placeholder="Confirm Password" value=""></div>
                    </div>
                    <div class="mt-3 text-center"><button class="btn btn-primary profile-button update_password" type="button">Update Password</button></div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('js/dashboard.js') ?>"></>
<?= $this->endSection() ?>
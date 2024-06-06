<?= $this->extend('Modules\Admin\Views\layouts\master') ?>
<?= $this->section('body-contents') ?>
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Country Add</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>admin/dashboard">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Country Add
                        </li>
                    </ol>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content Header-->
    <!--begin::App Content-->
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"></h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form class="row g-3 valid-err mt-3 " id="country-add" action="javascript:void(0)">
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                <div class="row mt-3">
                                    <div class="col-sm-12 col-md-12 col-lg-12" id="country_name">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="country_name" name="country_name" placeholder=" " autocomplete="off" />

                                            <label for="country_name">Name</label>
                                        </div>
                                        <span id="country_name_err"></span>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12 col-md-12 col-lg-12" id="country_code">
                                        <div class="form-floating ">
                                            <input type="text" class="form-control" id="country_code" name="country_code" placeholder=" " autocomplete="off" />

                                            <label for="country_code">Country Code</label>
                                        </div>
                                        <span id="country_code_err"></span>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12 col-md-12 col-lg-12" id="country_code_iso3">
                                        <div class="form-floating ">
                                            <input type="text" class="form-control" id="country_code_iso3" name="country_code_iso3" placeholder=" " autocomplete="off" />

                                            <label for="country_code_iso3">Country Code ISO3</label>
                                        </div>
                                        <span id="country_code_iso3_err"></span>
                                    </div>
                                </div>
                              
                                <div class="row mt-3">
                                    <div class="col-sm-12 col-md-12 col-lg-12" id="country_daily_code">
                                        <div class="form-floating ">
                                            <input type="text" class="form-control" id="country_daily_code" name="country_daily_code" placeholder=" " autocomplete="off" />

                                            <label for="country_daily_code">Dail Code</label>
                                        </div>
                                        <span id="country_daily_code_err"></span>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12 col-md-12 col-lg-12" id="country_desc">
                                        <div class="form-floating">
                                            <textarea class="form-control" placeholder=" " name="country_desc" id="country_desc" rows="3"></textarea>
                                            <label for="country_desc">Description</label>
                                        </div>
                                        <span id="country_desc_err"></span>
                                    </div>

                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12 col-md-12 col-lg-12" id="status">
                                        <div class="form-floating">
                                            <select class="form-select" name="status" id="status">
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
                                            </select>
                                            <label for="gfg">Select Status</label>
                                        </div>

                                    </div>
                                </div>
                                <div class="row mt-3 ">
                                    <div class="col-sm-12 col-md-12 col-lg-12 d-grid gap-2">
                                        <button type="submit" class="btn btn-success country-add">Add</button>
                                    </div>
                                </div>
                        </div>
                        </form>
                    </div>

                </div>

                <!-- /.card -->
            </div>
            <!-- /.col -->

            <!-- /.col -->
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
    </div>
    <!--end::App Content-->
</main>
<!--end::App Main--> <!--begin::App Content Header-->

<script src="<?= base_url('js/country.js') ?>"></script>

<?= $this->endSection() ?>
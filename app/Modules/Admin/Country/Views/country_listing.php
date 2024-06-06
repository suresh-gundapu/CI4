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
                    <h3 class="mb-0">Country</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>admin/dashboard">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Country<?php 
                            //  echo "<pre>";
                            //  print_r($data['data']);exit;
                 ?>
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
                            <a href="country-add">
                                <button class="btn btn-success">
                                    Add
                                </button>
                            </a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="countryDataTable"   class="table table-bordered table-responsive display">
                                <thead>
                                    <tr>
                                        <th>SNO</th>
                                        <th>Country</th>
                                        <th>Country Code</th>
                                        <th>Country Code ISO-3</th>
                                        <th>Number Of States</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>

                            </table>
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
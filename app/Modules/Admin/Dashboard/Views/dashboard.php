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
            <h3 class="mb-0"><span class="fs-5" style="color: #20b8a5;">Welcome To Hidden Brains Admin Panel</span></h3>
          </div>
          <div class="col-sm-6">
            <!-- <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">
              Dashboard
            </li>
          </ol> -->
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
          <!--begin::Col-->
          <?php
          // print_r($parent_arr);
          foreach ($parent_arr as $key => $value) {
            $child_arr = $menu_arr[$parent_arr[$key]['id']];
          ?>
            <div class="col-lg-3 col-6">
              <div class="card  shadow  sitemap-home mt-2">
                <div class="card-header">
                  <div class="card-inner-body">
                    <h4><span class="icon14 <?= $parent_arr[$key]['icon'] ?>"></span><?= $parent_arr[$key]['label'] ?></h4>
                  </div>
                </div>
                <div class="card-body ">
                  <?php if (is_array($child_arr) && count($child_arr) > 0) { ?>
                    <ul class="list-group list-group-flush">
                      <?php foreach ($child_arr as $k => $v) { ?>
                        <li class="list-group-item">
                          <a hijacked="yes" aria-nav-code="<?= $child_arr[$k]['code'] ?>" href="<?= $child_arr[$k]['url'] ?>" target="<?= $child_arr[$k]['target'] ?>" title="<?= $child_arr[$k]['label'] ?>" class="nav-active-link <?= $child_arr[$k]['class'] ?>">
                            <span class="icon12 <?= $child_arr[$k]['icon'] ?>"></span>
                            <?= $child_arr[$k]['label'] ?>
                          </a>
                        </li>

                      <?php } ?>
                    </ul>
                  <?php } ?>
                </div>
              </div>

            </div>

          <?php } ?>
        </div>
        <!--end::Col-->
      </div>
      <!--end::Row-->

      <!--end::Container-->
    </div>
    <!--end::App Content-->
                      </main>


<?= $this->endSection() ?>
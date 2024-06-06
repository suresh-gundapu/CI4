<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
    <meta name="generator" content="Hugo 0.84.0">
    <title>Home :: CI-4</title>
    <link rel="stylesheet" href="<?= base_url('css/bootstrap5/bootstrap.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('css/common.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('css/jQuery/sweetalert2.min.css') ?>"  />
    <script>
        var csrfName = '<?= csrf_token() ?>';
        var csrfHash = '<?= csrf_hash() ?>';
    </script>
    <script type='text/javascript'>
        var base_url = "<?= base_url() ?>"
    </script>
    <script src="<?= base_url('js/jQuery/jquery.min.js') ?>"></script>
    <!-- Validation library file -->
    <script src="<?= base_url('js/jQuery/jquery.validate.min.js') ?>"></script>
    <script src="<?= base_url('js/jQuery/sweetalert2.min.js') ?>"></script>
</head>

<body>
    <div class="layout-fixed  bg-body-tertiary">
    <?= $this->include("Modules\Front\Views\includes\header"); ?>
        <?= $this->renderSection("body-contents") ?>
        <?= $this->include("Modules\Front\Views\includes/footer"); ?>
        
    </div>
    <script src="js/bootstrap5/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
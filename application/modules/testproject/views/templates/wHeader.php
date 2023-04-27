<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title ?></title>
    <!-- Popperjs -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <!-- Tempus Dominus JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.4.4/dist/js/tempus-dominus.min.js" crossorigin="anonymous"></script>
    <!-- Tempus Dominus Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.4.4/dist/css/tempus-dominus.min.css" crossorigin="anonymous">
    <!-- Bootstrap CSS -->
    <link href="<?php echo base_url('application/modules/testproject/assets/css/bootstrap/bootstrap.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('application/modules/testproject/assets/css/daterangepicker.css');?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <script src="<?php echo base_url('application/modules/testproject/assets/js/moment.js');?>"></script>
    <script>
        function imgError(image) {
            image.onerror = "";
            image.src = "<?php echo base_url('application/modules/testproject/assets/img/default.png') ?>";
            return true;
        }
        function JSxPDTChangeLanguage(lang) {
            $.ajax({
                url: "<?php echo base_url('/changeLanguage') ?>",
                type: "POST",
                data: {
                    tLng: lang
                },
                success: function (data) {
                    if (data.status == 'success')
                    {
                        location.reload();
                    }
                }
            });
        }
    </script>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="<?php echo base_url('/products') ?>">Main</a>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <form class="d-flex">
                    <li class="nav-item">
                        <a class="nav-link" onclick="JSxPDTChangeLanguage('en')">EN</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link">|</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"onclick="JSxPDTChangeLanguage('th')" >TH</a>
                    </li>
                </form>
            </ul>
        </div>
    </div>
</nav>
<div class="container pb-4">
    <div class="row">
        <div class="card bg-dark">
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col-3 text-center text-white">
                        <span id="ospLocation"></span>
                    </div>
                    <div class="col-3 text-center text-white">
                        <span id="ospPM10"></span>
                    </div>
                    <div class="col-3 text-center text-white">
                        <span id="ospPM25"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
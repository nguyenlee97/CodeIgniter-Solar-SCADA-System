<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Ly Xuan Truong">

    <title><?php echo $title; ?></title>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>public/images/icon.png">

    <!-- Jquery -->
    <script src="<?php echo base_url('public/lib/jquery-3.5.1.min.js') ?>"></script>
    <!-- // Jquery -->

    <!-- Bootstrap -->
    <link href="<?php echo base_url(); ?>public/lib/bootstrap-4.1.1-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/lib/fontawesome-free-5.0.13/web-fonts-with-css/css/fontawesome-all.min.css" rel="stylesheet">
    <!-- <script src="<?php echo base_url('public/lib/bootstrap-4.1.1-dist/js/bootstrap.min.js') ?>"></script> -->
    <!-- Bootstrap -->

    <!-- Alertify -->
    <link href="<?php echo base_url('public/lib/alertifyjs/css/alertify.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('public/lib/alertifyjs/css/themes/default.min.css'); ?>" rel="stylesheet">
    <script type="text/javascript" src="<?php echo base_url('public/lib/alertifyjs/alertify.js');?>"></script>
    <!-- // Alertify -->

    <!-- CoreUI -->
    <link rel="stylesheet" href="<?php echo base_url('public/lib/coreui-master/dist/css/coreui.min.css'); ?>" crossorigin="anonymous">
    
    <!-- Databable -->
    <link href="<?php echo base_url('public/lib/Datatables/jquery.dataTables.min.css'); ?>" rel="stylesheet">
    <script src="<?php echo base_url('public/lib/Datatables/jquery.dataTables.min.js'); ?>"></script>

    <!-- Select2 -->
    <link href="<?php echo base_url('public/lib/select2/dist/css/select2.min.css'); ?>" rel="stylesheet">
    <script src="<?php echo base_url('public/lib/select2/dist/js/select2.full.min.js'); ?>"></script>
    <!-- // Select2 -->

    
    <script>
        window.base_url="<?php echo base_url(); ?>";
    </script>

</head>

<body  class="c-app">
  <div class="c-sidebar c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
    <!-- Sidebar content here -->
    <?php $this->load->view("layout/sidebar.php"); ?>
  </div>
  <div class="c-wrapper">
    <header class="c-header c-header-fixed">
      <!-- Header content here -->
      <?php $this->load->view("layout/header.php"); ?>
    </header>
    <div class="c-body">
      <main class="c-main">
        <!-- Main content here -->
        <?php $this->load->view($template,$data); ?>
      </main>
    </div>
    <footer class="c-footer">
    Cty IOT
    
    </footer>
  </div>
</body>

    <!-- style -->
    <!-- CUSTOME CSS -->
    <link href="<?php echo base_url(); ?>public/css/styles.css" rel="stylesheet">    
    <!-- // style -->

    <script src="<?php echo base_url('public/lib/coreui-master/dist/js/coreui.bundle.min.js'); ?>"></script>
</html>

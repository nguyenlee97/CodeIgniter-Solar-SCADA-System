<?php
/**
 * Created by PhpStorm.
 * User: Ly Xuan Truong
 * Date: 28/07/2020
 * Time: 6:07 PM
 */

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login page</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Ly Xuan Truong">

        <title><?php echo $title; ?></title>
        <link rel="shortcut icon" href="<?php echo base_url(); ?>public/images/icon.png">

        <!-- Jquery -->
        <script src="<?php echo base_url('public/lib/jquery-3.3.1.min.js') ?>"></script>
        <!-- // Jquery -->

        <!-- Bootstrap -->
        <link href="<?php echo base_url(); ?>public/lib/bootstrap-4.1.1-dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>public/lib/fontawesome-free-5.0.13/web-fonts-with-css/css/fontawesome-all.min.css" rel="stylesheet">
        <script src="<?php echo base_url('public/lib/bootstrap-4.1.1-dist/js/bootstrap.min.js') ?>"></script>
        <!-- Bootstrap -->

        <!-- Alertify -->
        <link href="<?php echo base_url('public/lib/alertifyjs/css/alertify.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('public/lib/alertifyjs/css/themes/default.min.css'); ?>" rel="stylesheet">
        <script type="text/javascript" src="<?php echo base_url('public/lib/alertifyjs/alertify.js');?>"></script>
        <!-- // Alertify -->

        <!-- CoreUI CSS -->
        <link rel="stylesheet" href="<?php echo base_url('public/lib/coreui-master/dist/css/coreui.min.css'); ?>" crossorigin="anonymous">

        <!-- CUSTOME CSS -->
        <link href="<?php echo base_url(); ?>public/css/styles_login.css" rel="stylesheet">

        <script src="<?php echo base_url('public/js/login.js') ?>"></script>
        <script>
            window.base_url = '<?php echo base_url(); ?>';
        </script>

    </head>
    
<body oncontextmenu="false" class="c-app">
  <div class="c-wrapper">
    <header class="c-header c-header-fixed">
      <!-- Header content here -->
    </header>
    <div class="c-body">
      <main class="c-main">
        <!-- Main content here -->
        <div class="imageLoginPage" style="" align='center'>
                <img src="<?php echo base_url('public/images/19a68065c02c3f72663d.jpg'); ?>" width="100" />
            </div>
        <div class="login-page">
            
            <div class="form">
                <h3 style="color: #44a2ff;">ĐĂNG NHẬP</h3>
                <form class="register-form" onsubmit="register(); return false;">
                    <div class="alert"></div>
                    <input type="text" placeholder="full name" class="u_fullname"/>
                    <input type="text" placeholder="username" class="u_name"/>
                    <input type="password" placeholder="password" class="u_pass"/>
                    <button>create</button>
                    <p class="message">Already registered? <a href="#">Sign In</a></p>
                </form>
                <form class="login-form" method="post" onsubmit="check_login(); return false;">
                    <div class="alert"></div>
                    <input type="text" placeholder="Username" class="u_name"/>
                    <input type="password" placeholder="Password" class="u_pass"/>
                    <button>Đăng nhập</button>
                    <!-- <p class="message">Bạn không nhớ mật khẩu? </p>
                    <p>Bấm vào <a href="#">đây</a> để lấy lại mật khẩu</p> -->
                </form>
            </div>
        </div>

        <script>
            $('.message a').click(function(){
                $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
            });

            $('.login-form .u_name').on('keyup',function(){
                $('.login-form .alert').html("");
            });

            $('.login-form .u_pass').on('keyup',function(){
                $('.login-form .alert').html("");
            });
        </script>
      </main>
    </div>
  </div>
</body>
</html>


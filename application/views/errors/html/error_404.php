<?php
    $base_url = (isset($_SERVER['HTTPS']) ? "https://" : "http://").$_SERVER['HTTP_HOST']."/";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Ly Xuan Truong">
    <link rel="shortcut icon" href="<?php echo $base_url."public/images/icon.png"; ?>" >
    <title>Page not found</title>
    <!-- Jquery -->
    <script src="<?php echo $base_url.'public/lib/jquery-3.5.1.min.js'; ?>"></script>
    <!-- // Jquery -->
    <!-- Bootstrap -->
    <link href="<?php echo $base_url."public/lib/bootstrap-4.1.1-dist/css/bootstrap.min.css"; ?>" rel="stylesheet">
</head>
<body>
    <div class="container" align="center">
        <img src="<?php echo $base_url.'public/images/page-not-found.png'; ?>" width="100%">
    </div>
    
    <script>
        $(document).ready(function(){
            setTimeout(function(){
                window.location.href='<?php echo $base_url; ?>';
            },5000);
        });
    </script>
</body>
</html>
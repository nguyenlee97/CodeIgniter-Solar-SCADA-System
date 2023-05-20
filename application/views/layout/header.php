<button class="c-header-toggler c-class-toggler d-lg-none " type="button" data-target="#sidebar" data-class="c-sidebar-show">
    <i class="fas fa-bars"></i>
</button>

<button class="c-header-toggler c-class-toggler mfs-3 d-md-down-none" type="button" data-target="#sidebar" data-class="c-sidebar-lg-show" responsive="true">
    <i class="fas fa-bars"></i>
</button>
<div class="c-header-nav">
    <table style="display: none;">
        <tr>
            <td class="dataDong1">Current PAC: </td>
        </tr>
        <tr>
            <td class="dataDong2">PAC Max: <span style="color:#3be43b" class="PACmax"> </span> - PAC Min: <span style="color:red" class="PACmin"></span></td>
        </tr>
        <tr></tr>
    </table>
    
</div>
<!-- <ul class="c-header-nav d-md-down-none">
    <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
    <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="<?php echo base_url('report'); ?>">Report</a></li>
    <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="<?php echo base_url('setting'); ?>">Settings</a></li>
</ul> -->
<ul class="c-header-nav mfs-auto">
    
</ul>
<ul class="c-header-nav mfe-md-3">
    <li class="c-header-nav-item dropdown">
        <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <div class="c-avatar"><i class="far fa-user-circle" style="font-size: 30px;color: #8b9dbd;"></i></div>
        </a>
        <div class="dropdown-menu dropdown-menu-right pt-0">
            <div class="dropdown-header bg-light py-2 text-center" >
                <strong><?php echo $_SESSION[LOGIN]['name']; ?></strong>
                <div>(<?php echo $_SESSION[LOGIN]['username']; ?>)</div>
            </div>
            <a class="dropdown-item" href="<?php echo base_url('login/change_password'); ?>">
                <svg class="c-icon mfe-2">
                    <use xlink:href="<?php echo base_url('public/lib/coreui-master/icons/sprites/free.svg#cil-sync'); ?>"></use>
                </svg>
                Đổi mật khẩu
            </a>
            <a class="dropdown-item" href="<?php echo base_url('login/logout'); ?>">
                <svg class="c-icon mfe-2">
                    <use xlink:href="<?php echo base_url('public/lib/coreui-master/icons/sprites/free.svg#cil-account-logout'); ?>"></use>
                </svg> 
                Đăng xuất
            </a>
            
        </div>
    </li>
</ul>
<div class="c-subheader justify-content-between px-3 " >
    <ol class="breadcrumb border-0 m-0 px-0 px-md-3">
        <a class="breadcrumb-item " href="<?php if(isset($breadcrumbLinkTitle)) echo $breadcrumbLinkTitle; else echo "#"; ?>">
            <svg class="c-sidebar-nav-icon"><use xlink:href="<?php echo base_url('public/lib/coreui-master/icons/sprites/free.svg#'.$iconTitle); ?>"></use></svg>
            <?php echo $title; ?>
        </a>
        <?php
        if(isset($subTitle)){
        ?>
        <a class="breadcrumb-item active" href="#">
            <svg class="c-sidebar-nav-icon"><use xlink:href="<?php echo base_url('public/lib/coreui-master/icons/sprites/free.svg#'.$iconSubTitle); ?>"></use></svg>
            <?php echo $subTitle; ?>
        </a>
        </li>
        <?php
        }
        ?>
    </ol>
</div>
<div class="c-sidebar-brand d-md-down-none">
    <!-- <div class="logo">Logo Cty IOT</div> -->
    <img src="<?php echo base_url('public/images/logo.jpg'); ?>" width="110" />
</div>

<ul class="c-sidebar-nav">
    <li class="c-sidebar-nav-title"><?php echo $data['thongTinCty']; ?></li>
    <li class="c-sidebar-nav-item">
      <a class="c-sidebar-nav-link " href="<?php echo base_url(); ?>">
          <svg class="c-sidebar-nav-icon"><use xlink:href="<?php echo base_url('public/lib/coreui-master/icons/sprites/free.svg#cil-tv'); ?>"></use></svg>
          Giám sát hệ thống
      </a>
    </li>
    <?php
    if( in_array($_SESSION[LOGIN]['loaiTaiKhoan'], array("quantri","nguoidung"))){
      ?>
    <li class="c-sidebar-nav-dropdown">
        <a class="c-sidebar-nav-dropdown-toggle " href="#">
            <svg class="c-sidebar-nav-icon"><use xlink:href="<?php echo base_url('public/lib/coreui-master/icons/sprites/free.svg#cil-settings'); ?>"></use></svg> 
            Cài đặt hệ thống
        </a>
        <ul class="c-sidebar-nav-dropdown-items">
        <li class="c-sidebar-nav-item">
          <a class="c-sidebar-nav-link" href="<?php echo base_url('Setting/location'); ?>"> 
          <svg class="c-sidebar-nav-icon"><use xlink:href="<?php echo base_url('public/lib/coreui-master/icons/sprites/free.svg#cil-compass'); ?>"></use></svg> 
            Khu vực lắp đặt
          </a>
        </li>
        <li class="c-sidebar-nav-item">
          <a class="c-sidebar-nav-link" href="<?php echo base_url('Setting/device'); ?>"> 
          <svg class="c-sidebar-nav-icon"><use xlink:href="<?php echo base_url('public/lib/coreui-master/icons/sprites/free.svg#cil-memory'); ?>"></use></svg> 
            Cài đặt thiết bị
          </a>
        </li>
        <!-- <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="buttons/loading-buttons.html"> Loading Buttons<span class="badge badge-danger">PRO</span></a></li> -->
        </ul>
    </li>
    <li class="c-sidebar-nav-item">
      <a class="c-sidebar-nav-link " href="<?php echo base_url('Account'); ?>">
          <svg class="c-sidebar-nav-icon"><use xlink:href="<?php echo base_url('public/lib/coreui-master/icons/sprites/free.svg#cil-people'); ?>"></use></svg>
          Quản lý người dùng
      </a>
    </li>
      <?php
    }
    ?>

    <?php
    if( in_array($_SESSION[LOGIN]['loaiTaiKhoan'], array("quantri"))){
    ?>
    <li class="c-sidebar-nav-item">
      <a class="c-sidebar-nav-link " href="<?php echo base_url('Export'); ?>">
          <svg class="c-sidebar-nav-icon"><use xlink:href="<?php echo base_url('public/lib/coreui-master/icons/sprites/free.svg#cil-notes'); ?>"></use></svg>
          Kết xuất dữ liệu
      </a>
    </li>
    <li class="c-sidebar-nav-item">
      <a class="c-sidebar-nav-link " href="<?php echo base_url('Api'); ?>">
          <svg class="c-sidebar-nav-icon"><use xlink:href="<?php echo base_url('public/lib/coreui-master/icons/sprites/free.svg#cil-3d'); ?>"></use></svg>
          API Document
      </a>
    </li>
    <?php
    }
    ?>
  </ul>

<?php
    if($data['allowEdit']['state']=='error'){
        ?>
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalCenterTitle">Sửa thông tin khu vực</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="alert alert-danger" role="alert"><?php echo  $data['allowEdit']['alert']; ?></div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
        <?php
    }else{
        ?>
<form class="form-edit-device " onsubmit="return false;">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalCenterTitle">Sửa thông tin khu vực</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
</div>
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-sm-6" style="display: none;">
                <label for="city">Tài khoản khách hàng</label>
                <select class="form-control input_taiKhoanCapTren" type="text" ></select>
            </div>
            <div class="form-group col-sm-12" >
                    <label for="city">Khu vực lắp đặt</label>
                    <select class="form-control input_khuvuc" ></select>
                </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-6">
                    <label for="city">ID thiết bị</label>
                    <input class="form-control input_deviceID not-null" type="text" placeholder="Nhập ID thiết bị"  readonly
                    value="<?php echo $data['infoThietBi']['idThietBi']; ?>"
                    >
                    <div class="device-name invalid-feedback">Vui lòng cung cấp thông tin.</div>
                </div>
            <div class="form-group col-sm-6">
                <label for="city">Tên thiết bị</label>
                <input class="form-control input_ten not-null" type="text" placeholder="Đặt tên cho thiết bị. Ví dụ: Inverter 1"
                    value="<?php echo $data['infoThietBi']['tenThietBi']; ?>"
                >
                <div class="device-name invalid-feedback">Vui lòng cung cấp thông tin.</div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-12 ">
                <label >Mô tả</label>
                <textarea class="form-control input_mota" style="height: inherit"><?php echo $data['infoThietBi']['mota']; ?></textarea>
            </div>
        </div>
    </div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-success btn-save-edit">Save changes</button>
</div>
</form>
<script>
    $(document).ready(function(){
        load_ds_taiKhoanCapTrenEdit(function(){
            // load khu vuc cho phần search danh sach
            load_ds_khuvuc_edit(function(){
            },'<?php echo $data['infoThietBi']['idKhuVucLapDat']; ?>');
        },'<?php echo $data['infoThietBi']['taiKhoanKhachHang']; ?>');

        $('.form-edit-device input.not-null').on('keyup',function () {
            if($(this).val()==""){
                $(this).addClass('is-invalid');
                $(this).parent().children('.invalid-feedback').html("Vui lòng cung cấp thông tin.");
            }else{
                $(this).removeClass('is-invalid');
                $(this).parent().children('.invalid-feedback').html("");
            }
        });

        <?php 
        if($data['loaiTaiKhoan']=='quantri'){
        ?>
            $('.form-edit-device .input_taiKhoanCapTren').parent().css('display','block');
            $('.form-edit-device .input_taiKhoanCapTren').parent().removeClass('col-sm-12');
            $('.form-edit-device .input_taiKhoanCapTren').parent().addClass('col-sm-6');

            $('.form-edit-device .input_khuvuc').parent().removeClass('col-sm-12');
            $('.form-edit-device .input_khuvuc').parent().addClass('col-sm-6');
        <?php
        }
        ?>

        $('.form-edit-device .input_taiKhoanCapTren').on('change',function(){
            load_ds_khuvuc_edit(); 
        });
        
        $('.btn-save-edit').on('click',function(){
            save_edit_device(); 
        });
    });

    function load_ds_taiKhoanCapTrenEdit(callback,defaultValue=""){
        let url=base_url+'Account/list_taiKhoanCapTren';
        $.post(url,{},function(res){
            if(res.state=='success'){
                var data= res.data.map(function(item) {
                    return {
                        id: item.username,
                        text: item.name+" ("+item.username+")",
                    };
                });

                $('.form-edit-device .input_taiKhoanCapTren').empty();
                $('.form-edit-device .input_taiKhoanCapTren').select2({
                    data: data,
                    width: '100%',
                    
                });
                if(defaultValue!=""){
                    $(".form-edit-device .input_taiKhoanCapTren").val(defaultValue).trigger('change.select2');
                }
                callback();
                
            }
        },'JSON');
    }

    function load_ds_khuvuc_edit(callback,defaultValue=""){
        let url=base_url+'Setting/list_location';
        $.post(url,{
            input_taikhoanKH : $('.form-edit-device .input_taiKhoanCapTren').val()
        },function(res){
            if(res.state=='1'){
                var data= res.data.map(function(item) {
                    return {
                        id: item.id,
                        text: item.tenKhuVuc,
                    };
                });

                $('.form-edit-device .input_khuvuc').empty();
                $('.form-edit-device .input_khuvuc').select2({
                    data: data,
                    width: '100%',
                });               

                if(defaultValue!=""){
                    $(".form-edit-device .input_khuvuc").val(defaultValue).trigger('change.select2');
                } 
            }
        },'JSON');
    }

    function save_edit_device() {
        var idThietBi = $('.form-edit-device .input_deviceID').val().trim();
        var tenThietBi = $('.form-edit-device .input_ten').val().trim();
        var mota = $('.form-edit-device .input_mota').val().trim();
        var taiKhoanCapTren = $('.form-edit-device .input_taiKhoanCapTren').val();
        var input_idKhuVuc = $('.form-edit-device .input_khuvuc').val();
        
        var check=true;
        $('.form-edit-device input.not-null').each(function () {
            if($(this).val().trim()==""){
                $(this).addClass('is-invalid');
                $(this).parent().children('.invalid-feedback').html("Vui lòng cung cấp thông tin.");
                check=false;
            }else{
                $(this).removeClass('is-invalid');
                $(this).parent().children('.invalid-feedback').html("");
            }
        });
        
        if(check==false) return;

        var url = window.base_url + "Setting/save_edit_device";
        $.post(url,{
            idThietBi : idThietBi,
            tenThietBi : tenThietBi,
            mota : mota,
            taiKhoanCapTren : taiKhoanCapTren,
            idKhuVuc : input_idKhuVuc,
        },function(data){
            if(data.state=='success'){
                $('.container-list-device').DataTable().ajax.reload();
                alertify.notify(data.alert,'success');
                $('#modalDevice').modal('hide');
            }else{
                alertify.notify(data.alert,'error');
            }

        },'JSON');
    }
</script>
        <?php
    }
?>

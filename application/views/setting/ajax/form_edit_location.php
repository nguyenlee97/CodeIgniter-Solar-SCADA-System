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
<form class="form-edit-location " onsubmit="return false;">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalCenterTitle">Sửa thông tin khu vực</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
</div>
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-sm-12" style="display: none;">
                <label for="city">Tài khoản khách hàng</label>
                <select class="form-control input_taiKhoanCapTren" type="text" ></select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-6">
                <input class="form-control input_idKhuVuc not-null" type="hidden"
                value="<?php echo $data['infoKhuVuc']['id']; ?>"
                >
                <label for="city">Tên khu vực</label>
                <input class="form-control input_ten not-null" type="text" placeholder="Nhập tên khu vực"
                value="<?php echo $data['infoKhuVuc']['tenKhuVuc']; ?>"
                >
                <div class="device-name invalid-feedback">Vui lòng cung cấp thông tin.</div>
            </div>
            <div class="form-group col-sm-6">
                <label for="city">Đơn giá 1 kWh (VNĐ)</label>
                <input class="form-control input_donGia not-null" type="number" placeholder="Nhập đơn giá" 
                value="<?php echo $data['infoKhuVuc']['donGia']; ?>"
                >
                <div class="device-name invalid-feedback">Vui lòng cung cấp thông tin.</div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-12 ">
                <label >Mô tả</label>
                <textarea class="form-control input_mota" style="height: inherit"><?php echo $data['infoKhuVuc']['mota']; ?></textarea>
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
        },'<?php echo $data['infoKhuVuc']['taiKhoanKhachHang']; ?>');

        $('.form-edit-location input.not-null').on('keyup',function () {
            if($(this).val()==""){
                $(this).addClass('is-invalid');
                $(this).parent().children('.invalid-feedback').html("Vui lòng cung cấp thông tin.");
            }else{
                $(this).removeClass('is-invalid');
                $(this).parent().children('.invalid-feedback').html("");
            }
        });

        $('.reset-add-khuvuc').on('click',function(){
            $('.form-edit-location input').val("");
        });

        <?php 
        if($data['loaiTaiKhoan']=='quantri'){
        ?>
            $('.form-edit-location .input_taiKhoanCapTren').parent().css('display','block');
        <?php
        }
        ?>
        
        $('.btn-save-edit').on('click',function(){
            save_edit_location(); 
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

                $('.form-edit-location .input_taiKhoanCapTren').empty();
                $('.form-edit-location .input_taiKhoanCapTren').select2({
                    data: data,
                    width: '100%',
                    
                });
                if(defaultValue!=""){
                    $(".form-edit-location .input_taiKhoanCapTren").val(defaultValue).trigger('change.select2');
                }
                callback();
                
            }
        },'JSON');
    }

    function save_edit_location() {
        var tenKhuVuc = $('.form-edit-location .input_ten').val().trim();
        var input_idKhuVuc = $('.form-edit-location .input_idKhuVuc').val().trim();
        var mota = $('.form-edit-location .input_mota').val().trim();
        var taiKhoanCapTren = $('.form-edit-location .input_taiKhoanCapTren').val();
        var input_donGia = $('.form-edit-location .input_donGia').val();
       
        var check=true;
        $('.form-edit-location input.not-null').each(function () {
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

        var url = window.base_url + "Setting/save_edit_khuvuc";
        $.post(url,{
            idKhuVuc : input_idKhuVuc,
            tenKhuVuc : tenKhuVuc,
            mota : mota,
            taiKhoanCapTren : taiKhoanCapTren,
            donGia : input_donGia,
        },function(data){
            if(data.state=='success'){
                $('.container-list-khuvuc').DataTable().ajax.reload();
                alertify.notify(data.alert,'success');
                $('#modalLocation').modal('hide');
            }else{
                alertify.notify(data.alert,'error');
            }

        },'JSON');
    }
</script>
        <?php
    }
?>

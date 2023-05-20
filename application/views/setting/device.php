<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 11/13/2018
 * Time: 5:15 PM
 */
?>
<style>
    .checkbox-week .custom-control{
        margin: 10px;
    }
</style>
<script src="<?php echo base_url('public/js/CheckChar.js'); ?>"></script>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            Danh sách thiết bị
            <small></small>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-sm-6" style="display: none;">
                    <label for="city">Tài khoản khách hàng</label>
                    <select class="form-control search_taiKhoanKhachHang" ></select>
                </div>
                <div class="form-group col-sm-12" >
                    <label for="city">Khu vực lắp đặt</label>
                    <select class="form-control search_khuvuc" ></select>
                </div>
            </div>
            <table class="table table-responsive-sm table-bordered table-striped  table-sm container-list-device" style="width:100%">
                <thead>
                    <tr>
                        <th width="12" class="align-middle">STT</th>
                        <th class="align-middle">ID thiết bị</th>
                        <th class="align-middle">Tên thiết bị</th>
                        <th width="20" class="align-middle">Sửa/Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4" align="center">Không tìm thấy khu vực lắp đặt</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <form class="form-add-device " onsubmit="return false;">
                    <div class="card-header">
                        Thêm thiết bị
                        <small></small>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-sm-6" style="display: none;">
                                <label for="city">Tài khoản khách hàng</label>
                                <select class="form-control input_taiKhoanCapTren" type="text" ></select>
                            </div>
                            <div class="form-group col-sm-12" >
                                <label for="city">Khu vực lắp đặt</label>
                                <select class="form-control input_khuvuc" type="text" ></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="city">ID thiết bị</label>
                                <input class="form-control input_deviceID not-null" type="text" placeholder="Nhập ID thiết bị">
                                <div class="device-name invalid-feedback">Vui lòng cung cấp thông tin.</div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="city">Tên thiết bị</label>
                                <input class="form-control input_ten not-null" type="text" placeholder="Đặt tên cho thiết bị. Ví dụ: Inverter 1">
                                <div class="device-name invalid-feedback">Vui lòng cung cấp thông tin.</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12 ">
                                <label >Mô tả</label>
                                <textarea class="form-control input_mota" style="height: inherit"></textarea>
                            </div>
                        </div>
                    
                
                    <div class="card-footer">
                        <button class="btn btn-sm btn-success btn-add-device" type="button">
                            <i class="fas fa-check"></i> Save</button>
                        <button class="btn btn-sm btn-danger reset-add-device" type="reset">
                            <i class="fa fa-ban"></i> Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade " id="modalDevice" tabindex="-1" role="dialog" aria-labelledby="modalDevice" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content border-info">
      
    </div>
  </div>
</div>

<script>
    // load list device
    window.list_time = [];
    $(document).ready(function(){
        load_ds_taiKhoanCapTren(function(){
            // load khu vuc cho phần search danh sach
            load_ds_khuvuc_search(function(){
                load_list_device();
            });

            // load khu vuc cho form add device
            load_ds_khuvuc_add();
        });
        $('.form-add-device input.not-null').on('keyup',function () {
            if($(this).val()==""){
                $(this).addClass('is-invalid');
                $(this).parent().children('.invalid-feedback').html("Vui lòng cung cấp thông tin.");
            }else{
                $(this).removeClass('is-invalid');
                $(this).parent().children('.invalid-feedback').html("");
            }
        });

        $('.reset-add-device').on('click',function(){
            $('.form-add-device input').val("");
        });

        <?php 
        if($data['loaiTaiKhoan']=='quantri'){
        ?>
            $('.form-add-device .input_taiKhoanCapTren').parent().css('display','block');
            $('.search_taiKhoanKhachHang').parent().css('display','block');

            $('.search_khuvuc, .input_khuvuc').parent().removeClass('col-sm-12');
            $('.search_khuvuc, .input_khuvuc').parent().addClass('col-sm-6');
        <?php
        }
        ?>
        $('.search_taiKhoanKhachHang').on('change',function(){
            load_ds_khuvuc_search(function(){
                $('.container-list-device').DataTable().ajax.reload();
            }); 

            // nếu thay đổi tài khoản ở ô tìm kiếm thì kiểm tra xem trên form add giống hay khác? nếu khác thì thay đổi trên form add 
            if($(this).val()!=$('.form-add-device input_taiKhoanCapTren')){
                $('.form-add-device .input_taiKhoanCapTren').val($(this).val());
                $('.form-add-device .input_taiKhoanCapTren').trigger('change');
                load_ds_khuvuc_add();
            }
        });

        $('.search_khuvuc').on('change',function(){
            $('.container-list-device').DataTable().ajax.reload();
        });

        $('.form-add-device .input_taiKhoanCapTren').on('change',function(){
            load_ds_khuvuc_add(); 
        });
        
        $('.btn-add-device').on('click',function(){
            add_device(); 
        });
    });

    function load_list_device() {
        var url=window.base_url + "Setting/list_device";
        $('.container-list-device').DataTable( {
            "language": {
                "lengthMenu": "Hiển thị _MENU_ dòng mỗi trang",
                "infoEmpty": "",
                "search": "Tìm kiếm:",
                "info": "Hiển thị từ _START_ đến _END_ trong tổng số _TOTAL_ dòng",
                "paginate": {
                    "next": "Trang sau >",
                    "previous": "< Trang trước"
                },
                "processing": "ĐANG XỬ LÝ...",

            },
            "ajax": {
                'type': "POST",
                'url': url,
                'data': function ( d ) {
                    d.input_taikhoanKH = $('.search_taiKhoanKhachHang').val();
                    d.input_idKhuVuc = $('.search_khuvuc').val();
                }
            },
            "columns": [
                { "data": "stt" },
                { "data": "idThietBi" },
                { "data": "tenThietBi" },
                {   
                    "data": "id",
                    "render": function ( data, type, row ) {
                        let buttonEdit = '<button class="btn btn-sm btn-success mr-1" title="Sửa thông tin" onclick="edit_device(\''+row.idThietBi+'\')"><i class="fas fa-pencil-alt"></i></button>';
                        let buttonDelete = '<button class="btn btn-sm btn-danger" title="Xóa người dùng" onclick="remove_device(\''+data+'\',\''+row.tenThietBi+'\')"><i class="fas fa-trash-alt "></i></button>';
                        let button='<div>'+buttonEdit+buttonDelete+'</div>';
                        return button;
                    }
                },
            ],
            "columnDefs": [
                { className: "text-center align-middle", "targets": [0] },
                { className: "align-middle", "targets": [1,2] },
                { className: "text-center align-middle", "targets": [3] },
            ]
        } );
        
    }
    
    function load_ds_taiKhoanCapTren(callback){
        let url=base_url+'Account/list_taiKhoanCapTren';
        $.post(url,{},function(res){
            if(res.state=='success'){
                var data= res.data.map(function(item) {
                    return {
                        id: item.username,
                        text: item.name+" ("+item.username+")",
                    };
                });

                $('.form-add-device .input_taiKhoanCapTren').empty();
                $('.form-add-device .input_taiKhoanCapTren').select2({
                    data: data,
                    width: '100%',
                    
                });

                $('.search_taiKhoanKhachHang').empty();
                $('.search_taiKhoanKhachHang').select2({
                    data: data,
                    width: '100%',
                    
                });

                callback();
                
            }
        },'JSON');
    }

    function load_ds_khuvuc_search(callback){
        let url=base_url+'Setting/list_location';
        $.post(url,{
            input_taikhoanKH : $('.search_taiKhoanKhachHang').val()
        },function(res){
            if(res.state=='1'){
                var data= res.data.map(function(item) {
                    return {
                        id: item.id,
                        text: item.tenKhuVuc,
                    };
                });

                // $('.form-add-device .input_taiKhoanCapTren').empty();
                // $('.form-add-device .input_taiKhoanCapTren').select2({
                //     data: data,
                //     width: '100%',
                    
                // });

                $('.search_khuvuc').empty();
                $('.search_khuvuc').select2({
                    data: data,
                    width: '100%',
                    
                });

                callback();
                
            }
        },'JSON');
    }

    function load_ds_khuvuc_add(){
        let url=base_url+'Setting/list_location';
        $.post(url,{
            input_taikhoanKH : $('.input_taiKhoanCapTren').val()
        },function(res){
            if(res.state=='1'){
                var data= res.data.map(function(item) {
                    return {
                        id: item.id,
                        text: item.tenKhuVuc,
                    };
                });

                $('.form-add-device .input_khuvuc').empty();
                $('.form-add-device .input_khuvuc').select2({
                    data: data,
                    width: '100%',
                    
                });                
            }
        },'JSON');
    }


    function add_device() {
        var tenThietBi = $('.form-add-device .input_ten').val().trim();
        var deviceID = $('.form-add-device .input_deviceID').val().trim();
        var mota = $('.form-add-device .input_mota').val().trim();
        var taiKhoanCapTren = $('.form-add-device .input_taiKhoanCapTren').val();
        var khuvuc = $('.form-add-device .input_khuvuc ').val();
       
        var check=true;
        $('.form-add-device input.not-null').each(function () {
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

        var url = window.base_url + "Setting/add_device";
        $.post(url,{
            tenThietBi : tenThietBi,
            deviceID : deviceID,
            mota : mota,
            taiKhoanCapTren : taiKhoanCapTren,
            khuvuc : khuvuc,
        },function(data){
            if(data.state=='success'){
                $('.container-list-device').DataTable().ajax.reload();
                alertify.notify(data.alert,'success');
            }else{
                alertify.notify(data.alert,'error');
            }

        },'JSON');
    }

    function remove_device(id,name){
        alertify.confirm('Cảnh báo','<p>Bạn có chắc muốn xóa thiết bị: '+name+' ?</p><p>Xóa thiết bị cũng sẽ xóa tất cả cấu hình và lịch sử liên quan đến thiết bị này.</p>',function(){
            var url = window.base_url + "Setting/remove_device";
            $.post(url,{
                id : id,
            },function(data){
                if(data.state=='success'){
                    $('.container-list-device').DataTable().ajax.reload();
                    alertify.notify(data.alert,'success');
                }else{
                    alertify.notify(data.alert,'error');
                }

            },'JSON');
        },null);

    }
    
    function edit_device(idThietBi) {
        let id_modal = "#modalDevice";
        $(id_modal).modal('show');

        let url=base_url+"Setting/form_edit_device";
        $.post(url,{
            idThietBi: idThietBi
        },function(res){
            $(id_modal+" .modal-content").html(res);
        });
    }


</script>
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
            Danh sách khu vực lắp đặt
            <small></small>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-sm-12" style="display: none;">
                    <label for="city">Tài khoản khách hàng</label>
                    <select class="form-control search_taiKhoanKhachHang" ></select>
                </div>
            </div>
            <table class="table table-responsive-sm table-bordered table-striped  table-sm container-list-khuvuc" style="width:100%">
                <thead>
                    <tr>
                        <th width="12" class="align-middle">STT</th>
                        <th class="align-middle">Tên khu vực</th>
                        <th class="align-middle">Mô tả</th>
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
                <form class="form-add-location " onsubmit="return false;">
                    <div class="card-header">
                        Thêm khu vực lắp đặt
                        <small></small>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-sm-12" style="display: none;">
                                <label for="city">Tài khoản khách hàng</label>
                                <select class="form-control input_taiKhoanCapTren" type="text" ></select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="city">Tên khu vực</label>
                                <input class="form-control input_ten not-null" type="text" placeholder="Nhập tên khu vực">
                                <div class="device-name invalid-feedback">Vui lòng cung cấp thông tin.</div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="city">Đơn giá 1 kWh (VNĐ)</label>
                                <input class="form-control input_donGia not-null" type="number" placeholder="Nhập đơn giá" value="1940">
                                <div class="device-name invalid-feedback">Vui lòng cung cấp thông tin.</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12 ">
                                <label >Mô tả</label>
                                <textarea class="form-control input_mota" style="height: inherit"></textarea>
                            </div>
                        </div>
                    </div>
                    

                    <div class="card-footer">
                        <button class="btn btn-sm btn-success btn-add-khuvuc" type="button">
                            <i class="fas fa-check"></i> Save</button>
                        <button class="btn btn-sm btn-danger reset-add-location" type="reset">
                            <i class="fa fa-ban"></i> Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade " id="modalLocation" tabindex="-1" role="dialog" aria-labelledby="modalLocation" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content border-info">
      
    </div>
  </div>
</div>

<script>
    $(document).ready(function(){
        load_ds_taiKhoanCapTren(function(){
            load_list_location();
        });
        $('.form-add-location input.not-null').on('keyup',function () {
            if($(this).val()==""){
                $(this).addClass('is-invalid');
                $(this).parent().children('.invalid-feedback').html("Vui lòng cung cấp thông tin.");
            }else{
                $(this).removeClass('is-invalid');
                $(this).parent().children('.invalid-feedback').html("");
            }
        });

        $('.reset-add-khuvuc').on('click',function(){
            $('.form-add-location input').val("");
        });

        <?php 
        if($data['loaiTaiKhoan']=='quantri'){
        ?>
            $('.form-add-location .input_taiKhoanCapTren').parent().css('display','block');
            $('.search_taiKhoanKhachHang').parent().css('display','block');
        <?php
        }
        ?>
        $('.search_taiKhoanKhachHang').on('change',function(){
            $('.container-list-khuvuc').DataTable().ajax.reload();
        });
        
        $('.btn-add-khuvuc').on('click',function(){
            add_khuvuc(); 
        });
    });

    function load_list_location() {
        var url=window.base_url + "Setting/list_location";
        $('.container-list-khuvuc').DataTable( {
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
                }
            },
            "columns": [
                { "data": "stt" },
                { "data": "tenKhuVuc" },
                { 
                    "data": "mota",
                    "render":function(data,type,row){
                        return '<span data-toggle="tooltip" data-placement="top" title="'+data+'">'+truncate(data,50)+'</span>';
                    } 
                },
                {   
                    "data": "id",
                    "render": function ( data, type, row ) {
                        let buttonEdit = '<button class="btn btn-sm btn-success mr-1" title="Sửa thông tin" onclick="edit_khuvuc(\''+data+'\')"><i class="fas fa-pencil-alt"></i></button>';
                        let buttonDelete = '<button class="btn btn-sm btn-danger" title="Xóa người dùng" onclick="remove_khuvuc(\''+data+'\',\''+row.tenKhuVuc+'\')"><i class="fas fa-trash-alt "></i></button>';
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

                $('.form-add-location .input_taiKhoanCapTren').empty();
                $('.form-add-location .input_taiKhoanCapTren').select2({
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

    function add_khuvuc() {
        var tenKhuVuc = $('.form-add-location .input_ten').val().trim();
        var mota = $('.form-add-location .input_mota').val().trim();
        var taiKhoanCapTren = $('.form-add-location .input_taiKhoanCapTren').val();
        var input_donGia = $('.form-add-location .input_donGia').val();
       
        var check=true;
        $('.form-add-location input.not-null').each(function () {
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

        var url = window.base_url + "Setting/add_khuvuc";
        $.post(url,{
            tenKhuVuc : tenKhuVuc,
            mota : mota,
            taiKhoanCapTren : taiKhoanCapTren,
            donGia : input_donGia,
        },function(data){
            if(data.state=='success'){
                $('.container-list-khuvuc').DataTable().ajax.reload();
                alertify.notify(data.alert,'success');
            }else{
                alertify.notify(data.alert,'error');
            }

        },'JSON');
    }

    function remove_khuvuc(id,name){
        alertify.confirm('Cảnh báo','<p>Bạn có chắc muốn xóa khu vực: '+name+' ?</p><p>Xóa khu vực lắp đặt cũng sẽ xóa tất cả thiết bị và cấu hình liên quan đến khu vực này.</p>',function(){
            var url = window.base_url + "Setting/remove_khuvuc";
            $.post(url,{
                id : id,
            },function(data){
                if(data.state=='success'){
                    $('.container-list-khuvuc').DataTable().ajax.reload();
                    alertify.notify(data.alert,'success');
                }else{
                    alertify.notify(data.alert,'error');
                }

            },'JSON');
        },null);

    }
    
    function edit_khuvuc(idKhuVuc) {
        let id_modal = "#modalLocation";
        $(id_modal).modal('show');

        let url=base_url+"Setting/form_edit_location";
        $.post(url,{
            idKhuVuc: idKhuVuc
        },function(res){
            $(id_modal+" .modal-content").html(res);
        });
    }


</script>
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
            Danh sách người dùng
            <small></small>
        </div>
        <div class="card-body">
            <table class="table table-responsive-sm table-bordered table-striped  table-sm container-list-taikhoan" style="width:100%">
                <thead>
                    <tr>
                        <th width="12" class="align-middle">STT</th>
                        <th class="align-middle">Username</th>
                        <th class="align-middle">Họ tên</th>
                        <th class="align-middle">Loại tài khoản</th>
                        <th class="align-middle">Công ty</th>
                        <th width="20" class="align-middle">Sửa/Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6" align="center">Không tìm thấy tài khoản</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card border-info">
                <form class="form-add-taikhoan " onsubmit="add_taikhoan(); return false;">
                    <div class="card-header">
                        Thêm người dùng
                        <small></small>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label>Tên người dùng</label>
                                <input class="form-control input_name not-null" type="text" placeholder="Nhập họ tên người dùng">
                                <div class="invalid-feedback">Vui lòng cung cấp thông tin.</div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label>Tên công ty</label>
                                <input class="form-control input_tenCongTy" type="text" placeholder="Nhập tên công ty">
                                <div class="invalid-feedback">Vui lòng cung cấp thông tin.</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label>Username</label>
                                <input class="form-control input_username  not-null" type="text" placeholder="Nhập Username">
                                <div class="invalid-feedback">Vui lòng cung cấp thông tin.</div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="postal-code">Email</label>
                                <input class="form-control input_email" type="text" placeholder="Nhập email">
                                <div class="invalid-feedback">Vui lòng cung cấp thông tin.</div>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="postal-code">Password</label>
                                <input class="form-control input_pass  not-null" type="password" placeholder="Nhập mật khẩu">
                                <div class="invalid-feedback">Vui lòng cung cấp thông tin.</div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="postal-code">Re-Password</label>
                                <input class="form-control input_repass  not-null" type="password" placeholder="Nhập lại mật khẩu">
                                <div class="invalid-feedback">Vui lòng cung cấp thông tin.</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6 ">
                                <label >Loại tài khoản</label>
                                <select class="form-control input_loaiTaiKhoan" style="height: inherit">
                                    <?php
                                    if($data['list_loaitaikhoan_create']!=NULL){
                                        foreach($data['list_loaitaikhoan_create'] as $item){
                                            echo "<option value=\"{$item['value']}\">{$item['text']}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-6 ">
                                <label >Tài khoản phụ của khách hàng</label>
                                <select type="number" class="form-control input_taiKhoanCapTren" style="height: inherit">
                                   
                                </select>
                                
                            </div>
                            <div class="form-group col-sm-6 ">
                                <label >Số tài khoản phụ tối đa</label>
                                <input type="number" class="form-control input_maxTaiKhoanPhu" style="height: inherit" value="0">
                                
                            </div>
                        </div>
                        <div class="row ">
                            <div class="form-group col-sm-12 div-permission">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button class="btn btn-sm btn-success btn-add-taikhoan" type="button">
                            <i class="fas fa-check"></i> Save</button>
                        <button class="btn btn-sm btn-danger reset-add-taikhoan" type="reset">
                            <i class="fa fa-ban"></i> Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade " id="modalAccount" tabindex="-1" role="dialog" aria-labelledby="modalAccount" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content border-info">
      
    </div>
  </div>
</div>

<script>
    $(document).ready(function(){
        load_ds_taikhoan();

        $('.form-add-taikhoan input.not-null').on('keyup',function () {
            if($(this).val()==""){
                $(this).addClass('is-invalid');
                $(this).parent().children('.invalid-feedback').html("Vui lòng cung cấp thông tin.");
            }else{
                $(this).removeClass('is-invalid');
                $(this).parent().children('.invalid-feedback').html("");
            }
        });

        $('.form-add-taikhoan .input_username').on('keyup',function () {
            Check_username(this);
        });

        $('.form-add-taikhoan .input_email').on('keyup',function () {
            Check_email(this);
        })

        
        $('.reset-add-taikhoan').on('click',function(){
            $('.form-add-taikhoan input').val("");
        });

        $('.btn-add-taikhoan').on('click',function(){
            add_taikhoan();
        });

    
        if($('.form-add-taikhoan .input_loaiTaiKhoan').on('change',function(){
            switch($(this).val()){
                case 'quantri': {
                    $('.form-add-taikhoan .input_taiKhoanCapTren').parent().css('display','none');
                    $('.form-add-taikhoan .input_maxTaiKhoanPhu').parent().css('display','none');
                    break;
                }
                case 'nguoidung':{
                    $('.form-add-taikhoan .input_taiKhoanCapTren').parent().css('display','none');
                    $('.form-add-taikhoan .input_maxTaiKhoanPhu').parent().css('display','block');
                    break;
                }
                case 'phu':{
                    $('.form-add-taikhoan .input_taiKhoanCapTren').parent().css('display','block');
                    $('.form-add-taikhoan .input_maxTaiKhoanPhu').parent().css('display','none');
                    load_ds_khuVucQuanLy();
                    break;
                }       
            }
        }));

        $('.form-add-taikhoan .input_loaiTaiKhoan').trigger('change');
        load_ds_taiKhoanCapTren();

        if($('.form-add-taikhoan .input_taiKhoanCapTren').on('change',function(){
            load_ds_khuVucQuanLy();
        }));
        
    });

    function load_ds_taikhoan() {
        var url=window.base_url + "Account/list_taikhoan";
        $('.container-list-taikhoan').DataTable( {
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
            "ajax": url,
            "columns": [
                { "data": "stt" },
                { "data": "username" },
                { "data": "name" },
                { "data": "tenLoaiTaiKhoan" },
                { "data": "tenCongTy" },
                {   
                    "data": "username",
                    "render": function ( data, type, row ) {
                        // let buttonInfo = '<button class="btn btn-sm btn-info mr-1" title="Xem chi tiết" onclick="info_taikhoan(\''+data+'\')"><i class="far fa-file-alt"></i></button>';
                        let buttonInfo="";
                        let buttonEdit = '<button class="btn btn-sm btn-success mr-1" title="Sửa thông tin" onclick="edit_taikhoan(\''+data+'\')"><i class="fas fa-pencil-alt"></i></button>';
                        let buttonDelete = '<button class="btn btn-sm btn-danger" title="Xóa người dùng" onclick="remove_taikhoan(\''+data+'\')"><i class="fas fa-trash-alt "></i></button>';
                        let button='<div>'+buttonInfo+buttonEdit+buttonDelete+'</div>';
                        return button;
                    }
                },
            ],
            "columnDefs": [
                { className: "text-center align-middle", "targets": [0] },
                { className: "align-middle", "targets": [1] },
                { className: "align-middle", "targets": [2] },
                { className: "align-middle", "targets": [3] },
                { className: "align-middle", "targets": [4] },
                { className: "text-center align-middle", "targets": [5] },
            ]
        } );
    }

    function load_ds_taiKhoanCapTren(){
        let url=base_url+'Account/list_taiKhoanCapTren';
        $.post(url,{},function(res){
            if(res.state=='success'){
                var data= res.data.map(function(item) {
                    return {
                        id: item.username,
                        text: item.name+" ("+item.username+")",
                    };
                });

                $('.form-add-taikhoan .input_taiKhoanCapTren').empty();
                $('.form-add-taikhoan .input_taiKhoanCapTren').select2({
                    data: data,
                    width: '100%',
                    
                });
                
            }
        },'JSON');
    }

    function load_ds_khuVucQuanLy(){
        let taiKhoanCapTren = $('.form-add-taikhoan .input_taiKhoanCapTren').val();
        let url=base_url+'Account/list_KhuVucQuanLy';
        $.post(url,{
            taiKhoanCapTren: taiKhoanCapTren
        },function(res){
            if(res.state=='success'){
                var htmlPermission = "<label >Khu vực được giám sát</label>";
                if(res.data.length>0){
                    for(let i=0;i<res.data.length;i++){
                        let item = res.data[i];
                        let option = '<div class="form-check checkbox pl-5 mt-2"> ' +
                            '<label class="form-check-label" title="Mô tả: '+item.mota+' "> ' +
                            '    <input class="form-check-input input_khuvuc" type="checkbox" ' +
                            '    value="'+item.idKhuVuc+'" />'+
                                    item.tenKhuVuc +
                            '</label>' +
                            '</div>';

                        htmlPermission +=option;
                    }
                }else{
                    htmlPermission+='<div class="form-check checkbox pl-5 mt-2">Chưa có khu vực nào được cài đặt</div>';
                }
                
                $('.form-add-taikhoan .div-permission').html(htmlPermission);
            }
        },'JSON');
        
    }
    
    function add_taikhoan() {
        var name = $('.form-add-taikhoan .input_name').val().trim();
        var username = $('.form-add-taikhoan .input_username').val().trim();
        var pass = $('.form-add-taikhoan .input_pass').val().trim();
        var repass = $('.form-add-taikhoan .input_repass').val().trim();

        var tenCongTy = $('.form-add-taikhoan .input_tenCongTy').val().trim();
        var email = $('.form-add-taikhoan .input_email').val().trim();
        var loaiTaiKhoan = $('.form-add-taikhoan .input_loaiTaiKhoan').val().trim();
        var maxTaiKhoanPhu = $('.form-add-taikhoan .input_maxTaiKhoanPhu').val().trim();
        var taiKhoanCapTren = $('.form-add-taikhoan .input_taiKhoanCapTren').val();

        var input_permission = [];
        $.each($(".form-add-taikhoan .input_khuvuc[type=checkbox]:checked"), function(){
            input_permission.push($(this).val());
        });

        var check=true;
        $('.form-add-taikhoan input.not-null').each(function () {
            if($(this).val().trim()==""){
                $(this).addClass('is-invalid');
                $(this).parent().children('.invalid-feedback').html("Vui lòng cung cấp thông tin.");
                check=false;
            }else{
                $(this).removeClass('is-invalid');
                $(this).parent().children('.invalid-feedback').html("");
            }
        });

        if(username.length < 6){
            $('.form-add-taikhoan .input_username').addClass('is-invalid');
            $('.form-add-taikhoan .input_username').parent().children('.invalid-feedback').html("Username ít nhất phải có 6 ký tự.");
            check=false;
        }

        if(pass.length < 6){
            $('.form-add-taikhoan .input_username').addClass('is-invalid');
            $('.form-add-taikhoan .input_username').parent().children('.invalid-feedback').html("Password ít nhất phải có 6 ký tự.");
            check=false;
        }

        if(pass!=repass){
            $('.form-add-taikhoan .input_repass').addClass('is-invalid');
            $('.form-add-taikhoan .input_repass').parent().children('.invalid-feedback').html("Vui lòng kiểm tra lại mật khẩu.");
            check=false;
        }

        if(email!='' && check_email_submit(email)==false){
            $('.form-add-taikhoan .input_email').addClass('is-invalid');
            $('.form-add-taikhoan .input_email').parent().children('.invalid-feedback').html("Email không hợp lệ.");
            check=false;
        }

        if(check==false){
            return;
        }

        var url = window.base_url + "Account/add_taikhoan";
        $.post(url,{
            name : name,
            username : username,
            pass : pass,
            repass : repass,
            tenCongTy:tenCongTy ,
            email: email,
            loaiTaiKhoan:loaiTaiKhoan,
            maxTaiKhoanPhu:maxTaiKhoanPhu,
            taiKhoanCapTren:taiKhoanCapTren,
            permission:input_permission,
        },function(data){
            if(data.state=='success'){
                $('.container-list-taikhoan').DataTable().ajax.reload();
                if(loaiTaiKhoan=='nguoidung'){
                    load_ds_taiKhoanCapTren();
                }
                alertify.notify(data.alert,'success');
            }else{
                alertify.notify(data.alert,'error');
            }

        },'JSON');
    }

    function remove_taikhoan(username){
        alertify.confirm('Cảnh báo','<p>Bạn có chắc muốn xóa tài khoản này?</p><p>Xóa tài khoản cũng sẽ xóa tất cả tài khoản phụ và cấu hình liên quan đến tài khoản này.</p>',function(){
            var url = window.base_url + "Account/remove_taikhoan";
            $.post(url,{
                username : username,
            },function(data){
                if(data.state=='success'){
                    alertify.notify(data.alert,'success');
                    $('.container-list-taikhoan').DataTable().ajax.reload();
                }else{
                    alertify.notify(data.alert,'error');
                }

            },'JSON');
        },null);

    }
    
    function edit_taikhoan(username) {
        let id_modal = "#modalAccount";
        $(id_modal).modal('show');
        $(id_modal+" .modal-header .modal-title").html("Chỉnh sửa tài khoản tài khoản");

        let url=base_url+"Account/form_edit_account";
        $.post(url,{
            username: username
        },function(res){
            $(id_modal+" .modal-content").html(res);
        });
    }

    function info_taikhoan(username){
        let id_modal = "#modalAccount";
        $(id_modal).modal('show');
        $(id_modal+" .modal-header .modal-title").html("Thông tin tài khoản");

    }

    

</script>
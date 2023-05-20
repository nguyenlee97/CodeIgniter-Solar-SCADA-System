<?php
    if($data['allowEdit']['state']=='error'){
        ?>
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalCenterTitle">Sửa thông tin tài khoản</h5>
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
<form class="form-edit-taikhoan " onsubmit="return false;">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalCenterTitle">Sửa thông tin tài khoản</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
</div>
    <div class="modal-body">
        
            <div class="row">
                <div class="form-group col-sm-6">
                    <label>Tên người dùng</label>
                    <input class="form-control input_name not-null" type="text"
                    value="<?php echo $data['userInfo']['name']; ?>"
                    placeholder="Nhập họ tên người dùng">
                    <div class="invalid-feedback">Vui lòng cung cấp thông tin.</div>
                </div>
                <div class="form-group col-sm-6">
                    <label>Tên công ty</label>
                    <input class="form-control input_tenCongTy" type="text" 
                    value="<?php echo $data['userInfo']['tenCongTy']; ?>"
                    placeholder="Nhập tên công ty">
                    <div class="invalid-feedback">Vui lòng cung cấp thông tin.</div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6">
                    <label>Username</label>
                    <input class="form-control input_username  not-null" 
                    value="<?php echo $data['userInfo']['username']; ?>"
                    type="text" placeholder="Nhập Username" readonly>
                    <div class="invalid-feedback">Vui lòng cung cấp thông tin.</div>
                </div>
                <div class="form-group col-sm-6">
                    <label for="postal-code">Email</label>
                    <input class="form-control input_email" type="text" placeholder="Nhập email"
                    value="<?php echo $data['userInfo']['email']; ?>"
                    >
                    <div class="invalid-feedback">Vui lòng cung cấp thông tin.</div>
                </div>
                
            </div>
            <div class="row">
                <div class="form-group col-sm-6">
                    <label for="postal-code">Password</label>
                    <input class="form-control input_pass  not-null" type="password" placeholder="Nhập mật khẩu"
                    value="<?php echo $data['userInfo']['password']; ?>"
                    >
                    <div class="invalid-feedback">Vui lòng cung cấp thông tin.</div>
                </div>
                <div class="form-group col-sm-6">
                    <label for="postal-code">Re-Password</label>
                    <input class="form-control input_repass  not-null" type="password" placeholder="Nhập lại mật khẩu"
                    value="<?php echo $data['userInfo']['password']; ?>"
                    >
                    <div class="invalid-feedback">Vui lòng cung cấp thông tin.</div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6 ">
                    <label >Loại tài khoản</label>
                    <input class="form-control " type="text" readonly
                    value="<?php echo $data['userInfo']['tenLoaiTaiKhoan']; ?>"
                    >
                    <input class="form-control input_loaiTaiKhoan" type="hidden" readonly
                    value="<?php echo $data['userInfo']['loaiTaiKhoan']; ?>"
                    >
                </div>
                <div class="form-group col-sm-6 ">
                    <label >Tài khoản phụ của khách hàng</label>
                    <input type="text" class="form-control input_taiKhoanCapTren" readonly style="height: inherit" 
                        value="<?php echo $data['userInfo']['tenTaiKhoanCapTren']." (".$data['userInfo']['taiKhoanCapTren'].")"; ?>"
                    />
                </div>
                <div class="form-group col-sm-6 ">
                    <label >Số tài khoản phụ tối đa</label>
                    <input type="number" class="form-control input_maxTaiKhoanPhu" style="height: inherit" 
                    value="<?php echo $data['userInfo']['maxTaiKhoanPhu']; ?>"
                    >
                </div>
            </div>
            <div class="row ">
                <div class="form-group col-sm-12 div-permission">
                    <label >Khu vực được giám sát</label>
                    <?php
                    if($data['listKhuVuc']!=NULL){
                        foreach($data['listKhuVuc'] as $item){
                    ?>
                        <div class="form-check checkbox pl-5 mt-2">
                            <label class="form-check-label" title="Mô tả: <?php echo $item['mota']; ?>">
                                <input class="form-check-input input_khuvuc" type="checkbox" 
                                value="<?php echo $item['idKhuVuc']; ?>" <?php echo $item['checked']; ?>>
                                <?php echo $item['tenKhuVuc'] ?>
                            </label>
                        </div>
                    <?php
                        }
                    }else{
                        ?>
                        <div class="form-check checkbox pl-5 mt-2">
                        Chưa có khu vực nào được cài đặt
                        </div>
                        <?php
                    }
                    ?>
                    
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
        $('.form-edit-taikhoan input.not-null').on('keyup',function () {
            if($(this).val()==""){
                $(this).addClass('is-invalid');
                $(this).parent().children('.invalid-feedback').html("Vui lòng cung cấp thông tin.");
            }else{
                $(this).removeClass('is-invalid');
                $(this).parent().children('.invalid-feedback').html("");
            }
        });

        if($('.form-edit-taikhoan .input_loaiTaiKhoan').on('change',function(){
            switch($(this).val()){
                case 'quantri': {
                    $('.form-edit-taikhoan .input_taiKhoanCapTren').parent().css('display','none');
                    $('.form-edit-taikhoan .input_maxTaiKhoanPhu').parent().css('display','none');
                    $('.form-edit-taikhoan .div-permission').parent().css('display','none');
                    break;
                }
                case 'nguoidung':{
                    $('.form-edit-taikhoan .input_taiKhoanCapTren').parent().css('display','none');
                    $('.form-edit-taikhoan .input_maxTaiKhoanPhu').parent().css('display','block');
                    $('.form-edit-taikhoan .div-permission').parent().css('display','none');
                    break;
                }
                case 'phu':{
                    $('.form-edit-taikhoan .input_taiKhoanCapTren').parent().css('display','block');
                    $('.form-edit-taikhoan .input_maxTaiKhoanPhu').parent().css('display','none');
                    break;
                }       
            }
        }));
        $('.form-edit-taikhoan .input_loaiTaiKhoan').trigger('change');

        $('.form-edit-taikhoan .btn-save-edit').on('click',function(){
            save_edit_taikhoan();
        });
    });
    function save_edit_taikhoan() {
        var name = $('.form-edit-taikhoan .input_name').val().trim();
        var username = $('.form-edit-taikhoan .input_username').val().trim();
        var pass = $('.form-edit-taikhoan .input_pass').val().trim();
        var repass = $('.form-edit-taikhoan .input_repass').val().trim();

        var tenCongTy = $('.form-edit-taikhoan .input_tenCongTy').val().trim();
        var email = $('.form-edit-taikhoan .input_email').val().trim();
        var loaiTaiKhoan = $('.form-add-taikhoan .input_loaiTaiKhoan').val().trim();
        var taiKhoanCapTren = $('.form-add-taikhoan .input_taiKhoanCapTren').val();
        var maxTaiKhoanPhu = $('.form-edit-taikhoan .input_maxTaiKhoanPhu').val().trim();

        var check=true;
        $('.form-edit-taikhoan input.not-null').each(function () {
            if($(this).val().trim()==""){
                $(this).addClass('is-invalid');
                $(this).parent().children('.invalid-feedback').html("Vui lòng cung cấp thông tin.");
                check=false;
            }else{
                $(this).removeClass('is-invalid');
                $(this).parent().children('.invalid-feedback').html("");
            }
        });

        if(pass.length < 6){
            $('.form-edit-taikhoan .input_username').addClass('is-invalid');
            $('.form-edit-taikhoan .input_username').parent().children('.invalid-feedback').html("Password ít nhất phải có 6 ký tự.");
            check=false;
        }

        if(pass!=repass){
            $('.form-edit-taikhoan .input_repass').addClass('is-invalid');
            $('.form-edit-taikhoan .input_repass').parent().children('.invalid-feedback').html("Vui lòng kiểm tra lại mật khẩu.");
            check=false;
        }

        if(email!='' && check_email_submit(email)==false){
            $('.form-edit-taikhoan .input_email').addClass('is-invalid');
            $('.form-edit-taikhoan .input_email').parent().children('.invalid-feedback').html("Email không hợp lệ.");
            check=false;
        }

        if(check==false){
            return;
        }

        var input_permission = [];
        $.each($(".form-edit-taikhoan .input_khuvuc[type=checkbox]:checked"), function(){
            input_permission.push($(this).val());
        });

        var url = window.base_url + "Account/save_edit_taikhoan";
        $.post(url,{
            name : name,
            username : username,
            pass : pass,
            repass : repass,
            tenCongTy:tenCongTy ,
            email: email,
            taiKhoanCapTren:taiKhoanCapTren,
            maxTaiKhoanPhu:maxTaiKhoanPhu,
            permission: input_permission,
        },function(data){
            if(data.state=='success'){
                $('.container-list-taikhoan').DataTable().ajax.reload();
                $('#modalAccount').modal('hide');
                alertify.notify(data.alert,'success');
            }else{
                alertify.notify(data.alert,'error');
            }
        },'JSON');
    }
</script>
        <?php
    }
?>

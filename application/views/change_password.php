<?php
/**
 * Created by PhpStorm.
 * User: Ly Xuan Truong
 * Date: 12/20/2018
 * Time: 2:02 PM
 */
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <strong  class="title_data">Thay đổi mật khẩu</strong>
            <small></small>
        </div>
        <div class="card-body report-content" align="center">
            <div class="col-sm-12" style="max-width: 400px;" align="left">
                <form class="form-change-pass">
                    <label>Mật khẩu hiện tại</label>
                    <div class="form-group pass_show">
                        <input type="password" value="" class="form-control input_old_pass" placeholder="Current Password">
                        <div class="old_pass invalid-feedback">Vui lòng cung cấp thông tin!</div>
                    </div>
                    <label>Mât khẩu mới</label>
                    <div class="form-group pass_show">
                        <input type="password" value="" class="form-control input_new_pass" placeholder="New Password">
                        <div class="new_pass invalid-feedback">Vui lòng cung cấp thông tin!</div>
                    </div>
                    <label>Nhập lại mật khẩu mới</label>
                    <div class="form-group pass_show">
                        <input type="password" value="" class="form-control input_re_new_pass" placeholder="Confirm Password">
                        <div class="re_new_pass invalid-feedback">Vui lòng cung cấp thông tin!</div>
                    </div>
                </form>

            </div>
        </div>
        <div class="card-footer" align="center">
            <button class="btn btn-sm btn-success save-pass" type="button">
                <i class="fas fa-check"></i> Save</button>
            <button class="btn btn-sm btn-danger reset-form" type="reset">
                <i class="fa fa-ban"></i> Clear</button>
        </div>
    </div>

</div>


<script>
    $(document).ready(function(){
        $('.pass_show').append('<span class="ptxt">Hiện</span>');

        $('.reset-form').on('click',function(){
            $('.form-change-pass .input_old_pass').val("");
            $('.form-change-pass .input_new_pass').val("");
            $('.form-change-pass .input_re_new_pass').val("");
        });

        $('.form-change-pass .input_old_pass,.form-change-pass .input_new_pass,.form-change-pass .input_re_new_pass').on('keyup',function () {
            if($(this).val()==""){
                $(this).addClass('is-invalid');
                $(this).parent().children('.invalid-feedback').html("Vui lòng cung cấp thông tin!");
            }else{
                $(this).removeClass('is-invalid');
                $(this).parent().children('.invalid-feedback').html("");
            }
        })

        $('.save-pass').on('click',function(){
            var old_pass = $('.form-change-pass .input_old_pass').val();
            var new_pass = $('.form-change-pass .input_new_pass').val();
            var re_new_pass = $('.form-change-pass .input_re_new_pass').val();

            if(old_pass==""){
                $('.form-change-pass .input_old_pass').addClass('is-invalid');
                $('.old_pass.invalid-feedback').html("Vui lòng cung cấp thông tin!");
                return;
            }

            if(new_pass==""){
                $('.form-change-pass .input_new_pass').addClass('is-invalid');
                $('.new_pass.invalid-feedback').html("Vui lòng cung cấp thông tin!");
                return;
            }

            if(new_pass.length < 6 ){
                $('.form-change-pass .input_new_pass').addClass('is-invalid');
                $('.new_pass.invalid-feedback').html("Mật khẩu phải có ít nhất 6 ký tự!");
                return;
            }

            if(re_new_pass==""){
                $('.form-change-pass .input_re_new_pass').addClass('is-invalid');
                $('.re_new_pass.invalid-feedback').html("Vui lòng cung cấp thông tin!");
                return;
            }

            if(new_pass != re_new_pass){
                $('.form-change-pass .input_re_new_pass').addClass('is-invalid');
                $('.re_new_pass.invalid-feedback').html("Vui lòng kiểm tra lại mật khẩu !");
                return;
            }

            var url = window.base_url + "/login/save_change_pass";
            $.post(url,{
                old_pass: old_pass,
                new_pass: new_pass
            },function(data){
                if(data.state == "error"){
                    $('.form-change-pass .input_old_pass').addClass('is-invalid');
                    $('.old_pass.invalid-feedback').html(data.alert);
                    return;
                }else{
                    alertify.notify(data.alert,'success');
                }
            },'json');


        });
    });


    $(document).on('click','.pass_show .ptxt', function(){
        $(this).text($(this).text() == "Hiện" ? "Ẩn" : "Hiện");
        
        $(this).prev().prev().attr('type', function(index, attr){return attr == 'password' ? 'text' : 'password'; });

    });
</script>

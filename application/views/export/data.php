<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 11/13/2018
 * Time: 3:05 PM
 */

?>
<script type="text/javascript" src="<?php echo base_url('public/js/CheckChar.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('public/lib/coreui-master/chartjs/dist/js/coreui-chartjs.bundle.min.js');?>"></script>
<link href="<?php echo base_url('public/lib/coreui-master/chartjs/dist/css/coreui-chartjs.min.css');?>"></link>

<script type="text/javascript" src="<?php echo base_url('public/lib/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js');?>"></script>
<link href="<?php echo base_url('public/lib/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.css'); ?>" rel="stylesheet"></link>

<div class="container-fluid" style="margin-top: -15px;">
   
    <form action ="<?php echo base_url('Export/export'); ?>" method ="get">
    <div class="row">
        <div class="form-group col-sm-6" style="display: none;">
            <label for="city">Tài khoản khách hàng</label>
            <select class="form-control search_taiKhoanKhachHang"  id ="cusomter_id" name ="cusomter_id"></select>
        </div>
        <div class="form-group col-sm-6" >
            <label for="city">Khu vực lắp đặt</label>
            <select class="form-control search_khuvuc"  id ="area_id" name ="area_id" ></select>
        </div>
        <div class="form-group col-sm-3" >
            <label for="city">Thiết bị</label>
            <select class="form-control search_thietbi" id ="device_id" name ="device_id"  ></select>
        </div>
        <div class="form-group col-sm-3">
            <label for="city">Từ ngày</label>
            <input type='text' class="form-control pickerDayFrom ml-2" style="text-align: center;min-width:0;"  id ="from_date" name ="from_date"/>
        </div>
        <div class="form-group col-sm-3">
            <label for="city">Đến ngày</label>
            <input type='text' class="form-control pickerDay ml-2" style="text-align: center;min-width:0;"  id ="to_date" name ="to_date"/>
        </div>
        <div class="form-group col-sm-3">
            <label for="city">Loại kết xuất</label>
            <select class="form-control" aria-label="Loại kết xuất" id="data_type" name ="data_type">
                    <option value="total" selected>Dữ liệu tổng</option>
                    <option value="detail">Dữ liệu chi tiết</option>                    
            </select>
        </div>      
    </div>    
    <div class ="row">    
        <div class ="col-sm-4">
                <button class="btn btn-sm btn-success btn-add-device" type="submit">
                                        <i class="fas fa-arrow-circle-down"></i> Export</button>
        </div>
    </div>
    </form>
</div>

<script>

    $(document).ready(function(){
                   
        $('.pickerDay').datepicker({
            format: "dd/mm/yyyy",
        }).datepicker("setDate",'now');       

        load_ds_taiKhoanCapTren(function(){
            // load khu vuc cho phần search danh sach
            load_ds_khuvuc_search(function(){
                load_ds_thietbi_search(function(){               
                 
                 
                },'<?php echo $data['idThietBiSelected']; ?>');

            },'<?php echo $data['idKhuVucSelected']; ?>');

        },'<?php echo $data['taiKhoanChinhSelected']; ?>');


        $('.pickerDay').datepicker({
            format: "dd/mm/yyyy",
        }).datepicker("setDate",'now');

        var yesterDay = new Date();
        yesterDay.setDate(yesterDay.getDate() - 1);
        $('.pickerDayFrom').datepicker({
            format: "dd/mm/yyyy",
        }).datepicker("setDate",yesterDay);

        $('.search_taiKhoanKhachHang').parent().css('display','block');
               
        //------------------------------------------------------------------
        $('.search_taiKhoanKhachHang').change(function(){
            load_ds_khuvuc_search(function(){
                load_ds_thietbi_search(function(){
                
                });
            });
        });

        $('.search_khuvuc').change(function(){
            load_ds_thietbi_search(function(){   
            });
        });

      
    });

    function resetLineSun(){
        var d = new Date();
        var n = d.getHours();

        if(n < 6){
            $('.place-animation').removeClass('sun1');
            $('.place-animation').removeClass('sun2');
            $('.place-animation').removeClass('sun3');
            $('.place-animation').addClass('sun4');
        }else if( n >= 6 && n < 10){
            $('.place-animation').removeClass('sun1');
            $('.place-animation').addClass('sun2');
            $('.place-animation').removeClass('sun3');
            $('.place-animation').removeClass('sun4');
        }else if( n >= 10 && n < 15){
            $('.place-animation').addClass('sun1');
            $('.place-animation').removeClass('sun2');
            $('.place-animation').removeClass('sun3');
            $('.place-animation').removeClass('sun4');
        }else if( n >= 15 && n < 18){
            $('.place-animation').removeClass('sun1');
            $('.place-animation').addClass('sun2');
            $('.place-animation').removeClass('sun3');
            $('.place-animation').removeClass('sun4');
        }else{
            $('.place-animation').removeClass('sun1');
            $('.place-animation').removeClass('sun2');
            $('.place-animation').removeClass('sun3');
            $('.place-animation').addClass('sun4');
        }
    }

    function load_ds_taiKhoanCapTren(callback,defaultValue=""){
        let url=base_url+'Account/list_taiKhoanCapTren';
        $.post(url,{},function(res){
            if(res.state=='success'){
                var data= res.data.map(function(item) {
                    return {
                            id: item.username,
                            text: item.name+" ("+item.username+")",
                        };
                    
                });

                $('.search_taiKhoanKhachHang').empty();
                $('.search_taiKhoanKhachHang').select2({
                    data: data,
                    width: '100%',
                    allowClear: false
                });

                if(defaultValue!=""){
                    $(".search_taiKhoanKhachHang").val(defaultValue).trigger('change.select2');
                }
                
                callback();
            }
        },'JSON');
    }

    function load_ds_khuvuc_search(callback,defaultValue=""){
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

                $('.search_khuvuc').empty();
                $('.search_khuvuc').select2({
                    data: data,
                    width: '100%',
                    allowClear: false
                });

                if(defaultValue!=""){
                    $(".search_khuvuc").val(defaultValue).trigger('change.select2');
                }

                callback();
                
            }
        },'JSON');
    }

    function load_ds_thietbi_search(callback,defaultValue=""){
        let url=base_url+'Setting/list_device';
        $.post(url,{
            input_taikhoanKH : $('.search_taiKhoanKhachHang').val(),
            input_idKhuVuc : $('.search_khuvuc').val()
        },function(res){
            if(res.state=='1'){
                var data = [];
                var all ={
                    id:"all",
                    text:"Tất cả thiết bị"
                }
                data.push(all);

                var data1 = res.data.map(function(item) {
                    return {
                        id: item.idThietBi,
                        text: item.tenThietBi,
                    };
                });              
               var devices = data.concat(data1);
                $('.search_thietbi').empty();
                $('.search_thietbi').select2({
                    data: devices,
                    width: '100%',
                    allowClear: false
                });

                if(defaultValue!=""){
                    $(".search_thietbi").val(defaultValue).trigger('change.select2');
                }

                callback();
                
            }
        },'JSON');
    }




</script>
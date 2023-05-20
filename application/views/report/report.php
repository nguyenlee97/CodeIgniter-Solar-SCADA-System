<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 11/13/2018
 * Time: 3:05 PM
 */

?>
<link href="<?php echo base_url(); ?>public/css/css_layouts/jquery-ui.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>public/js/js_layouts/jquery-ui.js"></script>
<script src="<?php echo base_url(); ?>public/lib/printThis.js"></script>
<script src="<?php echo base_url(); ?>public/lib/redirect.js"></script>

<div class="container-fluid" style="margin-top: -15px;">
    <div class="row">
    <div class="form-group col-sm-6" style="display: none;">
            <label for="city">Tài khoản khách hàng</label>
            <select class="form-control search_taiKhoanKhachHang" ></select>
        </div>
        <div class="form-group col-sm-12" >
            <label for="city">Khu vực lắp đặt</label>
            <select class="form-control search_khuvuc" ></select>
        </div>
        <div class="form-group col-sm-12" >
            <label for="city">Thiết bị</label>
            <select class="form-control search_thietbi" ></select>
        </div>

        <div class="form-group col-sm-6">
            <label >Từ ngày</label>
            <input class="form-control from-date datepicker"  >
        </div>
        <div class="form-group col-sm-6">
            <label >Đến ngày</label>
            <input class="form-control to-date datepicker"  >
        </div>
        
    </div>
    <div class="row">
        <div class="form-group col-sm-12" align="center">
            <button class="btn btn-sm btn-primary" type="button" onclick=" view_data(function(){});">
                <i class="fas fa-eye"></i>
                Xem
            </button>

            <!-- <button class="btn btn-sm btn-success" type="button" onclick="printReport();">
                <i class="fas fa-print"></i>
                In
            </button> -->

            <button class="btn btn-sm btn-danger" type="button" onclick="download_excel();">
                <i class="fas fa-download"></i>
                Xuất Excel
            </button>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <strong  class="title_data"></strong>
            <small></small>
        </div>
        <div class="card-body report-content">
            <div class="header-report">
                <div align="center" class="name-report"></div>
                <div class="name_device"></div>
                <div class="location_device"></div>
                <div class="time-from-to">Device history from 01/01/2018</div>
            </div>

            <div class="container-list-data ">
                <table class="table table-responsive-sm table-bordered table-striped table-sm table-history list-data-report">
                    <tr>
                        <th width="12">STT</th>
                        <th align="center">PAC</th>
                        <th></th>
                        <th width="180">Time</th>
                    </tr>
                    <tr class="record">
                        <td colspan="4" align="center">Not found</td>
                    </tr>
                </table>
            </div>

            <div class="footer-report" align="center"></div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $('.datepicker').datepicker({
            defaultDate: new Date(),
            dateFormat: "dd/mm/yy",
        });

        $('.datepicker').datepicker('setDate', 'today');

        <?php 
        switch($data['loaiTaiKhoan']){
            case 'quantri':
        ?>
            $('.search_taiKhoanKhachHang').parent().css('display','block');

            $('.search_taiKhoanKhachHang').parent().removeClass('col-sm-6');
            $('.search_taiKhoanKhachHang').parent().addClass('col-sm-12');

            $('.search_khuvuc').parent().removeClass('col-sm-12');
            $('.search_khuvuc').parent().addClass('col-sm-6');

            $('.search_thietbi').parent().removeClass('col-sm-12');
            $('.search_thietbi').parent().addClass('col-sm-6');
        <?php
            break;
            case 'nguoidung': case 'phu':
        ?>
            $('.search_khuvuc').parent().removeClass('col-sm-12');
            $('.search_khuvuc').parent().addClass('col-sm-6');

            $('.search_thietbi').parent().removeClass('col-sm-12');
            $('.search_thietbi').parent().addClass('col-sm-6');
        <?php
            break;
        }
        ?>
        
        load_ds_taiKhoanCapTren(function(){
            // load khu vuc cho phần search danh sach
            load_ds_khuvuc_search(function(){
                load_ds_thietbi_search(function(){
                   
                });
            });
        });

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

        $('.search_thietbi').change(function(){
        });
    });

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
                var data= res.data.map(function(item) {
                    return {
                        id: item.idThietBi,
                        text: item.tenThietBi,
                    };
                });

                $('.search_thietbi').empty();
                $('.search_thietbi').select2({
                    data: data,
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

    function view_data(callback){
        var user = $('.search_taiKhoanKhachHang').val();
        var idKhuVuc = $('.search_khuvuc').val();
        var idThietBi = $('.search_thietbi').val();

        var from = $('.from-date').val();
        var to = $('.to-date').val();

        let name_report = $('.search_thietbi option:selected').text();
        $('.name-report').html(name_report);
        loadData();
    }

    function loadData(){
        var url=window.base_url + "Report/get_data_report";
        console.log();
        $('.list-data-report').DataTable( {
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
            "processing": true,
            "serverSide": true,
            "ajax": {
                'type': "POST",
                'url': url,
                'data': function ( d ) {
                    d.user = $('.search_taiKhoanKhachHang').val();
                    d.idKhuVuc = $('.search_khuvuc').val();
                    d.idThietBi = $('.search_thietbi').val();
                    d.from = $('.from-date').val();
                    d.to = $('.to-date').val();
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

    function printReport(){
        view_data(function(){
            var from = $('.from-date').val();
            var to = $('.to-date').val();
            $('.report-content').printThis({
                importCSS: false,
                loadCSS: "<?php echo base_url('public/css/print.css'); ?>",
                header: "",
                footer: "",
                base: false ,
            });
        });

    }

    function download_excel(){
        var user = $('.search_taiKhoanKhachHang').val();
        var idKhuVuc = $('.search_khuvuc').val();
        var idThietBi = $('.search_thietbi').val();

        var from = $('.from-date').val();
        var to = $('.to-date').val();
        let url = base_url+'/Report/download_report';

        $.redirect(url,
            {
                user: user,
                idKhuVuc: idKhuVuc,
                idThietBi: idThietBi,
                from: from,
                to: to,
            },
            "POST",null,null,true);

    }
</script>
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
    <div class="row">
        <div class="form-group col-sm-12" style="display: none;">
            <label for="city">Tài khoản khách hàng</label>
            <select class="form-control search_taiKhoanKhachHang" ></select>
        </div>
        <div class="col-sm-12">
            <div align="center">
                <div class="place-animation sun1">
                    <div class="line-notmove"></div>
                    <div class="line-move delay-1">
                        <img class=" " src="<?php echo base_url('/public/images/sun-move.png'); ?>" width="20" >
                    </div>
                    <div class="line-move delay-4">
                        <img class=" " src="<?php echo base_url('/public/images/sun-move.png'); ?>" width="20" >
                    </div>
                    <div class="line-move delay-8">
                        <img class=" " src="<?php echo base_url('/public/images/sun-move.png'); ?>" width="20" >
                    </div>
                    
                    <div class="icon-from"></div>
                    <div class="icon-to">
                        <img class="" src="<?php echo base_url('/public/images/sola.jpg'); ?>" width="70" height="70">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="card text-white card-accent-info" id="bieuDoPAC">
                <div class="card-body">
                    <div class="justify-content-between">
                        <div class="float-left mb-2">
                            <h4 class="card-title mb-0">PAC</h4>
                            <div class="small text-muted ghiChuThoiGian" ></div>
                        </div>
                        
                        <div class="btn-toolbar d-md-block float-right ml-2" role="toolbar" aria-label="Toolbar with buttons">
                            <div class="btn-group mr-0">
                                <input type='text' class="form-control pickerDay" style="text-align: center;min-width:0;" />
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div style="height: 300px;">
                    <canvas  id="card-chart-PAC" class=""></canvas>
                    </div>
                    <div class="clearfix"></div>
                    <a class="text-right cursor-pointer float-right linkShowtableDataTong" data-toggle="collapse" href="#tableData" data-target="#tableDataTongPAC"
                        aria-expanded="false" aria-controls="bangDuLieuTong" style="color:#ced2d8" style="font-size: 12px;">
                            Hiện bảng dữ liệu
                    </a>
                    <div class="clearfix"></div>
                    <div class="collapse " id="tableDataTongPAC" data-parent="#bieuDoTong" >
                        <table class="table table-responsive-sm table-bordered table-striped table-sm" >
                            <thead>
                                <tr>
                                    <th>Ngày</th>
                                    <th>Sản lượng</th>
                                    <th>Đơn vị</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="card text-white card-accent-info" id="bieuDoTong">
                <div class="card-body">
                    
                    <div class="justify-content-between">
                        <div class="float-left mb-2">
                            <h4 class="card-title mb-0">Sản lượng</h4>
                            <div class="small text-muted ghiChuThoiGian" ></div>
                        </div>
                        
                        <div class="btn-toolbar d-md-block float-right" role="toolbar" aria-label="Toolbar with buttons">
                            <div class="btn-group btn-group-toggle mx-0" data-toggle="buttons">
                                <!-- <label class="btn btn-outline-secondary ">
                                    <input id="optionDay" type="radio" name="options" autocomplete="off" value="day"> Day
                                </label> -->
                                <label class="btn btn-outline-secondary active">
                                    <input type="radio" name="options" autocomplete="off" value="day" checked=""> Ngày
                                </label>
                                <label class="btn btn-outline-secondary ">
                                    <input type="radio" name="options" autocomplete="off" value="month" > Tháng
                                </label>
                                <label class="btn btn-outline-secondary end-btn-group" >
                                    <input type="radio" name="options" autocomplete="off" value="year" > Năm
                                </label>
                                <input type='text' class="form-control pickerMonth ml-2" style="text-align: center; min-width:0;" />
                                <input type='text' class="form-control pickerYear ml-2" style="text-align: center; min-width:0;" />
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div style="height: 300px;">
                    <canvas  id="card-chart1"></canvas>
                    </div>
                    <a class="text-right cursor-pointer float-right linkShowtableDataTong" data-toggle="collapse" href="#tableData" data-target="#tableDataTong"
                        aria-expanded="false" aria-controls="bangDuLieuTong" style="color:#ced2d8" style="font-size: 12px;">
                            Hiện bảng dữ liệu
                    </a>
                    <div class="clearfix"></div>
                    <div class="collapse " id="tableDataTong" data-parent="#bieuDoTong" >
                        <table class="table table-responsive-sm table-bordered table-striped table-sm" >
                            <thead>
                                <tr>
                                    <th>Ngày</th>
                                    <th>Sản lượng</th>
                                    <th>Đơn vị</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ui-view">
        <div class="row" id="div-card-khuvuc" >
            
        </div>
    </div>
    
</div>

<script>
    $(document).ready(function(){
        $('.c-header-nav table').css('display','inherit');
        var ctx = document.getElementById('card-chart1');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Energy',
                    data: [],
                    backgroundColor: '#11ece5',
                    borderColor: '#11ece5',
                    borderWidth: 2,
                    maxBarThickness: 15
                }]
            },
            // fill : "#ffff00",
            options: {
                layout: {
                    padding: {
                        left: 00,
                        right: 0,
                        top: 20,
                        bottom: 0
                    },
                },
                hover: {
					mode: 'nearest',
					intersect: true,
                    animationDuration: 0
				},
                
                scales: {
                    yAxes: [{
                        scaleLabel: {
                                display: true,
                                labelString: 'ENERGY kWh',
                                fontColor:'#fff',
                                fontSize:10
                            },
                        ticks: {
                            beginAtZero: true,
                            fontColor: "#fff",
                        },
                        gridLines: { color: "#485264" }
                    }],
                    xAxes: [{
                        scaleLabel: {
                                display: true,
                                labelString: '',
                                fontColor:'#fff',
                                fontSize:10
                            },
                        ticks: {
                            beginAtZero: true,
                            fontColor: "#fff",
                        },
                        gridLines: { color: "#485264" }
                    }]
                },
                tooltips: {
                    intersect: true,
                    mode: 'point',
                },
                legend: null,
                elements: {
                    line: {
                        fill: false,
                        backgroundColor: '#fff',
                        borderColor: '#fff',
                    },
                    point: {
                        backgroundColor: '#5CB6B0',
                        hoverBackgroundColor: '#5CB6B0',
                        radius: 3,
                        // pointStyle: alternatePointStyles,
                        hoverRadius: 6,
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });

        var ctxPAC = document.getElementById('card-chart-PAC');
        var myChartPAC = new Chart(ctxPAC, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'PAC',
                    data: [],
                    backgroundColor: '#11ece5',
                    borderColor: '#11ece5',
                    pointRadius: 0,
                    // borderWidth: 2,
                    // maxBarThickness: 15
                }]
            },
            // fill : "#ffff00",
            options: {
                layout: {
                    padding: {
                        left: 0,
                        right: 0,
                        top: 20,
                        bottom: 0
                    },
                },
                hover: {
					mode: 'nearest',
					intersect: true,
                    animationDuration: 0
				},
                
                scales: {
                    yAxes: [{
                        scaleLabel: {
                                display: true,
                                labelString: 'W',
                                fontColor:'#fff',
                                fontSize:10
                            },
                        ticks: {
                            beginAtZero: true,
                            fontColor: "#fff",
                        },
                        gridLines: { 
                            color: "#485264",
                            // drawBorder: false
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                                display: true,
                                labelString: '',
                                fontColor:'#fff',
                                fontSize:10
                            },
                        ticks: {
                            beginAtZero: true,
                            fontColor: "#fff",
                        },
                        gridLines: { color: "#485264" }
                    }]
                },
                tooltips: {
                    intersect: false,
					mode: 'index',
					callbacks: {
						label: function(tooltipItem, myData) {
							var label = myData.datasets[tooltipItem.datasetIndex].label || '';
							if (label) {
								label += ': ';
							}
							label += parseFloat(tooltipItem.value).toFixed(2);
							return label;
						}
					}
                },
                legend: null,
                elements: {
                    line: {
                        fill: false,
                        backgroundColor: '#fff',
                        borderColor: '#fff',
                    },
                    point: {
                        backgroundColor: '#5CB6B0',
                        hoverBackgroundColor: '#5CB6B0',
                        radius: 3,
                        // pointStyle: alternatePointStyles,
                        hoverRadius: 6,
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });


        $('#bieuDoTong .pickerYear').datepicker({
            format: "yyyy",
            viewMode: "years", 
            minViewMode: "years"
        }).datepicker("setDate",'now');

        $('#bieuDoTong .pickerMonth').datepicker({
            format: "mm-yyyy",
            viewMode: "months", 
            minViewMode: "months"
        }).datepicker("setDate",'now');

        $('.pickerDay').datepicker({
            format: "dd/mm/yyyy",
        }).datepicker("setDate",'now');

        var option = $("#bieuDoTong input:radio[name=options]:checked").val();
        switch(option){
            case 'day': 
                $('#bieuDoTong .pickerMonth').css('display','inherit');
                $('#bieuDoTong .pickerYear').css('display','none');
            break;
            case 'month': 
                $('#bieuDoTong .pickerMonth').css('display','none');
                $('.pickerYear').css('display','inherit');
            break;
            case 'year': 
                    $('#bieuDoTong .pickerMonth').css('display','none');
                    $('#bieuDoTong .pickerYear').css('display','none');
            break;
        }

        load_ds_taiKhoanCapTren(function(){
            update_data_chart(myChart);
            update_data_chartPAC(myChartPAC);
            renderCardKhuVuc();
            
        });

        $("#bieuDoTong input:radio[name=options]").on('change',function(){
            var option = $("#bieuDoTong input:radio[name=options]:checked").val();
            switch(option){
                case 'day': 
                    $('#bieuDoTong .pickerMonth').css('display','inherit');
                    $('#bieuDoTong .pickerYear').css('display','none');
                break;
                case 'month': 
                    $('#bieuDoTong .pickerMonth').css('display','none');
                    $('.pickerYear').css('display','inherit');
                break;
                case 'year': 
                    $('#bieuDoTong .pickerMonth').css('display','none');
                    $('#bieuDoTong .pickerYear').css('display','none');
                break;
            }

            update_data_chart(myChart);
        });

        <?php 
        if($data['loaiTaiKhoan']=='quantri'){
        ?>
            $('.search_taiKhoanKhachHang').parent().css('display','block');
        <?php
        }
        ?>

        //------------------------------------------------------------------
        $('.search_taiKhoanKhachHang').change(function(){
            update_data_chart(myChart);
            update_data_chartPAC(myChartPAC);
            renderCardKhuVuc();
        });

        $('#bieuDoTong .pickerMonth, #bieuDoTong .pickerYear').change(function(){
            update_data_chart(myChart);
        });

        $('.linkShowtableDataTong').on('click',function(){
            if($(this).html()=="Ẩn bảng dữ liệu"){
                $(this).html("Hiện bảng dữ liệu");
            }else{
                $(this).html("Ẩn bảng dữ liệu");
            }
        });

        $('.pickerDay').change(function(){
            update_data_chartPAC(myChartPAC);
        });
        resetLineSun();

        setInterval(function(){
            update_data_chart(myChart);
            update_data_chartPAC(myChartPAC);
            renderCardKhuVuc();
            resetLineSun();
        }, 180000);
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

                $('.search_taiKhoanKhachHang').empty();
                $('.search_taiKhoanKhachHang').select2({
                    data: data,
                    width: '100%',
                    
                });

                callback();
                
            }
        },'JSON');
    }

    function update_data_chart(chart){
        // return;
        var option = $("input:radio[name=options]:checked").val();
        var user = $('.search_taiKhoanKhachHang').val();
        var monthYear = $('.pickerMonth').val();
        var year = $('.pickerYear').val();
        
        // get data 
        let url = base_url+'/Dashboard/get_data_DashboardAll';
        $.post(url,{
            'option': option,
            'user': user,
            'monthYear': monthYear,
            'year': year
        },function(response){
            // let data = response.data;
            switch(response.state){
                case 'success':{
                    $('#bieuDoTong .card-title').html("Sản lượng tất cả hệ thống");
                    $('#bieuDoTong .ghiChuThoiGian').html(response.ghiChuThoiGian);
                    let data = response.data;
                    let regions = [];
                    let values = [];
                    data.forEach((item) => {
                        item.value=formatNumber(item.value);
                        regions.push(item.label);
                        values.push(item.value);
                    });

                    chart.data.labels = regions;
                    chart.data.datasets[0].data = values;

                    chart.options.scales.yAxes[0].scaleLabel.labelString =  "ENERGY "+response.donvi;
                    chart.update();

                    // render bang du lieu
                    let tenCot="";
                    let tbody="";
                    data.forEach((item) => {
                        item.value=formatNumber(item.value);
                        let tenDong="";
                        switch(option){
                            case 'day': 
                                tenCot ="Ngày";
                                tenDong = item.label + "-" + monthYear;
                            break;
                            case 'month': 
                                tenCot ="Tháng";
                                tenDong= "Tháng "+ item.label.substring(1,3) ;
                            break;
                            case 'year': 
                                tenCot ="Năm";
                                tenDong= "Năm "+item.label;
                            break;
                        }

                        tbody+="<tr>" +
                            "<td class='text-center'>"+tenDong+"</td>" +
                            "<td class='text-right' >"+item.value+"</td>" +
                            "<td class='text-right'>"+response.donvi+"</td>" +
                        "</tr>";
                    });
                    let thead = "<tr>" +
                        "<th class='text-center'>"+tenCot+"</th>" +
                        "<th class='text-center'>Sản lượng</th>" +
                        "<th class='text-center'>Đơn vị</th>" +
                    "</tr>";

                    $('#tableDataTong table thead').html(thead);
                    $('#tableDataTong table tbody').html(tbody);
                    break;
                }
                default: {

                    break;
                }
                
            }
            
        },'json');
        
    }

    function renderCardKhuVuc(){
        var user = $('.search_taiKhoanKhachHang').val();
        let url = base_url+'/Dashboard/get_EnergyCurrentOfKhuVuc';
        $.post(url,{
            'user': user,
        },function(response){
            switch(response.state){
                case 'success':
                    let listKhuVuc = response.data;
                    let htmlListKhuVuc = "";
                    let headerTongPAC = 0;
                    for(let i=0;i<listKhuVuc.length;i++){
                        let khuvuc = listKhuVuc[i];
                        if(khuvuc.PAC.Unit=='kW'){
                            headerTongPAC += parseFloat(khuvuc.PAC.Value);
                        }else{
                            headerTongPAC += (parseFloat(khuvuc.PAC.Value)/1000);
                        }
                       
                        let htmlCardKhuVuc = '<div class="form-group col-sm-4" >'+
                        '    <div class="card border-info"> '+
                        '        <div class="card-header"> '+
                                    khuvuc.tenKhuVuc +
                        '        </div> '+
                        '       <div class="card-body pt-1"> '+
                        // PAC
                        '            <div class="row b-b-1 mb-3 pt-0 pb-2 border-color-dask" > '+
                        '                <div class="col"> '+
                        '                    <div class="text-uppercase text-muted text-value-xl">PAC</div> '+
                        '                    <div class="text-value-xl text-center">'+ formatNumber(khuvuc.PAC.Value) + ' '+ formatDonVi(khuvuc.PAC.Unit) +'</div> '+
                        '                </div> '+
                        '                <div class="c-vr"></div> '+
                        '                <div class="col"> '+
                        '                    <div class="text-value-xl ">'+ formatNumber(khuvuc.total.Value) + ' '+ formatDonVi(khuvuc.total.Unit) +'</div> '+
                        '                    <div class="text-uppercase text-muted small">Tổng sản lượng</div> '+
                        '                    <div class="text-value-xl ">'+ khuvuc.total.tien + ' VNĐ</div> '+
                        '                    <div class="text-uppercase text-muted small">Tổng tiền</div> '+
                        '                </div> '+
                        '            </div> '+
                        // Cong Suat
                        '            <div class="row"> '+
                        '                <div class="col"> '+
                        '                    <div class="text-value-lg">'+ formatNumber(khuvuc.day.Value) + ' '+ formatDonVi(khuvuc.day.Unit) +'</div> '+
                        '                    <div class="text-uppercase text-muted small">Sản lượng ngày</div> '+
                        '                    <div class="text-value-lg pt-1">$'+ khuvuc.day.tien +' VNĐ</div> '+
                        '                    <div class="text-uppercase text-muted small">Tiền trong ngày</div> '+
                        '                </div> '+
                        '                <div class="c-vr"></div> '+
                        '                <div class="col"> '+
                        '                    <div class="text-value-lg">'+ formatNumber(khuvuc.month.Value) + ' '+ formatDonVi(khuvuc.month.Unit) +'</div> '+
                        '                    <div class="text-uppercase text-muted small">Sản lượng tháng</div> '+
                        '                    <div class="text-value-lg pt-1">$'+ khuvuc.month.tien +' VNĐ</div> '+
                        '                    <div class="text-uppercase text-muted small">Tiền trong tháng</div> '+
                        '                </div> '+
                        '            </div> '+
                        '       </div> '+
                        '       <div class="card-footer"> '+
                        '            <div class="row"> '+
                        '                <div class="col"> '+
                        '                   <div>Tổng số thiết bị: '+khuvuc.soluongInverter+'</div>' +
                        '                   <div>Online: '+khuvuc.soluongInverterOnline+'</div>' +
                        '                </div> '+
                        '                <div class="c-vr"></div> '+
                        '                <div class="col"> '+
                        '                   <button class="btn btn-info-theme btn-xemkhuvuc" idKhuVuc='+khuvuc.idKhuVucLapDat+'>Chi tiết khu vực</button>' +
                        '                </div> '+
                        '            </div> '+
                        '       </div> '+
                        '    </div> '+
                        '</div>';

                        htmlListKhuVuc+=htmlCardKhuVuc;
                    }

                    $('#div-card-khuvuc').html(htmlListKhuVuc);
                    $('.btn-xemkhuvuc').on('click',function(){
                        let idKhuVuc = $(this).attr('idKhuVuc');
                        window.location.href = base_url+'/Dashboard/khuvuc/'+user+'/'+idKhuVuc+"/";
                    });

                    let PACHeader_donvi="";
                    if(isNaN(headerTongPAC)){
                        headerTongPAC="";
                        PACHeader_donvi="";
                    }else{
                        PACHeader_donvi="kW";
                        if(headerTongPAC<1){
                            headerTongPAC=headerTongPAC*1000;
                            PACHeader_donvi="W";
                        }
                        headerTongPAC = formatNumber(headerTongPAC);
                    }
                    $('.c-header-nav .dataDong1').html('PAC cả hệ thống: '+ formatNumber(headerTongPAC) +' '+ formatDonVi(PACHeader_donvi));
                break;
                default: 
                break;
            }
        },'json');
    }

    function update_data_chartPAC(chart){
        // return;
        var option = $("input:radio[name=options]:checked").val();
        var user = $('.search_taiKhoanKhachHang').val();
        var idKhuVuc = $('.search_khuvuc').val();
        var idThietBi = $('.search_thietbi').val();
        var monthYear = $('.pickerMonth').val();
        var year = $('.pickerYear').val();
        var day = $('.pickerDay').val();
        
        // get data 
        let url = base_url+'/Dashboard/get_data_DashboardPAC';
        option='hour';
        $.post(url,{
            'option': option,
            'user': user,
            'idKhuVuc': idKhuVuc,
            'idThietBi': idThietBi,
            'monthYear': monthYear,
            'year': year,
            'day': day,
        },function(response){
            // let data = response.data;
            switch(response.state){
                case 'success':{
                    $('#bieuDoPAC .card-title').html("PAC tất cả hệ thống");
                    $('#bieuDoPAC .ghiChuThoiGian').html(response.ghiChuThoiGian);
                    let data = response.data;
                    let regions = [];
                    let values = [];
                    data.forEach((item) => {
                        item.value=formatNumber(item.value);
                        regions.push(item.label);
                        values.push(item.value);
                    });

                    chart.data.labels = regions;
                    chart.data.datasets[0].data = values;

                    chart.options.scales.yAxes[0].scaleLabel.labelString =  "PAC "+response.donvi;
                    chart.update();

                    // render bang du lieu
                    let tenCot="";
                    let tbody="";
                    data.forEach((item) => {
                        item.value=formatNumber(item.value);
                        let tenDong="";
                        switch(option){
                            case 'hour': 
                                tenCot ="Giờ";
                                tenDong = item.label;
                            break;
                            case 'day': 
                                tenCot ="Ngày";
                                tenDong = item.label + "-" + monthYear;
                            break;
                            case 'month': 
                                tenCot ="Tháng";
                                tenDong= "Tháng "+ item.label.substring(1,3) ;
                            break;
                            case 'year': 
                                tenCot ="Năm";
                                tenDong= "Năm "+item.label;
                            break;
                        }

                        tbody+="<tr>" +
                            "<td class='text-center'>"+tenDong+"</td>" +
                            "<td class='text-right' >"+item.value+"</td>" +
                            "<td class='text-right'>"+response.donvi+"</td>" +
                        "</tr>";
                    });
                    let thead = "<tr>" +
                        "<th class='text-center'>"+tenCot+"</th>" +
                        "<th class='text-center'>Công suất</th>" +
                        "<th class='text-center'>Đơn vị</th>" +
                    "</tr>";

                    $('#tableDataTongPAC table thead').html(thead);
                    $('#tableDataTongPAC table tbody').html(tbody);

                    $('.c-header-nav .dataDong2 .PACmax').html(formatNumber(response.PAC_max) +' '+ formatDonVi(response.donvi));
                    $('.c-header-nav .dataDong2 .PACmin').html(formatNumber(response.PAC_min) +' '+ formatDonVi(response.donvi));
                    break;
                }
                default: {

                    break;
                }
                
            }
            window.loadingChart==false;
        },'json');
    }
</script>
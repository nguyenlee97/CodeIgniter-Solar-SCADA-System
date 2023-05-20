<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <strong>Request</strong>
            <small></small>
        </div>
        <div class="card-body">
            <h5>URL: <small style="font-size: 15px"><?php echo base_url('Api/post_data'); ?></small></h5>
            <h5>Method: <small style="font-size: 15px">POST</small></h5>
            <h5>Content-Type: <small style="font-size: 15px">application/json</small></h5>
            <h5>Input:</h5>

            <table class="table table-bordered" border="1" style="border-collapse: collapse">
                <tr>
                    <th style="width: 150px">Tham số</th>
                    <th class="text-center" style="width: 100px">Kiểu</th>
                    <th class="text-center">Yêu cầu</th>
                    <th>Mô tả</th>
                </tr>
                <tr>
                    <td>DATA</td>
                    <td align="center">array</td>
                    <td align="center">Có</td>
                    <td>
                        <p>Dữ liệu ghi nhận:</p>
                        DAY_ENERGY, MONTH_ENERGY, YEAR_ENERGY, PAC, NUM_AC, AC_A, AC_B, AC_C, FREQUENCY_AC, INTERNAL_TEMP, PV_STRING</td>
                </tr>  
                <tr>
                    <td>HEAD</td>
                    <td align="center">array</td>
                    <td align="center">Có</td>
                    <td><p>Thông tin thiết bị</p>
                        DEVICE, Status, GateWay
                    </td>
                </tr>   
                <tr>
                    <td>Timestamp</td>
                    <td align="center">string</td>
                    <td align="center">Có</td>
                    <td><p>Thời gian ghi nhận tín hiệu </p>
                        Format: dd/md/yyyy H:i:s (Ví dụ: <?php echo gmdate('d/m/Y H:i:s',time()+7*3600); ?>)
                    </td>
                </tr>
                <tr>
                    <td>TimeSend</td>
                    <td align="center">string</td>
                    <td align="center">Có</td>
                    <td><p>Thời gian gửi tín hiệu </p>
                        Format: dd/mm/yyyy H:i:s (Ví dụ: <?php echo $data['currentTime']; ?>)
                        <!-- <p>Test: dd/mm/yyyy H:i:s (Ví dụ: <?php // echo $data['currentTimeConvert']; ?>)</p>
                        <p><?php // echo date_default_timezone_get (); ?></p> -->
                        <!-- <p>Memory_limit: <?php // echo ini_get('memory_limit'); ?></p> 
                        <p>Memory_limit new: <?php // ini_set('memory_limit', '2048M'); echo ini_get('memory_limit'); ?></p>  -->
                    </td>
                </tr>     
                <tr>
                    <td>TOKEN</td>
                    <td align="center">string</td>
                    <td align="center">Có</td>
                    <td>Mã xác thực &rarr;
                        <span style="color: transparent;"> Xem trong Email </span> &larr;
                    </td>
                </tr>         
            </table>

            <h5>Example: <small>Jquery Request</small></h5>
            <figure class="highlight">
            <pre style="background-color:#fff; font-size: 13px">
                <code class="language-js" data-lang="js" style="padding-left: 20px; padding-right:20px;">
    var settings = {
        "url": "<?php echo base_url('Api/post_data'); ?>",
        "method": "POST",
        "timeout": 0,
        "headers": {
            "Content-Type": "application/json",
            "Cookie": "ci_session=onsi1d6pqr631nks7hkje88cumueec02"
        },
        "data": JSON.stringify({"DATA":{"DAY_ENERGY":{"Unit":"kWh","Value":"173.24"},"MONTH_ENERGY":{"Unit":"kWh","Value":"274.6"},"YEAR_ENERGY":{"Unit":"kWh","Value":"338.3"},"PAC":{"Unit":"W","Value":"35.15"},"NUM_AC":"3","AC_A":{"UAC":{"Unit":"W","Value":"153.1"},"IAC":{"Unit":"W","Value":"153.1"}},"AC_B":{"UAC":{"Unit":"W","Value":"153.1"},"IAC":{"Unit":"W","Value":"153.1"}},"AC_C":{"UAC":{"Unit":"W","Value":"153.1"},"IAC":{"Unit":"W","Value":"153.1"}},"FREQUENCY_AC":{"Unit":"Hz","Value":"49.54"},"INTERNAL_TEMP":{"Unit":"Do C","Value":"55.6"},"PV_STRING":[{"ID_STRING":"String1","PV_UDC":{"Unit":"V","Value":"550.5"},"PV_IDC":{"Unit":"A","Value":"10.3"}},{"ID_STRING":"String2","PV_UDC":{"Unit":"V","Value":"550.5"},"PV_IDC":{"Unit":"A","Value":"9.5"}}]},"HEAD":{"DEVICE":{"DeviceID":"asdfa","SERI":"ABC12345678","DeviceClass":"Inverter","Manufacturer":"ZERVER SOLAR","Model":"acv123","Device_Address":"12"},"Status":{"Code":0,"Reason":"","UserMessage":""},"GateWay":{"Status":"normal","LastError":"no error","Firmware":"1.2","Model":"ABC"}},"Timestamp":"08/08/2020 10:06:33","TimeSend":"08/08/2020 10:07:33","TOKEN":"a782997f976359482a4cedce9932ec32"}),
    };

    $.ajax(settings).done(function (response) {
    console.log(response);
    });
                </code>
            </pre>
            </figure>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <strong>Response</strong>
            <small></small>
        </div>
        <div class="card-body">

            <h5>Content-Type: <small style="font-size: 15px">application/json</small></h5>
            <table class="table table-bordered" border="1" style="border-collapse: collapse">
                <tr>
                    <th style="width: 150px">Tên trường</th>
                    <th class="text-center" style="width: 100px">Kiểu</th>
                    <th>Mô tả</th>
                </tr>
                <tr>
                    <td>state</td>
                    <td align="center">string</td>
                    <td>Mã trạng thái gọi API
                        <ul>
                            <li>error : Gọi API lỗi</li>
                            <li>success : Gọi API thành công</li>
                        </ul>
                    </td>
                </tr>     
                <tr>
                    <td>alert</td>
                    <td align="center">string</td>
                    <td>Ghi chú kết quả gọi API
                        
                    </td>
                </tr>     
                <tr>
                    <td>data</td>
                    <td align="center">array</td>
                    <td>Mảng dữ liệu trả về nếu có</td>
                </tr>  
            </table>

            <h5>Example: <small>Response data</small></h5>
            <figure class="highlight">
            <pre style="background-color:#fff; font-size: 13px">
                <code class="language-js" data-lang="js" style="padding-left: 20px; padding-right:20px;">
    {"state":"success","alert":"POST DATA SUCCESS!"}
                </code>
            </pre>
            </figure>
            <br>
        </div>
    </div>
</div>
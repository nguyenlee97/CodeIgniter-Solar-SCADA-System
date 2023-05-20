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
<div class="container">
    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i>
            <strong>List devices</strong>
            <small></small>
        </div>
        <div class="card-body">
            <div class="container-list-device">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <form class="form-add-device " onsubmit="add_device(); return false;">
                    <div class="card-header">
                        <i class="far fa-plus-square"></i>
                        <strong>Add your devices</strong>
                        <small></small>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="city">Name</label>
                                <input class="form-control input_name_device" type="text" placeholder="Enter name">
                                <div class="device-name invalid-feedback">Please provide information.</div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="postal-code">Device ID</label>
                                <input class="form-control input_device_id" type="text" placeholder="Enter device id">
                                <div class="device-id invalid-feedback">Please provide information.</div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="postal-code">Location</label>
                                <input class="form-control input_location" type="text" placeholder="Enter location">
                                <div class="device-id invalid-feedback">Please provide information.</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-2 ">
                                <label >Frequency</label>
                                <select class="form-control custom-select Frequency_occurs" style="height: inherit">
                                    <option value="every">Occurs every</option>
                                    <option value="at">Occurs at</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-2 div-input_every_hour">
                                <label>&nbsp; </label>
                                <div class="input-group">
                                    <input type="number" class="form-control input_every_hour"  placeholder="1" aria-describedby="btnGroupAddon"
                                           value="1"
                                           min="0">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text" id="btnGroupAddon">Hour(s)</div>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group col-sm-3 div-input_at" style="display: none">
                                <label>&nbsp; </label>
                                <div class="input-group">
                                    <input type="number" class="form-control time-at" placeholder="" value="1"  aria-describedby="btnGroupAddon" max="23" min="0">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text" id="btnGroupAddon">o'clock</div>
                                        <button type="button" class="input-group-text form-control add-time-at">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="list-time input_at">

                        </ul>
                    </div>

                    <div class="card-footer">
                        <button class="btn btn-sm btn-success" type="submit">
                            <i class="fas fa-check"></i> Save</button>
                        <button class="btn btn-sm btn-danger reset-add-device" type="reset">
                            <i class="fa fa-ban"></i> Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // load list device
    window.list_time = [];
    $(document).ready(function(){
        load_list_device();

        $('.form-add-device .input_name_device').on('keyup',function () {
            if($(this).val()==""){
                $('.form-add-device .input_name_device').addClass('is-invalid');
                $('.device-name.invalid-feedback').html("Please provide information.");
            }else{
                $('.form-add-device .input_name_device').removeClass('is-invalid');
                $('.device-name.invalid-feedback').html("");
            }
        })

        $('.form-add-device .input_device_id').on('keyup',function () {
            if($(this).val()==""){
                $('.form-add-device .input_device_id').addClass('is-invalid');
                $('.device-id.invalid-feedback').html("Please provide information.");
            }else{
                $('.form-add-device .input_device_id').removeClass('is-invalid');
                $('.device-id.invalid-feedback').html("");
            }
        })

        $('.reset-add-device').on('click',function(){
            $('.form-add-device .input_name_device').val("");
            $('.form-add-device .input_device_id').val("");
        });

        $('.Frequency_occurs').change(function(){
            if($(this).val()=='at'){
                $('.div-input_every_hour').css('display','none');
                $('.div-input_at,.input_at').css('display','block');
            }else{
                $('.div-input_every_hour').css('display','block');
                $('.div-input_at,.input_at').css('display','none');
            }
        });

        $('.add-time-at').on('click',function(){
            var h = $('.time-at').val();
            if(window.list_time.indexOf(h)==-1){
                window.list_time.push(h);
            }

            $('.input_at').html("");
            for(var i=0;i<window.list_time.length;i++){
                $('.input_at').append("<li>"+window.list_time[i]+" o'clock &nbsp;&nbsp;<i onclick='remove_time("+i+");' class=\"far fa-times-circle cursor-pointer \"></i></li>");
            }


        });

    });

    function remove_time(i) {
        window.list_time.splice(i, 1);
        $('.input_at').html("");
        for(var i=0;i<window.list_time.length;i++){
            $('.input_at').append("<li>"+window.list_time[i]+" o'clock &nbsp;&nbsp;<i onclick='remove_time("+i+");' class=\"far fa-times-circle cursor-pointer \"></i></li>");
        }
    }

    function load_list_device() {
        var url=window.base_url + "setting/list_devices";
        $.post(url,{},function(data){
            $('.container-list-device').html(data);
        });
    }
    
    function add_device() {
        var name = $('.form-add-device .input_name_device').val();
        var location = $('.form-add-device .input_location').val();
        var deviceID = $('.form-add-device .input_device_id').val();
        var Frequency_occurs = $('.form-add-device .Frequency_occurs').val();

        if(deviceID==""){
            $('.form-add-device .input_device_id').addClass('is-invalid');
            return;
        }else{
            $('.form-add-device .input_device_id').removeClass('is-invalid');
        }

        if(name==""){
            $('.form-add-device .input_name_device').addClass('is-invalid');
            return;
        }else{
            $('.form-add-device .input_name_device').removeClass('is-invalid');
        }

        if(name==""){
            $('.form-add-device .input_name_device').addClass('is-invalid');
            return;
        }else{
            $('.form-add-device .input_name_device').removeClass('is-invalid');
        }

        if(Frequency_occurs=='at'){
            var occurs = window.list_time;
        }else{
            var occurs = $('.input_every_hour').val();
        }
        var frequency = {
            type: Frequency_occurs,
            occurs: occurs
        }

        var url = window.base_url + "setting/add_device";
        $.post(url,{
            name : name,
            location : location,
            deviceID : deviceID,
            frequency : frequency,
        },function(data){
            if(data.state=='success'){
                load_list_device();
                alertify.notify(data.alert,'success');
            }else{
                alertify.notify(data.alert,'error');
            }

        },'JSON');
    }

    function remove_device(deviceID){
        alertify.confirm('Warning','Are you sure you want to remove?',function(){
            var url = window.base_url + "setting/remove_device";
            $.post(url,{
                deviceID : deviceID,
            },function(data){
                if(data.state=='success'){
                    load_list_device();
                    alertify.notify(data.alert,'success');
                }else{
                    alertify.notify(data.alert,'error');
                }

            },'JSON');
        },null);

    }
    
    function edit_device(deviceID) {
        window.location.href="<?php echo base_url('Setting/edit/'); ?>"+deviceID;
    }


</script>
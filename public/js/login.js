function check_login(){
    var usn = $('.login-form .u_name').val();
    var pas = $('.login-form .u_pass').val();

    if(usn==""){
        $('.login-form .alert').html("Enter your username please!");
        return;
    }else if(pas==""){
        $('.login-form .alert').html("Enter your password please!");
        return;
    }

    var url = window.base_url + 'login';
    $.post(url,{
        usn: usn,
        pas: pas,
    },function(data){
        if(data.state=='success'){
            $('.login-form .alert').html(data.alert);
            window.location.reload();
        }else{
            $('.login-form .alert').html(data.alert);
        }
    },'json');
}

function register(){
    var fullname = $('.register-form .u_fullname').val();
    var usn = $('.register-form .u_name').val();
    var pas = $('.register-form .u_pass').val();

    if(fullname==""){
        $('.register-form .alert').html("Enter your full name please!");
        return;
    }else if(usn==""){
        $('.register-form .alert').html("Enter your name please!");
        return;
    }else if(pas==""){
        $('.register-form .alert').html("Enter your password please!");
        return;
    }

    var url = window.base_url + 'login/register';
    $.post(url,{
        fullname: fullname,
        usn: usn,
        pas: pas,
    },function(data){
        if(data.state=='success'){
            $('.register-form .alert').html(data.alert);
        }else{
            $('.register-form .alert').html(data.alert);
        }
    },'json');
}

function CheckNumber(number) {
	var pattern = "0123456789.";
	var len = number.value.length;
	if (len != 0) {
		var index = 0;
		
		while ((index < len) && (len != 0))
			if (pattern.indexOf(number.value.charAt(index)) == -1) {
				if (index == len-1)
					number.value = number.value.substring(0, len-1);
				else if (index == 0)
					 	number.value = number.value.substring(1, len);
					 else number.value = number.value.substring(0, index)+number.value.substring(index+1, len);
				index = 0;
				len = number.value.length;
			}
			else index++;
	}
}

function Check_sodienthoai(number) {
	var pattern = "0123456789";
	var len = number.value.length;
	if (len != 0) {
		var index = 0;
		
		while ((index < len) && (len != 0))
			if (pattern.indexOf(number.value.charAt(index)) == -1) {
				if (index == len-1)
					number.value = number.value.substring(0, len-1);
				else if (index == 0)
					 	number.value = number.value.substring(1, len);
					 else number.value = number.value.substring(0, index)+number.value.substring(index+1, len);
				index = 0;
				len = number.value.length;
			}
			else index++;
	}
}

function Check_username(number) {
	var pattern = "0123456789qwertyuiopasdfghjklzxcvbnm_QWERTYUIOPASDFGHJKLZXCVBNM";
	var len = number.value.length;
	if (len != 0) {
		var index = 0;
		
		while ((index < len) && (len != 0))
			if (pattern.indexOf(number.value.charAt(index)) == -1) {
				if (index == len-1)
					number.value = number.value.substring(0, len-1);
				else if (index == 0)
					 	number.value = number.value.substring(1, len);
					 else number.value = number.value.substring(0, index)+number.value.substring(index+1, len);
				index = 0;
				len = number.value.length;
			}
			else index++;
	}
}

function Check_email(number) {
	var pattern = "0123456789qwertyuiopasdfghjklzxcvbnm_QWERTYUIOPASDFGHJKLZXCVBNM@.;-";
	var len = number.value.length;
	if (len != 0) {
		var index = 0;
		
		while ((index < len) && (len != 0))
			if (pattern.indexOf(number.value.charAt(index)) == -1) {
				if (index == len-1)
					number.value = number.value.substring(0, len-1);
				else if (index == 0)
					 	number.value = number.value.substring(1, len);
					 else number.value = number.value.substring(0, index)+number.value.substring(index+1, len);
				index = 0;
				len = number.value.length;
			}
			else index++;
	}
}


function check_list_email(email) {
	if((email.split(';').length - 1)<=0)
		return check_email_submit(email);
	else if((email.split(';').length - 1)==1) {
		var list_email=email.split(";");
		if(check_email_submit(list_email[0])==true) {
			if(list_email[1]=="")
				return true;
			else if(check_email_submit(list_email[1])==true)
				return true;
			else
				return false
		}
		else
			return false;
	} else {
		return false;
	}
}

function check_email_submit(x) {
	var atpos=x.indexOf("@");
	var atpos2=x.indexOf(".@");
	var atpos3=x.indexOf("@.");
	var atpos4=x.indexOf("..");
	var dotpos=x.lastIndexOf(".");
	var tmp=x.split("@").length-1;
	
	if(atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length|| atpos2>=1 || atpos3>=1|| atpos4>=1 || tmp>1)
	  return false;
	else
		return true;
}

function check_dienthoai_submit(dienthoai) {
	if((dienthoai.length>11)||(dienthoai.length<10))
		return false;
	
	if((dienthoai.substr(0,2)!='09')&&(dienthoai.substr(0,2)!='01'))
		return false;
	
	return true;
}

function Check_otp(number) {
    var pattern = "0123456789";
    var len = number.value.length;
    if (len != 0) {
        var index = 0;

        while ((index < len) && (len != 0))
            if (pattern.indexOf(number.value.charAt(index)) == -1) {
                if (index == len-1)
                    number.value = number.value.substring(0, len-1);
                else if (index == 0)
                    number.value = number.value.substring(1, len);
                else number.value = number.value.substring(0, index)+number.value.substring(index+1, len);
                index = 0;
                len = number.value.length;
            }
            else index++;
    }
}

function truncate(input,number) {
	if (input.length > number) {
	   return input.substring(0, number) + '...';
	}
	return input;
 };

function formatNumber(number){
	 if(number===""){
		 return "--";
	 }else{
		let a = number*100;
		a = parseInt(a);
		a = a/100;
		return a;
	 }
}

function formatDonVi(donvi){
	if(donvi===""){
		return "--";
	}else{
	   return donvi;
	}
}
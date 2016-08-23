
var APP = Think.APP;
function verify_username(username, outer){
	var url = APP+"/Admin/Verify/verify_username";
    var dataString = 'username=' + username;
    $.ajax({
        type:"post",
        url:url,
        data:dataString,
        success:function(data){
            if(data.status){
                $(outer).removeClass('has-error');
                $(outer).addClass('has-success');
                console.log('verify success');
            }else{
                $(outer).removeClass('has-success');
                $(outer).addClass('has-error');
                console.log('verify error');
            }
        }
    });
    return false;
}

function verify_password(password,outer){
	var url = APP+"/Admin/Verify/verify_password";
    var dataString = 'password=' + password;
    $.ajax({
        type:"post",
        url:url,
        data:dataString,
        success:function(data){
            if(data.status){
                $(outer).removeClass('has-error');
                $(outer).addClass('has-success');
                console.log('verify success');
            }else{
                $(outer).removeClass('has-success');
                $(outer).addClass('has-error');
                console.log('verify error');
            }
        }
    });
    return false;
}
function verify_repassword(password,repassword,outer){
	var url = APP+"/Admin/Verify/verify_repassword";
    var dataString = 'password=' + password + '&repassword=' + repassword;
    $.ajax({
        type:"post",
        url:url,
        data:dataString,
        success:function(data){
            if(data.status){
                $(outer).removeClass('has-error');
                $(outer).addClass('has-success');
                console.log('verify success');
            }else{
                $(outer).removeClass('has-success');
                $(outer).addClass('has-error');
                console.log('verify error');
            }
        }
    });
    return false;
}
function verify_verify_code(code, outer){
    var url = APP+"/Admin/Verify/check_verify";
    var dataString = 'code='+ code;
    $.ajax({
        type:"post",
        url:url,
        data:dataString,
        success:function(data){
            if(data.status){
                $(outer).removeClass('has-error');
                $(outer).addClass('has-success');
            }else{
                $(outer).removeClass('has-success');
                $(outer).addClass('has-error');
            }
        }
    });
    return false;
}
function verify_mobile(mobile, outer){
    var url = APP+"/Admin/Verify/verify_mobile";
    var dataString = 'mobile='+ mobile;
    $.ajax({
        type:"post",
        url:url,
        data:dataString,
        success:function(data){
            if(data.status){
                $(outer).removeClass('has-error');
                $(outer).addClass('has-success');
            }else{
                $(outer).removeClass('has-success');
                $(outer).addClass('has-error');
            }
        }
    });
    return false;
}
function debug(){
	alert("debug");
}
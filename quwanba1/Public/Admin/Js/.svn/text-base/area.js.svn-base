/**
 * Created by Administrator on 2015/6/3.
 */

function loadArea(areaId,areaType){
    var areaAPP = Think.APP+"/Admin/City/get_city_by_first_letter";
    var dataString = 'first_letter=' + areaId;
    $.ajax({
        type:"post",
        url:areaAPP,
        data:dataString,
        success:function(data){
            var obj = data.info;
            if(areaType=='province'){
                $('#'+areaType).html('<option value="-1">省、省级市</option>');
                $('#city').html('<option value="-1">地级市</option>');
            }
            else if(areaType=='city'){
            	$('#'+areaType).html('');
                $('#district').html('<option value="-1">市、县级市</option>');
            }else if(areaType=='district'){
                $('#'+areaType).html('<option value="-1">市、县级市</option>');
            }
            if(areaType!='null'){
                $.each(obj,function(no,items){
                    $('#'+areaType).append('<option value="'+items.id+'">'+items.name+'</option>');
                });
            }
        }
    });
    return false;
}

function searchArea(search,areaType){
    var areaAPP = Think.APP+"/Admin/City/search_city";
    dataString = 'search_word=' + search;
    $.ajax({
        type:"post",
        url:areaAPP,
        data:dataString,
        success:function(data){
            var obj = data.info;
            if(areaType=='province'){
                $('#'+areaType).html('<option value="-1">省、省级市</option>');
                $('#city').html('<option value="-1">地级市</option>');
            }
            else if(areaType=='city'){
            	$('#'+areaType).html('');
                $('#district').html('<option value="-1">市、县级市</option>');
            }else if(areaType=='district'){
                $('#'+areaType).html('<option value="-1">市、县级市</option>');
            }
            if(areaType!='null'){
                $.each(obj,function(no,items){
                    $('#'+areaType).append('<option value="'+items.id+'">'+items.name+'</option>');
                });
            }
        }
    });
    return false;
}
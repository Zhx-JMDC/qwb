/**
 * Created by Administrator on 2015/6/13.
 */
$(document).ready(function(){
    // Set last page opened on the menu
    $('#menu li a[href]').on('click', function() {
        sessionStorage.setItem('menu', $(this).attr('href'));
    });
    if(!sessionStorage.getItem('menu')){

    }else{
        //console.log(sessionStorage.getItem('menu'));
        $('#menu li').removeClass('active');
        $('#menu li a[href=\''+ sessionStorage.getItem('menu') + '\']').parents('li').addClass('active nav-active');
        $('#menu li a[href=\''+ sessionStorage.getItem('menu') + '\']').parents('.children').toggle();
        var size = $('#menu li a[href=\''+ sessionStorage.getItem('menu') + '\']').parents('.children').size();
        if(size <= 0)
            $('#menu > li > a[href=\''+ sessionStorage.getItem('menu') + '\']').parent().children('.children').toggle();
    }
});
function high_light_menu(that) {
    sessionStorage.setItem('menu', $(that).attr('href'));
    //console.log(sessionStorage.getItem('menu'));
    return true;
}
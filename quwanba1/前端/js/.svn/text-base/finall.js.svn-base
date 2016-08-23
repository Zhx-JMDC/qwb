(function($) {
    $.fn.date = function(options) {
        var that = $(this);
        var day = false;
        var indexY = 1, indexM = 1, indexD = 1;
        var yearScroll = null, monthScroll = null, dayScroll = null;
        var nowdate = new Date();
        var initY=parseInt((nowdate.getYear()+"").substr(1,2));

        var initM=parseInt(nowdate.getMonth()+"")+1;
        var initD=parseInt(nowdate.getDate()+"");
        // var initD=parseInt(nowdate.getDate()+"");
         $.fn.date.defaultOptions = {
            beginyear: 1999,                 //日期--年--份开始
            endyear: 2020,                   //日期--年--份结束
            beginmonth: "1",                 //日期--月--份结束
            endmonth: "12",                  //日期--月--份结束
            beginday: "1",                   //日期--日--份结束
            endday: "31",                    //日期--日--份结束
            curdate: true,                   //打开日期是否定位到当前日期（暂时未写）
            theme: "date",                   //控件样式（暂时未写）
            mode: null,                      //操作模式（暂时未写）
            event: "click"                   //打开日期插件默认方式为点击后后弹出日期 
        }
        //用户选项覆盖插件默认选项  
        // showDay(); 
        var opts = $.extend(true, {}, $.fn.date.defaultOptions, options);
         //绑定事件（默认事件为获取焦点）
         if(opts.theme === "day"){day = true;}
         //$(this).(click,function(){})
        that.bind(opts.event, function() {
            createUL();      //动态生成控件显示的日期
            // console.log(opts.theme);
            that.blur();
                init_iScrll();
                extendOptions();
                ymRefresh()
        });

       


        function extendOptions() {
            $("#datePage").show();
            $("#dateshadow").show();
            $("#datescroll").show();
        }
        function ymRefresh(){
            yearScroll.refresh();
            monthScroll.refresh();
            yearScroll.scrollTo(0, initY*80+80, 100, true);
            monthScroll.scrollTo(0, initM*80-80, 100, true);
        } 

        $("#dateconfirm").unbind("click").click(function() {
            var  datestr;
            
            datestr = $("#yearwrapper ul li:eq(" + indexY + ")").html() + "/" +
                $("#monthwrapper ul li:eq(" + indexM + ")").html()+"月";
                var selectYEAR = opts.beginyear + indexY - 1;
                var selectMONTH = indexM;
                createNewUl_DAY(selectYEAR,selectMONTH);
            $("#datePage").hide();
            $("#dateshadow").hide(); 
           
            that.val(datestr);
            var rearYEAR = opts.beginyear + indexY -1 ;
            createNewUl_DAY(rearYEAR,indexM);
            return this;
        });
        $("#datecancle").click(function() {
            $("#datePage").hide();
            $("#dateshadow").hide();
             console.log(opts.theme);
        });
        

        //日期滑动
        function init_iScrll() {
            yearScroll = new iScroll("yearwrapper", { snap: "li", vScrollbar: false,
                onScrollEnd: function() {
                    indexY = (this.y / 80) * (-1) + 1;

                }
            });
            monthScroll = new iScroll("monthwrapper", { snap: "li", vScrollbar: false,
                onScrollEnd: function() {
                    indexM = (this.y / 80) * (-1) + 1;
                }
            });
           
             
        }
         function CreateDateUI(){
            var str = ''+
                '<div id="dateshadow"></div>'+
                '<div id="datePage" class="page">'+
                    '<section>'+
                        '<div id="datetitle"><h1>请选择日期</h1></div>'+
                        '<div id="datemark"><a id="markyear"></a><a id="markmonth"></a></div>'+
                        '<div id="timemark"><a id="markday"></a></div>'+
                        '<div id="datescroll">'+
                            '<div id="yearwrapper">'+
                                '<ul> </ul>'+
                            '</div>'+
                            '<div id="monthwrapper">'+
                                '<ul> </ul>'+
                            '</div>'+
                        '</div>'+
                        '<div id="datescroll_datetime">'+
                            '<div id="daywrapper">'+
                                '<ul></ul>'+
                            '</div>'+
                        '</div>'+
                    '</section>'+

                    '<footer id="dateFooter">'+
                        '<div id="setcancle">'+
                            '<ul>'+
                                '<li id="dateconfirm">确定</li>'+
                                '<li id="datecancle">取消</li>'+
                            '</ul>'+
                        '</div>'+
                    '</footer>'+
                '</div>'
            // $("#datePlugin").html(str);
        }
        function createUL() {
            // CreateDateUI();
            $("#yearwrapper ul").html(createYEAR_UL());
            $("#monthwrapper ul").html(createMONTH_UL());
        }
        //创建 --年-- 列表
        function createYEAR_UL(){
            var str="<li>&nbsp;</li>";
            for(var i=opts.beginyear; i<=opts.endyear;i++){
                str+='<li>'+i+'</li>'
            }
            return str+"<li>&nbsp;</li>";;
        }
        //创建 --月-- 列表
        function createMONTH_UL(){
            var str="<li>&nbsp;</li>";
            for(var i=opts.beginmonth;i<=opts.endmonth;i++){
                // if(i<10){
                //     i="0"+i
                // }
                str+='<li>'+i+'</li>'
            }
            return str+"<li>&nbsp;</li>";;
        }
        //创建 --日-- 列表
        function createNewUl_DAY(year,month){
           var week = new Date(year+'/'+month+'/1').getDay();
           console.log(week);
            if(week!= 0){
                var str="<li>&nbsp;</li>";
                for(var j=1; j<week ;j++)
                    str+="<li>&nbsp;</li>";
                str +="<li><p>1</p></li>";
            }else{
                console.log("1111");
                var str="<li><p>1</p></li>";
            }
            var checkMonth = month;
            if(checkMonth == 1 || checkMonth == 3 || checkMonth == 5 || checkMonth == 7 || checkMonth == 8 || checkMonth == 10 || checkMonth == 12){
                endDay = 31;
            }else if(checkMonth == 4 || checkMonth == 6 || checkMonth == 9 || checkMonth == 11){
                endDay = 30;
            }else if((year%4==0&&year%100!=0)||(year%400==0)){
                endDay = 29;
            }else{
                endDay = 28;
            }
            // console.log(endDay);
            function createNewUl_Make(){
                for(var i=2;i<=endDay;i++){
                    str+='<li><p>'+i+'</p></li>'
                }
                return str;  
            }
            // console.log(str);
            document.getElementById("daywrapper_ul").innerHTML=createNewUl_Make();

        }
    }
})(jQuery);





function change(){
    
    var week = new Date(year+'/'+month+'/1').getDay();
    // console.log(new Date('2016/7/9').getDay());
    // console.log(week);

    var checkMonth = month;

    if(checkMonth == 1 || checkMonth == 3 || checkMonth == 5 || checkMonth == 7 || checkMonth == 8 || checkMonth == 10 || checkMonth == 12){
        endDay = 31;
    }else if(checkMonth == 4 || checkMonth == 6 || checkMonth == 9 || checkMonth == 11){
        endDay = 30;
    }else if((year%4==0&&year%100!=0)||(year%400==0)){
        endDay = 29;
    }else{
        endDay = 28;
    }
    
    function createDAY_UL(){
            if(week==0){
                var str="<li><p>1</p></li>";
            }else{
                var str="<li>&nbsp;</li>";
                for(var j=1; j<week ;j++)
                    str+="<li>&nbsp;</li>";
            }
            
            // var str;
            for(var i=1;i<=endDay;i++){
                if(i==day){
                    str+='<li class="daywrapper_clicked"><p>'+i+'</p></li>';
                }else{
                    str+='<li><p>'+i+'</p></li>';
                }
            }
            return str;                     
        }
    document.getElementById("daywrapper_ul").innerHTML=createDAY_UL();
}

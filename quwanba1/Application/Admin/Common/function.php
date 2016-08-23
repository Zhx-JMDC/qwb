<?php
use Think\Upload;
use Think\Image;
/**
 * 登录检测
 */
function is_login(){
    return session('name') == 1 ? true : false;
}
/*
 * 将数组的每一行数据加上服务器地址
 */
function foreach_add_address($list = array(),$field_key){
    if(!empty($list)){
        $server_address = C('SERVER_ADDRESS');
        foreach($list as $key =>$value){
            if(isset($field_key)){
                $list[$key]['field_key'] = $server_address.$value;
            }else{
                $list[$key] = $server_address.$value;
            }
        }
    }
    return $list;
}
/**
 * 将数组转化成用逗号分割的字符串
 */
function foreach_to_str($list,$field_key){
    $str = '';
    foreach($list as $key =>$value){
        if(isset($field_key)){
            if($str == ''){
                $str = $value[$field_key];
            }else{
                $str .= ','.$value[$field_key];
            }
        }else{
            if($str == ''){
                $str = $value;
            }else{
                $str .= ','.$value;
            }
        }
    }
    return $str;
}
/*
 * 根据身份证号获取出生日期
 */
function getIDCardInfo($IDCard){
    $result['error']=0;//0：未知错误，1：身份证格式错误，2：无错误
    $result['flag']='';//0标示成年，1标示未成年
    $result['tdate']='';//生日，格式如：2012-11-15
    if(!eregi("^[1-9]([0-9a-zA-Z]{17}|[0-9a-zA-Z]{14})$",$IDCard)){
        $result['error']=1;
        return $result;
    }else{
        if(strlen($IDCard)==18){
            $tyear=intval(substr($IDCard,6,4));
            $tmonth=intval(substr($IDCard,10,2));
            $tday=intval(substr($IDCard,12,2));
            if($tyear>date("Y")||$tyear<(date("Y")-100)){
                $flag=0;
            }elseif($tmonth<0||$tmonth>12){
                $flag=0;
            }elseif($tday<0||$tday>31){
                $flag=0;
            }else{
                $tdate=$tyear."年".$tmonth."月".$tday."日";
//                if((time()-mktime(0,0,0,$tmonth,$tday,$tyear))>18*365*24*60*60){
//                    $flag=0;
//                }else{
//                    $flag=1;
//                }
            }
        }elseif(strlen($IDCard)==15){
            $tyear=intval("19".substr($IDCard,6,2));
            $tmonth=intval(substr($IDCard,8,2));
            $tday=intval(substr($IDCard,10,2));
            if($tyear>date("Y")||$tyear<(date("Y")-100)){
                $flag=0;
            }elseif($tmonth<0||$tmonth>12){
                $flag=0;
            }elseif($tday<0||$tday>31){
                $flag=0;
            }else{
                $tdate=$tyear."年".$tmonth."月".$tday."日";
//                if((time()-mktime(0,0,0,$tmonth,$tday,$tyear))>18*365*24*60*60){
//                    $flag=0;
//                }else{
//                    $flag=1;
//                }
            }
        }
    }
    $result['error']=2;//0：未知错误，1：身份证格式错误，2：无错误
//    $result['isAdult']=$flag;//0标示成年，1标示未成年
    $result['birthday']=$tdate;//生日日期
    return $result;
}
/**
 * 系统非常规MD5加密方法
 * @param  string $str 要加密的字符串
 * @return string
 */
function think_md5($str, $key = 'QUWANBA'){
    return '' === $str ? '' : md5(sha1($str) . $key);
}
/**
 * 上传图片
 * @param  array $config   配置
 * @return array
 */
function upload($config,$savePath){
    /* 上传配置 */
    $setting = C('EDITOR_UPLOAD');
    $setting['savePath'].=$savePath;
    /* 调用文件上传组件上传文件 */
    $uploader = new Upload($setting);
    $info = $uploader->upload($_FILES);
    if($info){
        $files = array();
        foreach ($info as $key => $value) {
            $savepath = $info[$key]['savepath'];
            $savename = $info[$key]['savename'];
            $localpath = WWW_ROOT.C('EDITOR_UPLOAD.rootPath').$savepath;
            $localpath = str_replace('./', '/', $localpath);
            $files[$key] = $savepath.$savename;
            // 保存缩略图
            if($config['thumb']){
                foreach($config['thumb'] as $config_key=>$config_value){
                    $thumbImg = new Image();
                    $thumbImg->open($localpath.$savename);
                    $thumbImg->thumb($config_value['w'], $config_value['h'], Image::IMAGE_THUMB_CENTER);
                    $thumbImg->save($localpath.$config_value['prefix'].$savename);
                }
            }
        }
        return $files;
    } else {
        return -1;
    }
}
/*
 * 根据原图地址获取缩略图地址
 */
function get_s_img($str,$prefix){
    $temp = $str;
    $s = 0;
    for($i=0;$i<3;$i++){
        $a = strpos($temp,'/');
        $temp = substr($temp,$a+1);
        $s += ($a+1);
    }
    $f = substr($str,0,$s);
    $e = substr($str,$s);
    $s_img_src = $f.$prefix.$e;
    return $s_img_src;
}

/**
 * 更具id获取省
 * @param $province_id
 * @author zhangxiang <zhxjmdc@gmail.com>
 */
function get_province($province_id){
    $map['id'] = $province_id;
    $ret = M('Province')->field('name')->where($map)->find();
    return $ret['name'];
}

/**
 * 更具id获取市
 * @param $city_id
 * @author zhangxiang <zhxjmdc@gmail.com>
 */
function get_city($city_id){
    $map['id'] = $city_id;
    $ret = M('City')->field('name')->where($map)->find();
    return $ret['name'];
}

/**
 * 更具id获取区
 * @param $district_id
 * @author zhangxiang <zhxjmdc@gmail.com>
 */
function get_district($district_id){
    $map['id'] = $district_id;
    $ret = M('District')->field('name')->where($map)->find();
    return $ret['name'];
}

/**
 * 图片地址拼接
 * @param $data 二维数组数据
 * @param array $fields 一位数组替换字段
 * @return data 拼接后数据
 * @author zhangxiang <zhxjmdc@gmail.com>
 */
function pic_replace($data, $fields = array()){
    foreach ($data as $key => $value){
        foreach ($value as $k=>$v){
            foreach ($fields as $fields_key => $fields_value){
                if($k == $fields_value){
                    $value[$k] = C('DOMAIN_NAME').'Uploads'.get_thumb($v, 's!');
                }
            }
        }
        $data[$key] = $value;
    }
    return $data;
}

function imgSaveName(){
    return uniqid().rand();
}

/**
 * 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg
 * @param $path       图片原路径
 * @param $thumb_path 缩略图路径
 * @param $size 缩略图尺寸
 * @author zhangxiang <zhxjmdc@gmail.com>
 */
function thumb_img($path, $thumb_path, $size){
    $image = new \Think\Image();
    $image->open($path);
    $image->thumb($size, $size)->save($thumb_path);
}

/**
 * 根据原图地址获取缩略图地址
 * @param $path   原图路径
 * @param $prefix 前缀
 * @return string
 * @author zhangxiang <zhxjmdc@gmail.com>
 */
function get_thumb($path,$prefix){
    $temp = $path;
    $s = 0;
    for($i=0;$i<3;$i++){
        $a = strpos($temp,'/');
        $temp = substr($temp,$a+1);
        $s += ($a+1);
    }
    $f = substr($path,0,$s);
    $e = substr($path,$s);
    $s_img_src = $f.$prefix.$e;
    return $s_img_src;
}

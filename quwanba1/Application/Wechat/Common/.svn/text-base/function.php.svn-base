<?php
/**
 * Created by PhpStorm.
 * User: zhangxiang
 * Date: 16/6/30
 * Time: 下午11:31
 */

/**
 * 打包称成Json数据
 * @param $code 返回的状态
 * @param $msg 提示信息
 * @param $result 返回的json格式的数据
 */
function response($code, $msg, $result = null) {
    $response = array('code' => $code, 'msg' => $msg);
    if ($result != null) {
        if (is_object($result) || is_array($result)) {
            $response ['result'] = $result;
        }
        else
            $response ['result'] = (string)$result;
    }
    // 返回结果
    echo json_encode($response);
}

/**
 * 解析Json数据，二维数组
 * @param $data  json串
 * @return mixed
 * @author zhangxiang <zhxjmdc@gmail.com>
 */
function json_request($data) {
    $params = json_decode($data,'true');
    return $params;
}

/**
 * 发送post/get请求
 * @param string $url    请求地址
 * @param json   $data   发送数据
 * @param strng  $method 发送方式
 * @return mixed|string
 * @author zhangxiang <zhxjmdc@gmail.com>
 */
function request($url,$data = null,$method){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);

    if($data != null){
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $tmpInfo = curl_exec($ch);
    if (curl_errno($ch)) {
        return curl_error($ch);
    }
    curl_close($ch);
    return $tmpInfo;
}

/**
 * 抓取url内容
 * @param  $url  抓取地址
 * @return mixed
 * @author zhangxiang <zhxjmdc@gmail.com>
 */
function get_curl($url){
    $ch = curl_init();                              /*初始化*/
    /*针对https抓取*/
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);/*跳过证书检查*/
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true); /*从证书中检查SSL加密算法是否存在*/
    curl_setopt($ch, CURLOPT_URL, $url);            /*设置提交的页面*/
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    /*返回抓取的内容*/
    $data = curl_exec($ch);
    if(curl_errno($ch)){
        var_dump(curl_error($ch));
    }
    curl_close($ch);
    return $data;
}

/**
 * 创建用户唯一id
 * @param $open_id 微信唯一open_id
 * @return string  随机唯一id
 * @author zhangxiang <zhxjmdc@gmail.com>
 */
function get_id($open_id){
    return substr(md5($open_id),0,8);
}

/**
 * 确定用户当前位置
 */
function get_position(){
    if(session('city')){
        return session('city');
    }else{
        return '湖州市';
    }
}

/**
 * 通过城市名称获取城市id
 */
function get_city_id($name){
    $map['name'] = $name;
    $data = M('City')->field('id')->where($map)->find();
    return $data['id'];
}

/**
 * @desc 根据两点间的经纬度计算距离
 * @param float $lat1 纬度值
 * @param float $lng1 经度值
 * @param float $lat2 纬度值
 * @param float $lng2 经度值
 * @return 距离
 */
function getDistance($lat1, $lng1, $lat2, $lng2) {
    $earthRadius = 6367000; //approximate radius of earth in meters

    $pi = 3.1415926;
    $lat1 = ($lat1 * $pi ) / 180;
    $lng1 = ($lng1 * $pi ) / 180;

    $lat2 = ($lat2 * $pi ) / 180;
    $lng2 = ($lng2 * $pi ) / 180;

    $calcLongitude = abs($lng2 - $lng1);
    $calcLatitude = abs($lat2 - $lat1);
    $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
    $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
    $calculatedDistance = $earthRadius * $stepTwo;

    return round($calculatedDistance);
}

/**
 * 二位数组根据子元素字段大小排序
 * @param $data   排序数组
 * @param $order  排序方法 SORT_ASC SORT_DESC
 * @param $where  排序的字段名称
 * @return mixed
 * @author zhangxiang <zhxjmdc@gmail.com>
 */
function array_sort($data, $order, $where){
    $temp = array();
    foreach ($data as $key => $row) {
        $temp[$key]  = $row[$where];
    }
    array_multisort($temp,$order, $data);//二位数组排序
    return $data;
}

/**
 * 生成唯一订单号
 * @return string
 * @author zhangxiang <zhxjmdc@gmail.com>
 */
function get_order_num(){
    $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
    return $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
}

/**
 * 字符串加解密
 * @param $string      需要加密解密的字符串
 * @param $operation   判断是加密还是解密，E表示加密，D表示解密；
 * @param string $key  密匙 
 * @return mixed|string
 * @author zhangxiang <zhxjmdc@gmail.com>]
 */
function encrypt($string,$operation,$key=''){
    $key=md5($key);
    $key_length=strlen($key);
    $string=$operation=='D'?base64_decode($string):substr(md5($string.$key),0,8).$string;
    $string_length=strlen($string);
    $rndkey=$box=array();
    $result='';
    for($i=0;$i<=255;$i++){
        $rndkey[$i]=ord($key[$i%$key_length]);
        $box[$i]=$i;
    }
    for($j=$i=0;$i<256;$i++){
        $j=($j+$box[$i]+$rndkey[$i])%256;
        $tmp=$box[$i];
        $box[$i]=$box[$j];
        $box[$j]=$tmp;
    }
    for($a=$j=$i=0;$i<$string_length;$i++){
        $a=($a+1)%256;
        $j=($j+$box[$a])%256;
        $tmp=$box[$a];
        $box[$a]=$box[$j];
        $box[$j]=$tmp;
        $result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256]));
    }
    if($operation=='D'){
        if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8)){
            return substr($result,8);
        }else{
            return'';
        }
    }else{
        return str_replace('=','',base64_encode($result));
    }
}

/**
 * 获取用户uid
 * @author zhangxiang <zhxjmdc@gmail.com>
 */
function get_uid(){
    $session_uid = session('uid');

    if($session_uid){
        session('uid',cookie('uid'));
    }
    return encrypt(session('uid'), 'D', C('ENCYRPT'));
}

/**
 * 支付宝参数数组排序
 * @param [para]  排序的参数数组
 * @return [para]
 */
function argSorts($para) {
    ksort($para);
    reset($para);
    return $para;
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
                    $value[$k] = C('DOMAIN_NAME').'Uploads'.$v;
                }
            }
        }
        $data[$key] = $value;
    }
    return $data;
}
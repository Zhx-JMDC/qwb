<?php
namespace Wechat\Model;
use Think\Model;

class AlipayModel extends Model{
    /**--------------------------------------------------------------------------------
     * 获取RSA签名
     * @param  [data] [排序后的数组]
     * @return [sign] [支付签名]
     */
    function sign($data) {
        $priKey = file_get_contents(VENDOR_PATH."AlipayRSA/rsa_private_key.pem");//私钥文件路径
        //转换为openssl密钥，必须是没有经过pkcs8转换的私钥
        $res = openssl_pkey_get_private($priKey);

        //调用openssl内置签名方法，生成签名$sign
        openssl_sign($data, $sign, $res);

        //释放资源
        openssl_free_key($res);

        //base64编码
        $sign = base64_encode($sign);

        return $sign;
    }

    /**--------------------------------------------------------------------------------
     * RSA验签
     * @param $data 待签名数据
     * @param $sign 要校对的的签名结果
     * @return 验证结果
     */
    function rsaVerify($data, $sign)  {
        $pubKey = file_get_contents(VENDOR_PATH."AlipayRSA/rsa_public_key.pem");
        $res = openssl_get_publickey($pubKey);

        $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        openssl_free_key($res);
        return $result;
    }
}
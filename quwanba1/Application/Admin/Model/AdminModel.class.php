<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/7
 * Time: 17:38
 */

namespace Admin\Model;
use Think\Model;

class AdminModel extends Model{
    protected $_auto = array (
        array('create_time','time',self::MODEL_INSERT,'function'),
    );
}
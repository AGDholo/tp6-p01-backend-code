<?php

declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class User extends Model
{
    protected $table = 'users';

    // 定义可以被批量操作的字段
    protected $field = ['name', 'email', 'password', 'avatar'];

    // 设置修改器
    public function setPasswordAttr($value)
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }
    
    public function setEmailAttr($value)
    {
        return strtolower($value);
    }
}

<?php

declare(strict_types=1);

namespace app\model;

use think\model\Pivot;

/**
 * @mixin \think\Model
 */
class User extends Pivot
{
    protected $table = 'users';

    // 定义可以被批量操作的字段
    protected $field = ['name', 'email', 'password', 'avatar'];

    // 定义数据输出时需要隐藏的字段
    protected $hidden = ['password', 'god'];

    // 设置修改器
    public function setPasswordAttr($value)
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }

    public function setEmailAttr($value)
    {
        return strtolower($value);
    }

    // 用户与推文关联
    public function tweets()
    {
        return $this->hasMany(Tweet::class);
    }

    // 用户与粉丝多对多关联
    public function followers()
    {
        return $this->belongsToMany(User::class, Follower::class, 'user_id', 'follower_id');
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, Follower::class, 'follower_id', 'user_id');
    }
}

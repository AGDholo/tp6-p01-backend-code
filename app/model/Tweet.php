<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Tweet extends Model
{
    // 指定表名
    protected $table = 'tweets';

    // 定义可以被批量操作的字段
    protected $field = ['content', 'user_id'];

    // 推文修改器
    public function setContentAttr($value)
    {
        // PHP 提供了内置 htmlspecialchars 方法来过滤文本
        return htmlspecialchars($value);
    }

    // 用户与推文反向关联
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

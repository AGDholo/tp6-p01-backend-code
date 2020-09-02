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
}

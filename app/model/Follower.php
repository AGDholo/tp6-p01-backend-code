<?php
declare (strict_types = 1);

namespace app\model;

use think\model\Pivot;

/**
 * @mixin \think\Model
 */
class Follower extends Pivot
{
    // 指定表名
    protected $table = 'followers';
}

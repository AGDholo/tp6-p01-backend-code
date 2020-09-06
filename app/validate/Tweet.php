<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class Tweet extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
    protected $rule = [
        // 推特允许发推文的字数是280
        'content|推文' =>  'require|max:280',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [];
}

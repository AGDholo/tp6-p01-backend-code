<?php

declare(strict_types=1);

namespace app\controller;

use think\Request;
use app\model\User as UserModel;
use thans\jwt\facade\JWTAuth;

class User
{
    // 定义中间件
    protected $middleware = [
        \thans\jwt\middleware\JWTAuth::class => [
            'except' => ['index', 'read']
        ],
    ];

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $data = UserModel::with(['tweets' => function($query) {
            $query->order('created_at desc');
        }])->find($id);
        
        return json($data);
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        $authID = JWTAuth::auth()['id'];

        if ($authID != $id) {
            return json([
                "message" => "非法请求"
            ], 401);
        }

        UserModel::update([
            "id" => $id,
            "name" => $request->param('name')
        ]);

        $data = UserModel::find($id);
        return json($data);
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}

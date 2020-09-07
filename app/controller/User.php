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
        $data = UserModel::with(['followers', 'followings', 'tweets' => function ($query) {
            $query->order('created_at desc');
        }])->find($id);

        // 正在关注他的用户 ID
        if ($data->followings) {
            $following_ids = $data->followings->column('id');

            // 查询当前登入用户是否已经关注
            $authID = JWTAuth::auth()['id'] . '';
            $match = array_search($authID, $following_ids);
            $is_following = $match === 0 ? true : false;
        }

        return json([
            'is_following' => $is_following ?? false,
            'data' => $data,
        ]);
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

    // 关注用户
    public function follow(Request $request)
    {
        $follow_id = $request->param('follow_id');

        $user = $this->follow_data($request);
        $user->followers()->attach($follow_id);

        return $this->read($follow_id);
    }

    // 取消关注用户
    public function unfollow(Request $request)
    {
        $follow_id = $request->param('follow_id');

        $user = $this->follow_data($request);
        $user->followers()->detach($follow_id);

        return $this->read($follow_id);
    }

    protected function follow_data($request)
    {
        $authID = JWTAuth::auth()['id'] . '';
        $id = intval($authID);

        $user = UserModel::find($id);
        return $user;
    }
}

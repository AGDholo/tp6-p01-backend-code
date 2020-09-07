<?php

declare(strict_types=1);

namespace app\controller;

use app\model\Tweet as TweetModel;
use app\model\User;
use think\Request;
use thans\jwt\facade\JWTAuth;

class Tweet
{
    // 定义中间件
    protected $middleware = [
        \thans\jwt\middleware\JWTAuth::class => [
            // index，read 方法除外
            'except' => ['index', 'read']
        ],
    ];

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        if ($request->param('all_tweets'))
            return json(TweetModel::with(['user'])->order('created_at desc')->select());

        // 关注用户
        $authID = JWTAuth::auth()['id'] . '';

        $data = User::with(['followers', 'followings', 'tweets' => function ($query) {
            $query->order('created_at desc');
        }])->find($authID);

        $followers_ids = $data->followers->column('id');

        $ids = [...$followers_ids, $authID];

        // 使用预加载的方法返回所有关联推文
        $data = TweetModel::with(['user'])->whereIn('user_id', $ids)->order('created_at desc')->select();
        return json($data);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        // 获取当前用户 ID
        $authID = JWTAuth::auth()['id'] . '';

        // 将当前 ID 与用户一起插入
        TweetModel::create([
            'content' => $request->param('content'),
            'user_id' => intval($authID)
        ]);

        // 使用预加载的方法返回所有关联推文
        $data = TweetModel::with(['user'])->order('created_at desc')->select();
        return json($data);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
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
        //
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

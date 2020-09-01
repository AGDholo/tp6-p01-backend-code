<?php

declare(strict_types=1);

namespace app\controller;

use app\model\User;
use app\validate\Auth as validateAuth;
use Exception;
use think\facade\Request;
use think\exception\ValidateException;
use thans\jwt\facade\JWTAuth;

class Auth
{
    public function me()
    {
        try {
            $id = JWTAuth::auth()['id'];
            $data = User::find($id);
            return json($data);
        } catch (Exception $e) {
            return json([
                'message' => '请先登录'
            ], 401);
        }
    }

    public function login()
    {
        $requestData = Request::post();

        $user = User::where('email', $requestData['email'])->find();

        if ($user !== null && password_verify($requestData['password'], $user->password)) {
            return json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'token' => JWTAuth::builder(['id' => $user->id]),
                'ttl' => env('JWT_TTL')
            ]);
        } else {
            return json(
                [
                    'message' => '授权错误，请检查邮件地址或密码'
                ],
                401
            );
        }
    }

    public function sign()
    {
        $requestData = Request::post();

        try {
            validate(validateAuth::class)->batch(true)->check($requestData);

            $create = User::create($requestData);
            $data = User::find($create->id);

            return json([
                'id' => $data->id,
                'name' => $data->name,
                'email' => $data->email,
                'token' => JWTAuth::builder(['id' => $data->id]),
                'ttl' => env('JWT_TTL')
            ]);

            return json($data);
        } catch (ValidateException $e) {
            return json(
                [
                    'message' => $e->getError()
                ],
                400
            );
        }
    }

    public function logout()
    {
        $authorization = Request::header('Authorization');
        $token = explode('Bearer ', $authorization)[1];

        try {
            JWTAuth::invalidate($token);
            JWTAuth::validate($token);

            return json([
                'message' => '登出成功'
            ]);
        } catch (Exception $e) {
            return json([
                'message' => '登出失败，请检查 token 有效情况'
            ], 403);
        }
    }
}

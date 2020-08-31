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
    public function login()
    {
        $requestData = Request::post();

        $user = User::where('email', $requestData['email'])->find();
        
        if ($user !== null && password_verify($requestData['password'], $user->password)) {
            return json([
                'name' => $user->name,
                'email' => $user->email,
                'token' => JWTAuth::builder(['id' => $user->id]),
                'ttl' => env('JWT_TTL')
            ]);
        } else {
            return json(
                [
                    'error' => '授权错误，请检查邮件地址或密码'
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
                'name' => $data->name,
                'email' => $data->email,
                'token' => JWTAuth::builder(['id' => $data->id]),
                'ttl' => env('JWT_TTL')
            ]);

            return json($data);
        } catch (ValidateException $e) {
            return json($e->getError(), 400);
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
                'success' => '登出成功'
            ]);
        } catch (Exception $e) {
            return json([
                'error' => '登出失败，服务器内部错误'
            ], 500);
        }
    }
}

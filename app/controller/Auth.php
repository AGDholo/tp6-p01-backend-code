<?php

declare(strict_types=1);

namespace app\controller;

use app\model\User;
use app\validate\Auth as validateAuth;
use think\facade\Request;
use think\exception\ValidateException;
use thans\jwt\facade\JWTAuth;

class Auth
{
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
}

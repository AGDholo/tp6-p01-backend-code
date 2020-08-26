<?php

declare(strict_types=1);

namespace app\controller;

use app\validate\Auth as validateAuth;
use think\facade\Request;
use think\exception\ValidateException;

class Auth
{
    public function sign()
    {
        $requestData = Request::post();

        try {
            validate(validateAuth::class)->batch(true)->check($requestData);
        } catch (ValidateException $e) {
            return json($e->getError(), 400);
        }
    }
}

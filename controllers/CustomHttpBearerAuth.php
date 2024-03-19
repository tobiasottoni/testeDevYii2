<?php

namespace app\controllers;

use Yii;
use yii\web\UnauthorizedHttpException;
use yii\filters\auth\HttpBearerAuth;

class CustomHttpBearerAuth extends HttpBearerAuth
{
    public function authenticate($user, $request, $response)
    {
        $accessToken = $this->getAccessToken($request);
        if ($accessToken !== null && Yii::$app->session->get('access_token') === $accessToken) {
            return $user;
        }

        throw new UnauthorizedHttpException('Your request was made with invalid credentials.');
    }
}
<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use app\models\Users;
use yii\filters\auth\HttpBearerAuth;

class AuthController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Configura o middleware de autenticação HttpBearerAuth
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['login', 'check-token'], // Exclui a ação 'login' e 'check-token' da autenticação
        ];

        // Configura a resposta para JSON
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;

        return $behaviors;
    }

    public function actionLogin()
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        $username = $params['username'] ?? null;
        $password = $params['password'] ?? null;

        if ($username === null || $password === null) {
            return ['error' => 'Usuário e senha são obrigatórios.'];
        }

        // Busca o usuário pelo nome de usuário e pelo status 'active'
        $user = Users::findOne(['username' => $username, 'active' => 'active']);

        if ($user !== null && Yii::$app->security->validatePassword($password, $user->password_hash)) {
            // Gera um token de acesso
            $token = Yii::$app->security->generateRandomString();

            // Salva o token no banco de dados
            $user->user_token = $token;
            if ($user->save()) {
                return ['access_token' => $token];
            } else {
                return ['error' => 'Erro ao salvar o token no banco de dados.'];
            }
        } else {
            return ['error' => 'Usuário ou senha inválidos ou usuário não está ativo.'];
        }
    }

    public function actionLogout()
    {
        // Remove o token da sessão do usuário
        Yii::$app->session->remove('access_token');

        // Remove o token do banco de dados
        $requestToken = Yii::$app->request->getHeaders()->get('Authorization');
        $user = Users::findOne(['user_token' => $requestToken]);
        if ($user !== null) {
            $user->user_token = null;
            $user->save();
        }

        return ['message' => 'Usuário deslogado com sucesso.'];
    }

    public function actionCheckToken()
    {
        $requestToken = Yii::$app->request->getHeaders()->get('Authorization');

        $user = Users::findOne(['user_token' => $requestToken]);

        if ($user !== null) {
            return ['message' => 'Token válido.'];
        } else {
            return ['error' => 'Token inválido.'];
        }
    }
}

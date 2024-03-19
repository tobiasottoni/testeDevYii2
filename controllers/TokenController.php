<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use app\models\Users;
use yii\filters\auth\HttpBearerAuth;

class TokenController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Configura o middleware de autenticação HttpBearerAuth
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['check-token'], // Adiciona a ação 'check-token' como exceção
        ];

        // Configura a resposta para JSON
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;

        return $behaviors;
    }


    public function actionCheckToken()
    {
        // Obtém o token fornecido no cabeçalho da requisição
        $requestToken = Yii::$app->request->getHeaders()->get('Authorization');

        if ($requestToken !== null) {
            // Remove o prefixo "Bearer" do token recebido no cabeçalho
            $requestToken = str_replace('Bearer ', '', $requestToken);

            // Procura pelo usuário com o token correspondente (sem o prefixo "Bearer") no banco de dados
            $user = Users::findOne(['user_token' => $requestToken]);

            if ($user !== null) {
                // Token encontrado no banco de dados, retorna uma resposta positiva
                return ['message' => 'Acesso permitido.'];
            } else {
                // Token não encontrado no banco de dados, retorna uma resposta negativa
                return ['error' => 'Acesso não permitido.'];
            }
        } else {
            // Token não encontrado no cabeçalho da requisição
            return ['error' => 'Token não encontrado no cabeçalho.'];
        }
    }
}

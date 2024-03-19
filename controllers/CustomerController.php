<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use app\models\Customers;
use app\models\Users;

class CustomerController extends ActiveController
{
    public $modelClass = 'app\models\Customers';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']); // Remove a ação padrão index
        unset($actions['create']); // Remove a ação padrão create
        return $actions;
    }

    public function actionCreate()
    {
        $access = $this->checkToken();

        if (isset($access) and $access === 1) {

            $model = new Customers();

            // Carregar os dados do cliente enviados via POST
            $model->load(Yii::$app->request->post(), '');

            if ($model->save()) {
                return $model;
            } else {
                return ['error' => 'Erro ao cadastrar o cliente.', 'errors' => $model->errors];
            }
        } else {
            Yii::$app->response->statusCode = 401;
            return ['error' => 'Token inválido ou não encontrado no cabeçalho.'];
        }
    }


    public function actionIndex()
    {
        $access = $this->checkToken();

        if (isset($access) and $access === 1) {


            $dataProvider = new ActiveDataProvider([
                'query' => Customers::find(),
                'pagination' => [
                    'pageSize' => 10, // Defina o tamanho da página aqui
                ],
            ]);

            return $dataProvider;
        } else {
            Yii::$app->response->statusCode = 401;
            return ['error' => 'Token inválido ou não encontrado no cabeçalho.'];
        }
    }

    private function checkToken()
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
                return 1;
            } else {
                // Token não encontrado no banco de dados, retorna uma resposta negativa
                Yii::$app->response->statusCode = 401;
                return ['error' => 'Acesso não permitido.'];
            }
        } else {
            // Token não encontrado no cabeçalho da requisição
            Yii::$app->response->statusCode = 401;
            return ['error' => 'Token não encontrado no cabeçalho.'];
        }
    }
}

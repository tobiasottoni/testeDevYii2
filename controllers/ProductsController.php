<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use app\models\Products;
use app\models\Users;

class ProductsController extends ActiveController
{
    public $modelClass = 'app\models\Products';

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

            $model = new Products();

            // Carrega os dados do produto enviados via POST
            $model->load(Yii::$app->request->post(), '');

            if ($model->save()) {
                return $model;
            } else {
                return ['error' => 'Erro ao cadastrar o produto.', 'errors' => $model->errors];
            }
        } else {
            Yii::$app->response->statusCode = 401;
            return ['error' => 'Token inválido ou não encontrado no cabeçalho.'];
        }
    }

    public function actionIndex($customer_id = null)
    {
        $access = $this->checkToken();

        if (isset($access) and $access === 1) {

            $query = Products::find();

            // Se um ID de cliente for fornecido, filtra os produtos pelo cliente
            if ($customer_id !== null) {
                $query->andWhere(['customer_id' => $customer_id]);
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
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

<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Users;

//Comando para criar usuários: php yii create-user/create <name> <username> <password>

class CreateUserController extends Controller
{
    public function actionCreate($name, $username, $password)
    {
        $user = new Users();
        $user->name = $name;
        $user->username = $username;
        $user->password_hash = Yii::$app->security->generatePasswordHash($password);
        $user->active = 'active';

        if ($user->save()) {
            echo "User created successfully.\n";
        } else {
            echo "Error creating user: " . implode(", ", $user->getFirstErrors()) . "\n";
        }
    }
}

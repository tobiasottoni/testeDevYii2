<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Customers extends ActiveRecord
{
    public static function tableName()
    {
        return 'customers'; // Nome da tabela no banco de dados
    }

    public function rules()
    {
        return [
            [['name', 'cpf'], 'required'],
            ['cpf', 'unique'],
            ['cpf', 'string', 'length' => 11], // Validação básica de CPF
            ['address', 'string'],
            ['number', 'string'],
            ['complement', 'string'],
            ['city', 'string'],
            ['state', 'string'],            
            ['zip', 'string'],
            ['photo', 'string'],
            ['gender', 'in', 'range' => ['male', 'female']], // Validação de sexo
            [['active'], 'in', 'range' => ['active', 'inactive']],
        ];
    }
}


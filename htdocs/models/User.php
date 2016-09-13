<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

/*
    private static $users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'username' => 'demo',
            'password' => 'demo',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ];

*/
    /**
     * @inheritdoc
     */
public static function findIdentity($id)
{
    return static::findOne(['id' => $id]);
}
public static function findIdentityByAccessToken($token, $type = null)
{
    throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
}
public static function findByUsername($username)
{
    return static::findOne(['username' => $username]);
}
public function getId()
{
    return $this->getPrimaryKey();
}
public function getAuthKey()
{
    return $this->auth_key;
}    
public function validateAuthKey($authKey)
{
    return $this->getAuthKey() === $authKey;
}
public function validatePassword($password)
{
    return Yii::$app->security->validatePassword($password, $this->password);
}
}

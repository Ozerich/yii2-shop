<?php

namespace ozerich\shop\modules\admin\models;

use ozerich\admin\interfaces\IAdminUser;
use yii\base\BaseObject;
use yii\web\IdentityInterface;

class AdminUser extends BaseObject implements IAdminUser, IdentityInterface
{
    public $id;
    public $authKey;
    public $username;
    public $accessToken;
    public $password;

    private static $users = [
        '1' => [
            'id' => '1',
            'username' => 'admin',
            'password' => 'yii2shop-admin',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ];

    public function checkAdminLogin($login, $password)
    {
        if ($login == 'admin' && $password == 'yii2shop-admin') {
            return new self(self::$users['1']);
        }

        return null;
    }

    public function getDashboardName()
    {
        return 'admin';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

}
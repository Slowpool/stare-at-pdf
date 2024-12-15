<?php

namespace app\models\domain;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $name
 * @property string $password_hash
 * @property string $access_token
 * @property string $auth_key
 *
 * @property PdfFile[] $pdfFiles
 */
class UserRecord extends ActiveRecord implements IdentityInterface
{
    // public $id;
    // public $name;
    // public $password_hash;
    // public $auth_key;
    // public $access_token;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'password_hash', 'access_token', 'auth_key'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['password_hash'], 'string', 'max' => 64],
            [['access_token'], 'string', 'max' => 16],
            [['auth_key'], 'string', 'max' => 32],
            [['name', 'access_token', 'auth_key'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'password_hash' => 'Password hash',
            'access_token' => 'Access token',
            'auth_key' => 'Auth key',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }
    
    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['access_token' => $token]);
    }
    
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByName($name)
    {
        return self::findOne(['name' => $name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return self::hashPassword($password) === $this->password_hash;
    }

    /**
     * Gets query for [[PdfFiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPdfFiles()
    {
        return $this->hasMany(PdfFileRecord::class, ['user_id' => 'id']);
    }

    public function getUsername() {
        return $this->name;
    }

    public static function hashPassword($password) {
        return hash('haval256,4', $password);
    }
}

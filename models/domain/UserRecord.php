<?php

namespace app\models\domain;

use Yii;

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
class UserRecord extends \yii\db\ActiveRecord
{
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
        return self::find(); // TODO finish identity
    }

    /**
     * {@inheritdoc}
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

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
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
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
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
}

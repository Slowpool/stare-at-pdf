<?php

namespace app\models\domain;

use Yii;
use app\models\domain\UserRecord;

/**
 * This is the model class for table "pdf_file".
 *
 * @property int $id
 * @property string $name
 * @property int $bookmark
 * @property int $user_id
 *
 * @property User $user
 */
class PdfFileRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pdf_file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'user_id'], 'required'],
            [['bookmark', 'user_id'], 'integer'],
            [['name'], 'string', 'max' => 150],
            [['name'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserRecord::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'bookmark' => 'Bookmark',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(UserRecord::class, ['id' => 'user_id']);
    }

    /** @return string[] */
    public static function getFilesOfUserAsArray($username)
    {
        UserRecord::find()
                  ->asArray()
                  ->where(['name' => $username])
                  ->all();
        // $userDir = Yii::getAlias('@uploads') . "/$username";
        // $fileNames = [];
        // if (is_dir($userDir)) {
        //     if ($dir = opendir($userDir)) {
        //         while (($file = readdir($dir)) !== false) {
        //             $fileNames[] = $file;
        //         }
        //         return $fileNames;
        //     } else {
        //         throw new \Exception('failed to open uploads');
        //     }
        // } else {
        //     throw new \Exception('uploads isn\'t a directory');
        // }
    }
}

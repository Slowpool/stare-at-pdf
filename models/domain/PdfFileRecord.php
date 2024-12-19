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
    public $id;
    public $name;
    public $bookmark;
    public $user_id;

    public function __construct($fileName) {
        $this->name = $fileName;
        // still not sure that default values should be set this way
        $this->bookmark = 0;
        $this->user_id = Yii::$app->user->identity->id;
    }

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
        return self::find()
                    ->alias('pf')
                    ->asArray()
                    ->select(['pf.id', 'pf.name', 'pf.bookmark', 'pf.user_id'])
                    ->joinWith('user u') // probably ordinary join?
                    ->where(['u.name' => $username])
                    ->all();
    }

    public static function getBookmarkByFileName($file_name) {
        return self::find()
                    ->asArray()
                    ->where(['name' => $file_name])
                    ->one()
                    ['bookmark'];
    }
}

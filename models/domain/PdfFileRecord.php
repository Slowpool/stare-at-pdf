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
 * @property UserRecord $user
 */
class PdfFileRecord extends \yii\db\ActiveRecord
{
    // https://qna.habr.com/q/425831?ysclid=m4wxkml2dl503187584
    // public $id;
    // public $name;
    // public $bookmark;
    // public $user_id;

    /**
     * ActiveRecord requires empty constructor, so it can not be overriden. So, use this method to initiate \app\models\domain\PdfFileRecord with params. 
     * @param string $fileName
     * @return \app\models\domain\PdfFileRecord
     */
    public static function explicitConstructor($fileName): self
    {
        $record = new self;
        $record->name = $fileName;
        $record->user_id = Yii::$app->user->identity->id;
        // still not sure that default values should be set this way
        $record->bookmark = 1;
        return $record;
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
            [['id', 'name', 'bookmark', 'user_id'], 'safe'],
            [['name', 'user_id'], 'required'],
            [['bookmark', 'user_id'], 'integer'],
            [['name'], 'string', 'max' => 150],
            [
                ['name', 'user_id'],
                'unique',
                'targetAttribute' => ['name', 'user_id'],
                'message' => 'You already have the book with this name in your library'
            ],
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
            ->joinWith('user u') // TODO probably ordinary join?
            ->where(['u.name' => $username])
            ->all();
    }

    public static function getBookmarkByFileName($pdfName)
    {
        return self::findByNameForCurrentUser($pdfName, true)['bookmark'];
    }

    public static function findByNameForCurrentUser(string $pdfName, bool $asArray = false): self|array
    {
        $pdfFile = self::find();
        if ($asArray) {
            $pdfFile = $pdfFile->asArray();
        }
        return $pdfFile->where(['name' => $pdfName, 'user_id' => Yii::$app->user->identity->id])
            ->one();
    }

    public static function updateBookmark(string $pdfName, int $newBookmark): bool
    {
        try {
            $pdfFile = self::findByNameForCurrentUser($pdfName);
            $pdfFile->bookmark = $newBookmark;
            return $pdfFile->update();
        } catch (\Exception) {
            return false;
        }
    }
}

<?php

namespace app\models\domain;

use Yii;
use app\models\domain\UserRecord;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "pdf_file".
 *
 * @property int $id
 * @property string $name
 * @property int $bookmark
 * @property int $user_id
 * @property string $slug
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
            // TODO is slug safe?
            [['id', 'name', 'bookmark', 'user_id', 'slug'], 'safe'],
            [['name', 'user_id', 'slug'], 'required'],
            [['bookmark', 'user_id'], 'integer'],
            [['name', 'slug'], 'string', 'max' => 150],
            [
                ['user_id', 'name'],
                'unique',
                'targetAttribute' => ['name', 'user_id'],
                'message' => 'You already have the book with this name in your library'
            ],
            [
                ['user_id', 'slug'],
                'unique',
                'targetAttribute' => ['user_id', 'slug'],
                // TODO bad message
                'message' => 'You already have such a slug in your library'
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

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $this->slug = new Expression("SLUGIFICATE(:name)", [':name' => $this->name]);
            return true;
        }
        else {
            return false;
        }
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

    public function getCategories()
    {
        // TODO malconfigurated???
        return $this->hasMany(PdfFileCategoryRecord::class, ['id' => 'category_id'])
            ->viaTable('pdf_file_category_entry', ['pdf_file_id' => 'id']);
    }

    /** @return  */
    public static function getFilesOfUserAsArray($includeCategories = false, $fieldsToSelect = ['id', 'name', 'bookmark', 'user_id']): array
    {
        for ($i = 0; $i < sizeof($fieldsToSelect); $i++) {
            $fieldsToSelect[$i] = 'pf.' . $fieldsToSelect[$i];
        }

        $query = self::find()
            ->leftJoin('user u', 'u.id = pf.user_id')
            ->where(['u.name' => Yii::$app->user->identity->name])
            ->alias('pf')
            ->asArray();

        if ($includeCategories) {
            $query = $query->with('categories');
        }
        return $query->select($fieldsToSelect)
            ->all();
    }

    /**
     * @deprecated
     * @param string $pdfName
     * @return self
     */
    public static function getBookmarkByFileName($pdfName)
    {
        trigger_error('use findBySlugForCurrentUser instead', E_USER_DEPRECATED);
        return self::findByNameForUser($pdfName, true)['bookmark'];
    }

    /**
     * @deprecated
     * @param string $pdfName
     * @return self
     */
    public static function findByNameForUser(string $pdfName, bool $asArray = false, bool $execute = true): ActiveQuery|self|array
    {
        $pdfFile = self::find();
        if ($asArray) {
            $pdfFile = $pdfFile->asArray();
        }
        $query = $pdfFile->where(['pdf_file.name' => $pdfName, 'pdf_file.user_id' => Yii::$app->user->identity->id]);
        return $execute ? $query->one() : $query;
    }
    
    public static function findBySlugForCurrentUser($pdfSlug, bool $asArray = false, bool $execute = true): ActiveQuery|ActiveRecord|array|null {
        $pdfFile = self::find();
        if ($asArray) {
            $pdfFile = $pdfFile->asArray();
        }
        $query = $pdfFile->where(['pdf_file.slug' => $pdfSlug, 'pdf_file.user_id' => Yii::$app->user->identity->id]);
        return $execute ? $query->one() : $query;
    }

    public static function updateBookmark(string $pdfId, int $newBookmark): bool
    {
        try {
            $pdfFile = self::findOne($pdfId);
            $pdfFile->bookmark = $newBookmark;
            return $pdfFile->update();
        } catch (\Exception) {
            return false;
        }
    }

    // public static function findById($id): PdfFileRecord|null {
    //     return self::findOne($id);
    // }

    public static function getPdfFileIdsAndNames(): array
    {
        return self::getFilesOfUserAsArray(false, ['id', 'name']);
    }
}

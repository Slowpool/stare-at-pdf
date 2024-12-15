<?php

namespace app\models\domain;

use Yii;

/**
 * This is the model class for table "pdf_file".
 *
 * @property int $id
 * @property string $name
 * @property int $bookmark
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
            [['name'], 'required'],
            [['bookmark'], 'integer'],
            [['name'], 'string', 'max' => 150],
            [['name'], 'unique'],
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
        ];
    }

    // TODO it should be stored in db
    /** @return string[] */
    public static function getFileNamesOfUser($username)
    {
        $userDir = Yii::getAlias('@uploads') . "/$username";
        $fileNames = [];
        if (is_dir($userDir)) {
            if ($dir = opendir($userDir)) {
                while (($file = readdir($dir)) !== false) {
                    $fileNames[] = $file;
                }
                return $fileNames;
            } else {
                throw new \Exception('failed to open uploads');
            }
        } else {
            throw new \Exception('uploads isn\'t a directory');
        }
    }

    /** @return PdfFileRecord[] */
    public static function getFilesOfUser($username) {
        $fileNames = self::getFileNamesOfUser($username);
        
    }
}

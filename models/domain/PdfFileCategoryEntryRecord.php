<?php

namespace app\models\domain;

use Yii;

/**
 * This is the model class for table "pdf_file_category_entry".
 *
 * @property int $id
 * @property int $pdf_file_id
 * @property int $category_id
 *
 * @property PdfFileCategoryRecord $category
 * @property PdfFileRecord $pdfFile
 */
class PdfFileCategoryEntryRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pdf_file_category_entry';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pdf_file_id', 'category_id'], 'required'],
            [['pdf_file_id', 'category_id'], 'integer'],
            [['category_id', 'pdf_file_id'], 'unique', 'targetAttribute' => ['category_id', 'pdf_file_id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => PdfFileCategoryRecord::class, 'targetAttribute' => ['category_id' => 'id']],
            [['pdf_file_id'], 'exist', 'skipOnError' => true, 'targetClass' => PdfFileRecord::class, 'targetAttribute' => ['pdf_file_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pdf_file_id' => 'Pdf File ID',
            'category_id' => 'Category ID',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(PdfFileCategoryRecord::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[PdfFile]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPdfFile()
    {
        return $this->hasOne(PdfFileRecord::class, ['id' => 'pdf_file_id']);
    }
}

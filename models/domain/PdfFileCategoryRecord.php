<?php

namespace app\models\domain;

use Yii;

/**
 * This is the model class for table "pdf_file_category".
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $color
 *
 * @property UserRecord $user
 */
class PdfFileCategoryRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pdf_file_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'color'], 'required'],
            [['user_id', 'name'], 'unique', 'targetAttribute' => ['user_id', 'name']],
            [['user_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserRecord::class, 'targetAttribute' => ['user_id' => 'id']],
            [['name'], 'string', 'min' => 1, 'max' => Yii::getAlias('@MAX_CATEGORY_NAME_LENGTH')],
            [['color'], 'string', 'length' => Yii::getAlias('@CATEGORY_COLOR_LENGTH')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => 'Name',
            'color' => 'Color',
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

    public function explicitConstructor(string $name, string $color) {
        $this->user_id = Yii::$app->user->identity->id;

        $this->name = $name;
        $this->color = $color;
    }
}

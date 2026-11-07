<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "method".
 *
 * @property int $id
 * @property string $title
 *
 * @property Recipe[] $recipes
 */
class Method extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'method';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
        ];
    }

    /**
     * Gets query for [[Recipes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRecipes()
    {
        return $this->hasMany(Recipe::className(), ['id_method' => 'id']);
    }
}

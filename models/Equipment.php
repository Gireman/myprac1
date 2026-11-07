<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "equipment".
 *
 * @property int $id
 * @property string $title
 *
 * @property RecipeEquipment[] $recipeEquipments
 */
class Equipment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'equipment';
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
     * Gets query for [[RecipeEquipments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRecipeEquipments()
    {
        return $this->hasMany(RecipeEquipment::className(), ['id_equipment' => 'id']);
    }
}

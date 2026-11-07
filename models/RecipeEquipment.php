<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "recipe_equipment".
 *
 * @property int $id
 * @property int $id_recipe
 * @property int $position
 * @property int $id_equipment
 *
 * @property Equipment $equipment
 * @property Recipe $recipe
 */
class RecipeEquipment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'recipe_equipment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_recipe', 'position', 'id_equipment'], 'required'],
            [['id_recipe', 'position', 'id_equipment'], 'integer'],
            [['id_recipe'], 'exist', 'skipOnError' => true, 'targetClass' => Recipe::className(), 'targetAttribute' => ['id_recipe' => 'id']],
            [['id_equipment'], 'exist', 'skipOnError' => true, 'targetClass' => Equipment::className(), 'targetAttribute' => ['id_equipment' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_recipe' => 'Id Recipe',
            'position' => 'Position',
            'id_equipment' => 'Id Equipment',
        ];
    }

    /**
     * Gets query for [[Equipment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEquipment()
    {
        return $this->hasOne(Equipment::className(), ['id' => 'id_equipment']);
    }

    /**
     * Gets query for [[Recipe]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRecipe()
    {
        return $this->hasOne(Recipe::className(), ['id' => 'id_recipe']);
    }
}

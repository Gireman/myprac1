<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "recipe_note".
 *
 * @property int $id
 * @property int $id_recipe
 * @property int $position
 * @property int $id_note
 *
 * @property Note $note
 * @property Recipe $recipe
 */
class RecipeNote extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'recipe_note';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_recipe', 'position', 'id_note'], 'required'],
            [['id_recipe', 'position', 'id_note'], 'integer'],
            [['id_recipe'], 'exist', 'skipOnError' => true, 'targetClass' => Recipe::className(), 'targetAttribute' => ['id_recipe' => 'id']],
            [['id_note'], 'exist', 'skipOnError' => true, 'targetClass' => Note::className(), 'targetAttribute' => ['id_note' => 'id']],
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
            'id_note' => 'Id Note',
        ];
    }

    /**
     * Gets query for [[Note]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNote()
    {
        return $this->hasOne(Note::className(), ['id' => 'id_note']);
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

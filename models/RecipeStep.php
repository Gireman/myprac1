<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "recipe_step".
 *
 * @property int $id
 * @property int $id_recipe
 * @property int $position
 * @property string $title
 * @property string $text
 *
 * @property Recipe $recipe
 */
class RecipeStep extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'recipe_step';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_recipe', 'position', 'title', 'text'], 'required'],
            [['id_recipe', 'position'], 'integer'],
            [['title', 'text'], 'string', 'max' => 255],
            [['id_recipe'], 'exist', 'skipOnError' => true, 'targetClass' => Recipe::className(), 'targetAttribute' => ['id_recipe' => 'id']],
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
            'title' => 'Title',
            'text' => 'Text',
        ];
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

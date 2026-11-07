<?php

namespace app\models;

use Yii;
use yii\web\ServerErrorHttpException;

/**
 * This is the model class for table "favourite".
 *
 * @property int $id
 * @property int $id_user
 * @property int $id_recipe
 *
 * @property Recipe $recipe
 * @property User $user
 */
class Favourite extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'favourite';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'id_recipe'], 'required'],
            [['id_user', 'id_recipe'], 'integer'],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
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
            'id_user' => 'Id User',
            'id_recipe' => 'Id Recipe',
        ];
    }

    /**
     * ID избранных рецептов пользователя.
     *
     * @param int $userId
     * @return int[]
     */
    public static function recipeIdsForUser($userId)
    {
        $ids = static::find()
            ->select('id_recipe')
            ->where(['id_user' => $userId])
            ->orderBy(['id' => SORT_ASC])
            ->column();

        return array_values(array_unique(array_map('intval', $ids)));
    }

    /**
     * Добавляет рецепт в избранное или убирает его оттуда.
     *
     * @param int $userId
     * @param int $recipeId
     * @return bool true — теперь в избранном, false — удалён из избранного
     * @throws ServerErrorHttpException если не удалось сохранить
     */
    public static function toggle($userId, $recipeId)
    {
        $exists = static::find()->where(['id_user' => $userId, 'id_recipe' => $recipeId])->exists();

        if ($exists) {
            // Запись удаляется из БД
            static::deleteAll(['id_user' => $userId, 'id_recipe' => $recipeId]);
            return false;
        }

        $favourite = new static(['id_user' => $userId, 'id_recipe' => $recipeId]);
        if (!$favourite->save()) {
            throw new ServerErrorHttpException('Не удалось сохранить избранное.');
        }

        return true;
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

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }
}
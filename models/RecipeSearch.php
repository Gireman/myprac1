<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * RecipeSearch represents the model behind the search form of `app\models\Recipe`.
 * Используется в админке: поиск по названию, фильтры по способу и сложности, сортировка.
 */
class RecipeSearch extends Recipe
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_method', 'card_minutes', 'taste_acidity', 'taste_body', 'taste_sweetness', 'has_milk', 'taste_minutes'], 'integer'],
            [['title', 'level', 'roast', 'description', 'img', 'img_alt', 'pill_color', 'fact_coffee', 'fact_water', 'fact_time', 'tip'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        // joinWith нужен для сортировки по названию способа; все поля ниже — с префиксом recipe.
        $query = Recipe::find()->alias('recipe')->joinWith('method');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
            'sort' => [
                'defaultOrder' => ['id' => SORT_ASC],
                'attributes' => [
                    'id' => [
                        'asc' => ['recipe.id' => SORT_ASC],
                        'desc' => ['recipe.id' => SORT_DESC],
                    ],
                    'title' => [
                        'asc' => ['recipe.title' => SORT_ASC],
                        'desc' => ['recipe.title' => SORT_DESC],
                    ],
                    'id_method' => [
                        'asc' => ['method.title' => SORT_ASC, 'recipe.id' => SORT_ASC],
                        'desc' => ['method.title' => SORT_DESC, 'recipe.id' => SORT_ASC],
                    ],
                    'level' => [
                        'asc' => ['recipe.level' => SORT_ASC],
                        'desc' => ['recipe.level' => SORT_DESC],
                    ],
                    'card_minutes' => [
                        'asc' => ['recipe.card_minutes' => SORT_ASC],
                        'desc' => ['recipe.card_minutes' => SORT_DESC],
                    ],
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'recipe.id' => $this->id,
            'recipe.id_method' => $this->id_method,
            'recipe.level' => $this->level,
        ]);

        $query->andFilterWhere(['like', 'recipe.title', $this->title]);

        return $dataProvider;
    }
}

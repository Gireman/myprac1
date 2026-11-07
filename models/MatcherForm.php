<?php

namespace app\models;

use yii\base\Model;

/**
 * Тест «Подобрать вкус»: ответы пользователя и расчёт подходящего рецепта.
 * Профили вкуса берутся из таблицы recipe (taste_*, has_milk, taste_minutes).
 */
class MatcherForm extends Model
{
    public $mood = 'Легко и свежо';
    public $format = 'Чистый кофе';
    public $effort = '5 минут';
    public $acidity = 65;

    public static function moods()
    {
        return ['Легко и свежо', 'Мягко и уютно', 'Бодро и насыщенно'];
    }

    public static function formats()
    {
        return ['Чистый кофе', 'С молоком', 'По-разному'];
    }

    public static function efforts()
    {
        return ['До 2 минут', '5 минут', 'Можно не спешить'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mood', 'format', 'effort', 'acidity'], 'required'],
            ['mood', 'in', 'range' => self::moods()],
            ['format', 'in', 'range' => self::formats()],
            ['effort', 'in', 'range' => self::efforts()],
            ['acidity', 'integer', 'min' => 0, 'max' => 100],
        ];
    }

    /**
     * Подбирает рецепт. Вызывать после validate().
     *
     * @return array|null [recipe, title, origins, acidity, sweetness, body]
     */
    public function match()
    {
        $acidity = (int)$this->acidity;

        $desiredBody = $this->mood === 'Бодро и насыщенно' ? 90 : ($this->mood === 'Мягко и уютно' ? 65 : 30);
        $desiredSweetness = ($this->mood === 'Мягко и уютно' || $this->format === 'С молоком') ? 88 : 62;
        $effortMinutes = $this->effort === 'До 2 минут' ? 2 : ($this->effort === '5 минут' ? 5 : 9);
        $wantsMilk = $this->format === 'С молоком';

        $recipes = Recipe::find()
            ->with(['method', 'notes'])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        $best = null;
        $bestScore = null;

        foreach ($recipes as $recipe) {
            $formatPenalty = 0;
            if ($this->format !== 'По-разному' && (bool)$recipe->has_milk !== $wantsMilk) {
                $formatPenalty = 65;
            }
            $effortPenalty = abs($recipe->taste_minutes - $effortMinutes) * 5;

            $score = abs($recipe->taste_acidity - $acidity) * 1.2
                + abs($recipe->taste_body - $desiredBody)
                + abs($recipe->taste_sweetness - $desiredSweetness) * 0.65
                + $formatPenalty
                + $effortPenalty;

            // строгое «меньше»: при равенстве остаётся рецепт с меньшим id
            if ($best === null || $score < $bestScore) {
                $best = $recipe;
                $bestScore = $score;
            }
        }

        if ($best === null) {
            return null;
        }

        if ($this->format === 'С молоком') {
            $title = 'Мягкий гедонист';
        } elseif ($acidity > 68) {
            $title = 'Яркий исследователь';
        } elseif ($desiredBody > 75) {
            $title = 'Смелый классик';
        } else {
            $title = 'Спокойный баланс';
        }

        if ($best->taste_acidity > 65) {
            $origins = ['Эфиопии или Кении', 'цитруса, цветов, ягод и чая'];
        } elseif ($best->taste_body > 75) {
            $origins = ['Бразилии или Колумбии', 'какао, орехов и карамели'];
        } else {
            $origins = ['Гватемалы или Коста-Рики', 'яблока, тростника и сухофруктов'];
        }

        return [
            'recipe' => $best,
            'title' => $title,
            'origins' => $origins,
            'acidity' => (int)round(($acidity + $best->taste_acidity) / 2),
            'sweetness' => (int)round(($desiredSweetness + $best->taste_sweetness) / 2),
            'body' => (int)round(($desiredBody + $best->taste_body) / 2),
        ];
    }
}

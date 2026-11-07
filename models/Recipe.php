<?php

namespace app\models;

use Yii;
use app\components\RecipeImage;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "recipe".
 *
 * @property int $id
 * @property string $title
 * @property int $id_method
 * @property string $level
 * @property string $roast
 * @property string $description
 * @property int $card_minutes
 * @property string $img
 * @property string $img_alt
 * @property string $pill_color
 * @property string $fact_coffee
 * @property string $fact_water
 * @property string $fact_time
 * @property string $tip
 * @property int $taste_acidity
 * @property int $taste_body
 * @property int $taste_sweetness
 * @property int $has_milk
 * @property int $taste_minutes
 *
 * @property Favourite[] $favourites
 * @property Method $method
 * @property RecipeEquipment[] $recipeEquipments
 * @property RecipeNote[] $recipeNotes
 * @property RecipeStep[] $recipeSteps
 * @property Note[] $notes
 * @property Equipment[] $equipments
 * @property Note[] $orderedNotes
 * @property Equipment[] $orderedEquipments
 */
class Recipe extends \yii\db\ActiveRecord
{
    /** Допустимые значения ENUM recipe.level */
    const LEVELS = ['Легко', 'Средне', 'Точно'];

    /**
     * Поля формы админки (в БД их нет, при сохранении раскладываются по связанным таблицам).
     */

    /** @var string[] названия нот по порядку */
    public $notesInput = [];

    /** @var string[] названия оборудования по порядку */
    public $equipmentInput = [];

    /** @var array[] шаги по порядку: [['title' => '', 'text' => ''], ...] */
    public $stepsInput = [];

    /** @var UploadedFile|null загруженная картинка */
    public $imgFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'recipe';
    }

    /**
     * Удаление рецепта выполняется в транзакции (вместе с beforeDelete).
     *
     * {@inheritdoc}
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_DELETE,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $trimmed = ['title', 'roast', 'description', 'img_alt', 'pill_color', 'fact_coffee', 'fact_water', 'fact_time', 'tip'];

        return [
            [$trimmed, 'trim'],
            [['title', 'id_method', 'level', 'roast', 'description', 'card_minutes', 'img_alt', 'pill_color', 'fact_coffee', 'fact_water', 'fact_time', 'tip', 'taste_acidity', 'taste_body', 'taste_sweetness', 'has_milk', 'taste_minutes'], 'required'],
            [['img'], 'required', 'when' => function ($model) {
                return !$model->imgFile;
            }, 'message' => 'Выберите картинку из списка или загрузите новую.'],

            [['id_method'], 'integer'],
            [['card_minutes', 'taste_minutes'], 'integer', 'min' => 1, 'max' => 600],
            [['taste_acidity', 'taste_body', 'taste_sweetness'], 'integer', 'min' => 0, 'max' => 100],
            [['has_milk'], 'in', 'range' => [0, 1], 'message' => 'Выберите «С молоком» или «Без молока».'],
            [['level'], 'in', 'range' => self::LEVELS],

            [['title', 'roast', 'description', 'img', 'img_alt', 'fact_coffee', 'fact_water', 'fact_time', 'tip'], 'string', 'max' => 255],
            [['pill_color'], 'match', 'pattern' => '/^#(?:[0-9a-f]{3}|[0-9a-f]{6})$/i', 'message' => 'Цвет нужен в формате #RRGGBB, например #d8f35d.'],

            // Имя попадает в путь к файлу, поэтому — только безопасные символы и только существующая картинка
            [['img'], 'match', 'pattern' => '/^[A-Za-z0-9_-]+$/', 'message' => 'Недопустимое имя картинки.'],
            [['img'], 'validateImgExists'],
            [['imgFile'], 'image',
                'skipOnEmpty' => true,
                'extensions' => ['jpg', 'jpeg', 'png', 'webp'],
                'checkExtensionByMimeType' => true,
                'minWidth' => 800,
                'maxSize' => 8 * 1024 * 1024,
                'wrongExtension' => 'Подойдут только файлы JPG, PNG и WebP.',
                'underWidth' => 'Картинка слишком маленькая: нужна ширина не меньше 800 px.',
                'tooBig' => 'Файл слишком большой: не больше 8 МБ.',
            ],

            [['id_method'], 'exist', 'skipOnError' => true, 'targetClass' => Method::class, 'targetAttribute' => ['id_method' => 'id']],

            [['notesInput', 'equipmentInput', 'stepsInput'], 'safe'],
            [['notesInput'], 'validateTitles', 'params' => ['label' => 'Нота']],
            [['equipmentInput'], 'validateTitles', 'params' => ['label' => 'Оборудование']],
            [['stepsInput'], 'validateSteps', 'skipOnEmpty' => false],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'id_method' => 'Способ заваривания',
            'level' => 'Сложность',
            'roast' => 'Обжарка',
            'description' => 'Описание',
            'card_minutes' => 'Время на карточке, мин',
            'img' => 'Картинка из библиотеки',
            'imgFile' => 'Загрузить новую картинку',
            'img_alt' => 'Описание картинки (alt)',
            'pill_color' => 'Цвет плашки способа',
            'fact_coffee' => 'Кофе',
            'fact_water' => 'Вода',
            'fact_time' => 'Время',
            'tip' => 'Совет',
            'taste_acidity' => 'Кислотность',
            'taste_body' => 'Плотность',
            'taste_sweetness' => 'Сладость',
            'has_milk' => 'Молоко',
            'taste_minutes' => 'Время на приготовление в подборе вкуса, мин',
            'notesInput' => 'Вкусовые ноты',
            'equipmentInput' => 'Оборудование',
            'stepsInput' => 'Шаги приготовления',
        ];
    }

    /**
     * Значения по умолчанию для формы создания.
     */
    public function applyDefaults()
    {
        $this->level = self::LEVELS[0];
        $this->pill_color = '#d8f35d';
        $this->card_minutes = 5;
        $this->taste_minutes = 5;
        $this->taste_acidity = 50;
        $this->taste_body = 50;
        $this->taste_sweetness = 50;
        $this->has_milk = 0;
    }

    /* ------------------------------------------------------------------
     * Связи
     * ---------------------------------------------------------------- */

    /**
     * Gets query for [[Favourites]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFavourites()
    {
        return $this->hasMany(Favourite::className(), ['id_recipe' => 'id']);
    }

    /**
     * Gets query for [[Method]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMethod()
    {
        return $this->hasOne(Method::className(), ['id' => 'id_method']);
    }

    /**
     * Gets query for [[RecipeEquipments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRecipeEquipments()
    {
        return $this->hasMany(RecipeEquipment::className(), ['id_recipe' => 'id']);
    }

    /**
     * Gets query for [[RecipeNotes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRecipeNotes()
    {
        return $this->hasMany(RecipeNote::className(), ['id_recipe' => 'id']);
    }

    /**
     * Gets query for [[RecipeSteps]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRecipeSteps()
    {
        return $this->hasMany(RecipeStep::className(), ['id_recipe' => 'id'])->orderBy(['position' => SORT_ASC]);
    }

    /**
     * Ноты рецепта через связку recipe_note.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotes()
    {
        return $this->hasMany(Note::class, ['id' => 'id_note'])->via('recipeNotes')->orderBy(['note.id' => SORT_ASC]);
    }

    /**
     * Оборудование рецепта через связку recipe_equipment.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEquipments()
    {
        return $this->hasMany(Equipment::class, ['id' => 'id_equipment'])->via('recipeEquipments')->orderBy(['equipment.id' => SORT_ASC]);
    }

    /**
     * Ноты в порядке, который задан в админке (recipe_note.position).
     * Связь notes сортируется по id, поэтому порядок применяем здесь.
     *
     * @return Note[]
     */
    public function getOrderedNotes()
    {
        return $this->sortByPosition($this->notes, $this->recipeNotes, 'id_note');
    }

    /**
     * Оборудование в порядке, который задан в админке (recipe_equipment.position).
     *
     * @return Equipment[]
     */
    public function getOrderedEquipments()
    {
        return $this->sortByPosition($this->equipments, $this->recipeEquipments, 'id_equipment');
    }

    /**
     * Названия нот массивом: ['Цитрус', 'жасмин', 'мёд'] — в порядке из админки.
     *
     * @return string[]
     */
    public function getNoteTitles()
    {
        return ArrayHelper::getColumn($this->getOrderedNotes(), 'title');
    }

    /**
     * Размеры картинки [width, height] — в БД их нет, берём из файла.
     * Нужны для width/height у <img>, чтобы страница не прыгала при загрузке.
     *
     * @param int $width
     * @return int[]
     */
    public function getImgSize($width = 800)
    {
        $path = Yii::getAlias('@webroot/img/' . $this->img . '-' . $width . '.jpg');
        $size = is_file($path) ? @getimagesize($path) : false;
        return $size ? [(int)$size[0], (int)$size[1]] : [800, 600];
    }

    /**
     * @param object[] $items модели справочника (Note / Equipment)
     * @param object[] $links строки связки с полем position
     * @param string $fk поле связки, указывающее на справочник
     * @return object[]
     */
    private function sortByPosition(array $items, array $links, $fk)
    {
        $positions = ArrayHelper::map($links, $fk, 'position');
        usort($items, function ($a, $b) use ($positions) {
            $pa = $positions[$a->id] ?? PHP_INT_MAX;
            $pb = $positions[$b->id] ?? PHP_INT_MAX;
            return ($pa <=> $pb) ?: ($a->id <=> $b->id);
        });
        return $items;
    }

    /* ------------------------------------------------------------------
     * Поля формы: заполнение, нормализация, проверка
     * ---------------------------------------------------------------- */

    /**
     * Заполняет поля формы из БД (для страницы редактирования).
     */
    public function fillInputsFromRelations()
    {
        $this->notesInput = array_values(ArrayHelper::getColumn($this->getOrderedNotes(), 'title'));
        $this->equipmentInput = array_values(ArrayHelper::getColumn($this->getOrderedEquipments(), 'title'));
        $this->stepsInput = [];
        foreach ($this->recipeSteps as $step) {
            $this->stepsInput[] = ['title' => $step->title, 'text' => $step->text];
        }
    }

    /**
     * Сбрасывает поля формы. Вызывать перед load(): если в форме удалили все строки,
     * браузер не пришлёт ключ вовсе, и в модели остались бы прежние значения.
     */
    public function clearInputs()
    {
        $this->notesInput = [];
        $this->equipmentInput = [];
        $this->stepsInput = [];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeValidate()
    {
        $this->normalizeInputs();
        return parent::beforeValidate();
    }

    /**
     * Приводит поля формы к аккуратному виду: обрезает пробелы, убирает пустые строки.
     */
    public function normalizeInputs()
    {
        $this->notesInput = $this->normalizeTitles($this->notesInput);
        $this->equipmentInput = $this->normalizeTitles($this->equipmentInput);

        $steps = [];
        foreach ((array)$this->stepsInput as $row) {
            if (!is_array($row)) {
                continue;
            }
            $title = trim((string)($row['title'] ?? ''));
            $text = trim((string)($row['text'] ?? ''));
            if ($title === '' && $text === '') {
                continue; // полностью пустую строку игнорируем
            }
            $steps[] = ['title' => $title, 'text' => $text];
        }
        $this->stepsInput = $steps;
    }

    /**
     * @param mixed $values
     * @return string[]
     */
    private function normalizeTitles($values)
    {
        $result = [];
        foreach ((array)$values as $value) {
            if (!is_scalar($value)) {
                continue;
            }
            $title = trim(preg_replace('/\s+/u', ' ', (string)$value));
            if ($title !== '') {
                $result[] = $title;
            }
        }
        return $result;
    }

    /**
     * Проверка существования выбранной картинки (если новую не загружают).
     *
     * @param string $attribute
     */
    public function validateImgExists($attribute)
    {
        if ($this->imgFile || $this->$attribute === '' || $this->$attribute === null) {
            return;
        }
        if (!RecipeImage::exists($this->$attribute)) {
            $this->addError($attribute, 'Картинка не найдена в папке web/img. Выберите другую или загрузите новую.');
        }
    }

    /**
     * Ноты и оборудование: длина названий и отсутствие повторов.
     *
     * @param string $attribute
     * @param array $params
     */
    public function validateTitles($attribute, $params)
    {
        $label = $params['label'] ?? 'Значение';
        $seen = [];
        foreach ($this->$attribute as $title) {
            if (mb_strlen($title) > 255) {
                $this->addError($attribute, $label . ' «' . mb_substr($title, 0, 30) . '…» длиннее 255 символов.');
                return;
            }
            $key = mb_strtolower($title);
            if (isset($seen[$key])) {
                $this->addError($attribute, $label . ' «' . $title . '» указано дважды.');
                return;
            }
            $seen[$key] = true;
        }
    }

    /**
     * Шаги: минимум один, у каждого есть название и описание.
     *
     * @param string $attribute
     */
    public function validateSteps($attribute)
    {
        $steps = $this->$attribute;
        if (!$steps) {
            $this->addError($attribute, 'Добавьте хотя бы один шаг.');
            return;
        }
        foreach ($steps as $i => $step) {
            $n = $i + 1;
            if ($step['title'] === '') {
                $this->addError($attribute, "Шаг $n: укажите название.");
            } elseif (mb_strlen($step['title']) > 255) {
                $this->addError($attribute, "Шаг $n: название длиннее 255 символов.");
            }
            if ($step['text'] === '') {
                $this->addError($attribute, "Шаг $n: укажите описание.");
            } elseif (mb_strlen($step['text']) > 255) {
                $this->addError($attribute, "Шаг $n: описание длиннее 255 символов (сейчас " . mb_strlen($step['text']) . ').');
            }
        }
    }

    /* ------------------------------------------------------------------
     * Сохранение и удаление
     * ---------------------------------------------------------------- */

    /**
     * Сохраняет рецепт вместе с картинкой, шагами, нотами и оборудованием — всё или ничего.
     * Перед вызовом нужно присвоить $imgFile (UploadedFile::getInstance).
     *
     * @return bool
     */
    public function saveWithRelations()
    {
        if (!$this->validate()) {
            return false;
        }

        $oldImg = $this->isNewRecord ? null : $this->getOldAttribute('img');
        $newImg = null;
        $transaction = static::getDb()->beginTransaction();

        try {
            if ($this->imgFile instanceof UploadedFile) {
                $newImg = RecipeImage::generate($this->imgFile->tempName);
                $this->img = $newImg;
            }

            if (!$this->save(false)) {
                throw new \RuntimeException('Не удалось сохранить рецепт.');
            }

            $this->syncSteps();
            $this->syncLinked(Note::class, RecipeNote::class, 'id_note', $this->notesInput);
            $this->syncLinked(Equipment::class, RecipeEquipment::class, 'id_equipment', $this->equipmentInput);

            $transaction->commit();
        } catch (\RuntimeException $e) {
            $transaction->rollBack();
            $this->discardNewImage($newImg, $oldImg);
            $this->addError('general', $e->getMessage());
            return false;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            $this->discardNewImage($newImg, $oldImg);
            Yii::error($e->getMessage() . "\n" . $e->getTraceAsString(), __METHOD__);
            $this->addError('general', 'Не удалось сохранить рецепт из-за ошибки на сервере.' . (YII_DEBUG ? ' ' . $e->getMessage() : ''));
            return false;
        }

        // Старая картинка больше не нужна, если её загрузили через админку и никто её не использует
        if ($oldImg !== null && $oldImg !== $this->img) {
            self::deleteImageIfUnused($oldImg);
        }

        return true;
    }

    /**
     * Шаги: удаляем старые и записываем заново с position 1..N.
     */
    private function syncSteps()
    {
        RecipeStep::deleteAll(['id_recipe' => $this->id]);

        $rows = [];
        foreach (array_values($this->stepsInput) as $i => $step) {
            $rows[] = [$this->id, $i + 1, $step['title'], $step['text']];
        }
        if ($rows) {
            static::getDb()->createCommand()
                ->batchInsert(RecipeStep::tableName(), ['id_recipe', 'position', 'title', 'text'], $rows)
                ->execute();
        }
    }

    /**
     * Ноты/оборудование: находим запись справочника по названию (или создаём новую)
     * и пересобираем связку с position 1..N.
     *
     * @param string $dictClass Note::class | Equipment::class
     * @param string $linkClass RecipeNote::class | RecipeEquipment::class
     * @param string $fk id_note | id_equipment
     * @param string[] $titles
     */
    private function syncLinked($dictClass, $linkClass, $fk, array $titles)
    {
        $known = [];
        foreach ($dictClass::find()->orderBy(['id' => SORT_ASC])->all() as $item) {
            $key = mb_strtolower(trim($item->title));
            if (!isset($known[$key])) {
                $known[$key] = (int)$item->id;
            }
        }

        $rows = [];
        foreach ($titles as $i => $title) {
            $key = mb_strtolower($title);
            if (!isset($known[$key])) {
                $item = new $dictClass(['title' => $title]);
                if (!$item->save()) {
                    throw new \RuntimeException('Не удалось добавить «' . $title . '» в справочник.');
                }
                $known[$key] = (int)$item->id;
            }
            $rows[] = [$this->id, $i + 1, $known[$key]];
        }

        $linkClass::deleteAll(['id_recipe' => $this->id]);
        if ($rows) {
            static::getDb()->createCommand()
                ->batchInsert($linkClass::tableName(), ['id_recipe', 'position', $fk], $rows)
                ->execute();
        }
    }

    /**
     * Откат: убираем только что созданные файлы картинки.
     *
     * @param string|null $newImg
     * @param string|null $oldImg
     */
    private function discardNewImage($newImg, $oldImg)
    {
        if ($newImg !== null) {
            RecipeImage::delete($newImg);
            // возвращаем модели прежнее значение, чтобы форма не ссылалась на удалённые файлы
            $this->img = $oldImg;
        }
    }

    /**
     * Удаляет файлы картинки, если она была загружена через админку и не используется другими рецептами.
     *
     * @param string $img
     */
    private static function deleteImageIfUnused($img)
    {
        if (strpos((string)$img, RecipeImage::PREFIX) !== 0) {
            return; // готовые картинки сайта не трогаем
        }
        if (!self::find()->where(['img' => $img])->exists()) {
            RecipeImage::delete($img);
        }
    }

    /**
     * В БД у favourite внешний ключ RESTRICT, а остальное зависит от настроек FK,
     * поэтому связанные записи убираем явно (метод вызывается внутри транзакции удаления).
     *
     * {@inheritdoc}
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        Favourite::deleteAll(['id_recipe' => $this->id]);
        RecipeStep::deleteAll(['id_recipe' => $this->id]);
        RecipeNote::deleteAll(['id_recipe' => $this->id]);
        RecipeEquipment::deleteAll(['id_recipe' => $this->id]);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function afterDelete()
    {
        parent::afterDelete();
        self::deleteImageIfUnused($this->img);
    }
}

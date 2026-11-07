<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $mail
 * @property string $password
 * @property string $fullname
 * @property string $phone
 * @property int $role
 *
 * @property Favourite[] $favourites
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    public $consent;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'mail', 'password', 'fullname', 'phone'], 'required'],
            [['role'], 'integer'],
            ['mail', 'email', 'message' => 'Невреный формат почты'],

            //Валидвция логина
            ['username', 'unique', 'message' => 'Такой логин уже зарегистрирован'],
            ['username', 'string', 'min' => '6', 'message' => 'Логин не менее 6 символов'],
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9]/', 'message' => 'Только латинские буквы и цифры'],

            //Валидация ФИО
            ['fullname', 'match', 'pattern' => '/^[а-яА-ЯёЁ\s]+$/u', 'message' => 'Только кириллица и пробелы'],

            ['consent', 'required', 'requiredValue' => 1, 'on' => 'register', 'message' => 'Необходимо согласие с правилами'],

            ['phone', 'match', 'pattern' => '/^\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}$/', 'message' => 'Телефон должен быть в формате +7(XXX)-XXX-XX-XX.'],

            [['username', 'mail', 'password', 'fullname', 'phone'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'mail' => 'Почта',
            'password' => 'Пароль',
            'fullname' => 'ФИО',
            'phone' => 'Телефон',
            'role' => 'Роль',
        ];
    }

    /**
     * Gets query for [[Favourites]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFavourites()
    {
        return $this->hasMany(Favourite::className(), ['id_user' => 'id']);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null; // Не используем токены
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null; // Не используем authKey
    }

    public function validateAuthKey($authKey)
    {
        return false; // Не используем authKey
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public function validatePassword($password)
    {
        //return Yii::$app->security->validatePassword($password, $this->password);
        return $this->password === $password;
    }

//     public function register()
// {
//     if ($this->validate()) {
//         //$this->password = Yii::$app->security->generatePasswordHash($this->password); // Хэшируем пароль
//         return $this->save(false); // Сохраняем пользователя без повторной валидации
//     }
//     return false;
// }

public function register()
{
    $this->role = 0; // обычный пользователь, подставь своё значение

    return $this->save(); // save() сам запускает validate()
}
}

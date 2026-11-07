<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Method;
use app\models\Recipe;
use app\models\Favourite;
use app\models\MatcherForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'toggle-favorite' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    // public function actionMatcher()
    // {
    //     return $this->render('matcher');
    // }



    // public function actionRegistration()
    // {
    //     return $this->render('registration');
    // }


    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['/site/index']);
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(['/site/index']);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
 * Каталог рецептов.
 */
public function actionRecipes()
{
    $recipes = Recipe::find()
        ->with(['method', 'notes'])
        ->orderBy(['id' => SORT_ASC])
        ->all();
 
    $methods = Method::find()
        ->orderBy(['id' => SORT_ASC])
        ->all();
 
    return $this->render('recipes', [
        'recipes' => $recipes,
        'methods' => $methods,
    ]);
}
 
/**
 * Страница рецепта: recipe?id=N.
 * Без id показываем первый рецепт, как раньше в app.js.
 */
public function actionRecipe($id = null)
{
    $query = Recipe::find()->with(['method', 'notes', 'equipments', 'recipeSteps']);
 
    if ($id === null) {
        $recipe = $query->orderBy(['id' => SORT_ASC])->one();
    } else {
        $recipe = $query->where(['id' => (int)$id])->one();
    }
 
    if ($recipe === null) {
        // Страница «Рецепт не найден» рисуется во view, но с кодом 404
        Yii::$app->response->statusCode = 404;
    }
 
    return $this->render('recipe', ['recipe' => $recipe]);
}

// public function actionRecipes()
// {
//     return $this->render('recipes');
// }

// public function actionRecipe()
// {
//     return $this->render('recipe');
// }

/**
 * Добавить/убрать рецепт из избранного текущего пользователя (AJAX, POST: id).
 * Ответ: {ok: true, active: bool, ids: [..]}
 */
public function actionToggleFavorite()
{
    Yii::$app->response->format = Response::FORMAT_JSON;
 
    // Гость: id пользователя нет — избранное недоступно
    if (Yii::$app->user->isGuest) {
        Yii::$app->response->statusCode = 401;
        return ['ok' => false, 'message' => 'Нужно войти'];
    }
 
    $recipeId = (int)Yii::$app->request->post('id');
    if (!Recipe::find()->where(['id' => $recipeId])->exists()) {
        Yii::$app->response->statusCode = 404;
        return ['ok' => false, 'message' => 'Рецепт не найден'];
    }
 
    $userId = Yii::$app->user->identity->id;
    $active = Favourite::toggle($userId, $recipeId);
 
    return [
        'ok' => true,
        'active' => $active,
        'ids' => Favourite::recipeIdsForUser($userId),
    ];
}

 /**
     * Тест «Подобрать вкус». Ответы приходят GET-параметрами
     * (mood, format, effort, acidity), поэтому результат можно открыть по ссылке.
     * Параметр edit=1 возвращает форму с уже выбранными ответами.
     */
    public function actionMatcher()
    {
        $model = new MatcherForm();
        $result = null;
        $request = Yii::$app->request;
 
        $answers = array_intersect_key(
            $request->get(),
            array_flip(['mood', 'format', 'effort', 'acidity'])
        );
 
        if ($answers && $model->load($answers, '')) {
            if ($model->validate()) {
                if (!$request->get('edit')) {
                    $result = $model->match();
                }
            } else {
                // Параметры подделаны или повреждены — показываем чистую форму
                $model = new MatcherForm();
            }
        }
 
        return $this->render('matcher', [
            'model' => $model,
            'result' => $result,
        ]);
    }

}

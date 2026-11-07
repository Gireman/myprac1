<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritDoc
     */
public function behaviors()
{
    return array_merge(
        parent::behaviors(),
        [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        // регистрация: и гостям, и вошедшим
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['?', '@'],
                    ],
                    [
                        // остальное только админу
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return !Yii::$app->user->isGuest
                                && Yii::$app->user->identity->role == 1; // твоё значение роли админа
                        },
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    Yii::$app->response->redirect(['/site/index'])->send();
                    Yii::$app->end();
                },
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ]
    );
}

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /*public function actionCreate()
    {
        $model = new User();
    
         if ($model->load(Yii::$app->request->post())) {
             if ($model->register()) {
                 Yii::$app->session->setFlash('success', 'Регистрация прошла успешно!');
                return $this->redirect(['site/index']); // Перенаправляем на главную страницу
             } else {
                 Yii::$app->session->setFlash('error', 'Ошибка при регистрации.');
             }
         }
    
         return $this->render('create', [
             'model' => $model,
         ]);
    }*/

    public function actionCreate()
{
    $model = new User(['scenario' => 'register']);

    if ($model->load(Yii::$app->request->post())) {
        if ($model->register()) {
            Yii::$app->session->setFlash('success', 'Регистрация прошла успешно!');
            return $this->redirect(['/site/index']);
        }
    }

    return $this->render('/site/registration', [
        'model' => $model,
    ]);
}

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

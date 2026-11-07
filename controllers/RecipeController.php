<?php

namespace app\controllers;

use Yii;
use app\models\Recipe;
use app\models\RecipeSearch;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * RecipeController implements the CRUD actions for Recipe model.
 * Раздел админки: доступен только пользователю с ролью 1 (как UserController).
 */
class RecipeController extends Controller
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
                            'allow' => true,
                            'matchCallback' => function ($rule, $action) {
                                return !Yii::$app->user->isGuest
                                    && Yii::$app->user->identity->role == 1;
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
     * Lists all Recipe models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RecipeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Recipe model.
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
     * Creates a new Recipe model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Recipe();

        if ($this->request->isPost) {
            $model->clearInputs();
            if ($model->load($this->request->post())) {
                $model->imgFile = UploadedFile::getInstance($model, 'imgFile');
                if ($model->saveWithRelations()) {
                    Yii::$app->session->setFlash('success', 'Рецепт «' . $model->title . '» создан.');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->applyDefaults();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Recipe model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost) {
            $model->clearInputs();
            if ($model->load($this->request->post())) {
                $model->imgFile = UploadedFile::getInstance($model, 'imgFile');
                if ($model->saveWithRelations()) {
                    Yii::$app->session->setFlash('success', 'Изменения в рецепте «' . $model->title . '» сохранены.');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->fillInputsFromRelations();
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Recipe model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $title = $model->title;

        try {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Рецепт «' . $title . '» удалён.');
        } catch (\yii\db\Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            Yii::$app->session->setFlash('error', 'Не удалось удалить рецепт «' . $title . '»: он связан с другими данными.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Recipe model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Recipe the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Recipe::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Рецепт не найден.');
    }
}

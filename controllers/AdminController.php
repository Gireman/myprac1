<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * AdminController implements the CRUD actions for Favourite model.
 */
class AdminController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        // Дополнительная проверка перед каждым действием на роль. Если роль не Админ - редирект на главную страницу
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->role !== 1) {
            return $this->redirect(['site/index']);
        }

        return true;
    }

    /**
     * Lists all Role models.
     *
     * @return string
     */
    public function actionIndex()
    {
        
        return $this->render('index');
    }

}


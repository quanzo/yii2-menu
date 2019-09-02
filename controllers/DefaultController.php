<?php

namespace x51\yii2\modules\menu\controllers;

use x51\yii2\modules\menu\models\Menu;
use x51\yii2\modules\menu\models\MenuSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use \yii\base\DynamicModel;


/**
 * DefaultController implements the CRUD actions for Menu model.
 */
class DefaultController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            /*'access' => [
        'class' => AccessControl::className(),
        'rules' => [
        [
        'allow' => true,
        'roles' => ['menu_manage'],
        ],
        [
        'allow' => false,
        'roles' => ['?'],
        ],
        ],
        ],*/
        ];
    }

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // групповая операция
        $request = \Yii::$app->request;
        if ($request->post('operation', false) && $request->post('sel', false)) {
            $pmKey = Menu::primaryKey();
            if (is_array($pmKey)) {
                $pmKey = current($pmKey);
            }
            $op = $request->post('operation');
            $sel = $request->post('sel', false);
            foreach ($sel as &$id) {
                $id = intval($id);
            }
            switch ($op) {
                case 'set-menu':{
                        // создаем динамическую модель с одним полем
                        $validateModel = new DynamicModel([
                            'menu' => $request->post('new-menu', ''),
                        ]);
                        // правила валидации и валидация
                        $validateModel->addRule(
                            'menu', 'string', ['max' => 75]
                        )->addRule(
                            'menu', 'safe'
                        )->validate();

                        if (!$validateModel->hasErrors()) { // если нет ошибок - вносим изменения
                            Menu::updateAll(
                                ['menu' => $validateModel->menu],
                                [$pmKey => $sel]
                            );
                        }
                        break;
                    }
                case 'delete':{
                        Menu::deleteAll([$pmKey => $sel]);
                        break;
                    }
            }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Menu model.
     * @param integer $id
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
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Menu();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

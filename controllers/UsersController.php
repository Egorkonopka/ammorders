<?php
/**
 *
 * @package    Material Dashboard Yii2
 * @author     CodersEden <hello@coderseden.com>
 * @link       https://www.coderseden.com
 * @copyright  2020 Material Dashboard Yii2 (https://www.coderseden.com)
 * @license    MIT - https://www.coderseden.com
 * @since      1.0
 */
namespace app\controllers;

use app\models\User;
use app\models\UserSearch;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Class UsersController
 * @package app\controllers
 */
class UsersController extends \yii\web\Controller
{
	/**
	 * @param \yii\base\Action $action
	 *
	 * @return bool|void
	 * @throws \yii\web\BadRequestHttpException
	 */
	public function beforeAction( $action )
	{
		if ( \Yii::$app->getUser()->isGuest ) {
			return \Yii::$app->getResponse()->redirect( \yii\helpers\Url::to(['/']) )->send();
		}
		return parent::beforeAction( $action ); // TODO: Change the autogenerated stub
	}

	/**
	 * @return array
	 */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

	/**
	 * @param array $params
	 *
	 * @return $this
	 */
	public function setViewParams(array $params = [])
	{
		$this->view->params = ArrayHelper::merge($this->view->params, $params);
		return $this;
	}

	/**
	 * @return string
	 */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(\Yii::$app->getRequest()->queryParams);
        $dataProvider->pagination->pageSize=10;

	    \Yii::$app->view->title = \Yii::t('app', 'Users');

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

	/**
	 * @param $id
	 *
	 * @return string
	 * @throws NotFoundHttpException
	 */
    public function actionView($id)
    {
        $model = $this->findModel($id);

	    \Yii::$app->view->title = \Yii::t('app', 'User {userName}', ['userName'=>$model->fullName]);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

	/**
	 * @return string|\yii\web\Response
	 */
    public function actionCreate()
    {
        $model = new User([
            'scenario' => User::SCENARIO_CREATE
        ]);

        \Yii::$app->view->title = \Yii::t('app', 'Create User');

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->user_id]);
        } else {
            return $this->render('form', [
                'action'=> 'create',
                'model' => $model,
            ]);
        }
    }

	/**
	 * @param $id
	 *
	 * @return string|\yii\web\Response
	 * @throws NotFoundHttpException
	 */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $user = \Yii::$app->getRequest()->post('User');

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->user_id]);
        } else {
        	\Yii::$app->view->title = \Yii::t('app', 'Update {userName}', ['userName'=>$model->fullName]);

            return $this->render('form', [
                'action'=> 'update',
                'model' => $model,
            ]);
        }
    }

	/**
	 * @param $id
	 *
	 * @return \yii\web\Response
	 * @throws NotFoundHttpException
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($id == 1) {
            return $this->redirect(['/users']);
        }

        $model->delete();

        return $this->redirect(['/users']);
    }

	/**
	 * @param $id
	 *
	 * @return User|null
	 * @throws NotFoundHttpException
	 */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

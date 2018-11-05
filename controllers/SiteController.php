<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Cats;

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
                        'actions' => ['logout','feeds-parser'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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
        $p= new \yii\data\ActiveDataProvider([
            'query'=>\app\models\NewsModel::getAllNews(),
            'pagination'=>[
                'pageSize'=>10,
            ],
        ]);
        return $this->render('index',['p'=>$p]);
    }

    /**
        Добавление фидов .. 
    */
    public function actionFeedsParser()
    {
        $m=new \app\models\ParserConfigurator();
        if (Yii::$app->request->isPost && $m->saveConfig(Yii::$app->request->post())){
            return $this->refresh();
        }
        return $this->render('parser-config',['m'=>$m]);
    }
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
            return $this->goBack();
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

        return $this->goHome();
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


    public function actionNewsCats()
    {
        $cid=Yii::$app->request->get('edit');
        $m=$cid? Cats::findOne($cid):new Cats();
        if (!$m)
            $m=new Cats();

        
        
        if (Yii::$app->request->isPost  ){
            $post=Yii::$app->request->post();
            if ($m->todo($post,$post['act']))
                return $this->redirect(['']);
        }


        $p=new \yii\data\ActiveDataProvider([
            'query'=>\app\models\Cats::allCats(),
            'pagination'=>[
                'pageSize'=>30,
            ],
            'sort'=>[
                'defaultOrder'=>['title'=>SORT_ASC],
            ],
        ]);
        return $this->render('news-cats',['m'=>$m,'p'=>$p]);
    }
}

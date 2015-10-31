<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\ValidarFormulario;
use app\models\ValidarFormularioAjax;

use yii\widgets\ActiveForm;
use yii\web\response;

class SiteController extends Controller
{
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
                ],
            ],
        ];
    }

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
     * Action tutorial
     */
    public function actionValidarformularioajax()
    {
        $model = new ValidarFormularioAjax;
        $msg = null;
        if ( $model->load( Yii::$app->request->post() ) && Yii::$app->request->isAjax ) 
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if( $model->load( Yii::$app->request->post() ) )
        {
            if( $model->validate() )
            {
                //acciones = consultar/registrar
                $msg = "Enhorabuena, Formulario enviado exitosamente";
                $model->nombre = null;
                $model->email = null;
            }
            else
            {
                $model->getErrors();
            }
        }
        return $this->render("validarformularioajax", ['model'=>$model, 'mensaje'=>$msg]);
    }

    public function actionValidarformulario()
    {
        $model = new ValidarFormulario;
        if ( $model->load( Yii::$app->request->post() ) ) 
        {
            if( $model->validate() )
            {
                //acciones = consultar/registrar
            }
            else
            {
                $model->getErrors();
            }
        }
        return $this->render("validarformulario", ['model'=>$model]);
    }

    public function actionRequest()
    {
        $mensaje = null;
        if( isset( $_REQUEST["nombre"] ) )
        {
            $mensaje = "Bien, has enviado tu nombre correctamente: ".$_REQUEST["nombre"];
        }   
        $this->redirect(["site/formulario", "mensaje"=>$mensaje]);
    }

    public function actionFormulario($mensaje = null)
    {
        return $this->render("formulario", ["mensaje"=>$mensaje]);
    }
    
    public function actionSaluda( $get = "Tutorial Yii")
    {
        $mensaje = "Hola Mundo";
        $numeros = [0,1,2,3,4,5];
        return $this->render(
            'saluda',[
                'saludo'=>$mensaje, 
                'numeros'=>$numeros,
                'parametroGet'=>$get
            ]);
    }
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

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

    public function actionAbout()
    {
        return $this->render('about');
    }
}

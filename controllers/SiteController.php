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
use app\models\FormAlumnos;
use app\models\Alumnos;
use app\models\FormSearch;
use yii\helpers\Html;
//  paginación
use yii\data\Pagination;
//  Delete
use yii\helpers\Url;

/**
 * Clases para validaciones AJAX
 */
use yii\widgets\ActiveForm;
use yii\web\Response;

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
    public function actionDelete()
    {
        if( Yii::$app->request->post() )
        {
            $id_alumno = Html::encode($_POST["id_alumno"]);
            if( (int)$id_alumno )
            {
                if( Alumnos::deleteAll("id_alumno=:id_alumno", [":id_alumno"=>$id_alumno]) )
                {
                    echo "Alumno con id alumno $id_alumno eliminado con exito, redireccionamos...";
                    echo "<meta http-equiv='refresh' content='3;".Url::toRoute('site/view')."'>";
                }
                else
                {
                    echo "Ha ocurrido un erro al eliminar el alumno, redireccionamos...";
                    echo "<meta http-equiv='refresh' content='3;".Url::toRoute('site/view')."'>";
                }
            }
            else
            {
                echo "Ha ocurrido un erro al eliminar el alumno, redireccionamos...";
                echo "<meta http-equiv='refresh' content='3;".Url::toRoute('site/view')."'>";
            }
        }
        else
            return $this->redirect(["site/view"]);
    }
    public function actionView()
    {
        $table = new Alumnos;
        $model = $table->find()->orderBy('clase, note_final')->all();

        $form = new FormSearch;
        $search = null;

        if( $form->load( Yii::$app->request->get() ) )
        {
            if( $form->validate() )
            {
                $search = Html::encode($form->q);
                #$model = $table->find()->where('id_alumno LIKE :data OR nombre LIKE :data OR apellidos LIKE :data')->addParams([":data"=>"%".$search."%"])->all();
                $table = Alumnos::find()
                        ->where(["like", "id_alumno", $search])
                        ->orWhere(["like", "nombre", $search])
                        ->orWhere(["like", "apellidos", $search]);
                // clonar el objeto $table una vez hecha la consulta
                // se crea la instancia para la paginacion
                $count = clone $table;
                $pages = new Pagination([
                    "pageSize" => 2,                    //  elementos por pagina
                    "totalCount" => $count->count(),    //  contamos la cantidad de registros traidos por la consulta
                    ]);
                $model = $table
                        ->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all();
            }
            else
            {
                $form->getErrors;
            }
        }
        else
        {
            $table = Alumnos::find();
            $count = clone $table;
            $pages = new Pagination([
                "pageSize" => 2,                    //  elementos por pagina
                "totalCount" => $count->count(),    //  contamos la cantidad de registros traidos por la consulta
                ]);
            $model = $table
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
        }

        return $this->render("view", ["model"=>$model, "form"=>$form, "search"=>$search, "pages"=>$pages]);
    }
    public function actionCreate()
    {
        $model = new FormAlumnos;
        $msg = null;
        if ( $model->load( Yii::$app->request->post() ) ) 
        {
            if( $model->validate() )
            {
                $table = new Alumnos;

                $table->nombre = $model->nombre;
                $table->apellidos = $model->apellidos;
                $table->clase = $model->clase;
                $table->note_final = $model->note_final;

                if ( $table->insert() ) 
                {
                    $msg = "Enhorabuena, Registro de alumno exitoso!!!";
                    $model->nombre = null;
                    $model->apellidos = null;
                    $model->clase = null;
                    $model->note_final = null;
                }
                else
                {
                    $msg = "Error, Ha ocurrido algun problema al tratar de insertar datos de alumno.";
                }
            }
            else
            {
                $model->getErrors();
            }
        }

        return $this->render("create", ["model"=>$model, "msg"=>$msg]);
    }

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

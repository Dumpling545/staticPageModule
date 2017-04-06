<?php

namespace app\modules\StaticPageModule\controllers;

use Yii;
use app\modules\StaticPageModule\models\category\{ErrorPage,GetCategoryModel, BaseStaticPage, SendCategoryModel, CreateCategoryModel};
use yii\web\Controller;
use app\modules\StaticPageModule\services\implementations\{CategoryService, StaticPageService};
use yii\web\NotFoundHttpException;
use yii\filters\{VerbFilter,AccessControl};
use \yii\data\Pagination;
use app\modules\StaticPageModule\helpers\PageSorter;
use \app\modules\StaticPageModule\configuration\Constants;
class CategoryController extends Controller{
    private $categoryService;
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'get-category' => ['GET'],
                    'sort-pages' => ['POST'],
                    'create-category' => ['GET', 'POST'],
                ]
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create-category'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }
    public function afterAction($action, $result)
    {
        Yii::$app->getUser()->setReturnUrl(Yii::$app->request->url);
        return parent::afterAction($action, $result);
    } 
    function __construct($id, $module, $config = array()) {
        parent::__construct($id, $module, $config);
        $this->categoryService = new CategoryService();
    }

    public function actionCreateCategory(){
        $status = 0;
        Yii::info(strval(Yii::$app->cache[Constants::CATEGORY_ID_KEY]));
        if(Yii::$app->user->identity->username == "admin")
            $status = Constants::ADMIN;
        else
            $status = Constants::AUTHORIZED_USER;
        $model = new CreateCategoryModel();
        $model->scenario = CreateCategoryModel::SCENARIO_CREATE_CATEGORY;
        if(Yii::$app->request->method == "GET"){
            return $this->render('addCategory', [
                'model' => $model,
                'categories' => $this->categoryService->getCategoryNames($status, false)
                ]);
        }else if ($model->load(Yii::$app->request->post())) {
            try{
            $id = $this->categoryService->createCategory($model);
            return $this->redirect('/page/category/'.$model->slug.'/1.html');
            } catch(\Exception $e){
                $error = new ErrorPage();
                Yii::$app->response->setStatusCode(Constants::BAD_REQUEST);
                $error->message= $e->getMessage();
                return $this->render('errorPage', ['model' => $error]);
            }
        }
    }
    public function actionGetCategory(string $slug, $sortBy = null)
    {
        $model = new SendCategoryModel();
        if ($slug != null) {
            try{
                $id = -1;
                if($slug!=='slug'){
                    $id = $this->categoryService->getCategoryId($slug);
                }
                $status = Constants::GUEST;
                    if(!Yii::$app->user->isGuest){
                        if(Yii::$app->user->identity->username == "admin")
                            $status = Constants::ADMIN;
                        else
                            $status = Constants::AUTHORIZED_USER;
                }
                $model = $this->categoryService->getCategory($id, $status);
                PageSorter::sort($model->pages, $sortBy);
                $pagination = new Pagination([
                    'defaultPageSize' => 10,
                    'totalCount' => count($model->pages),
                ]);
                $model->pages = array_slice($model->pages, $pagination->offset, $pagination->limit);
            } catch (\Exception $e){
                $error = new ErrorPage();
                Yii::$app->response->setStatusCode(Constants::BAD_REQUEST);
                $error->message= $e->getMessage();
                return $this->render('errorPage', ['model' => $error]);
            }
        }
        if($slug === 'slug'){
            return $this->render('getCategorySimple', [
            'model' => $model,
            'pagination' => $pagination
            ]);
        } else {
            return $this->render('getCategory', [
                'model' => $model,
                'pagination' => $pagination
            ]);
        }
    }
}

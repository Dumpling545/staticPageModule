<?php

namespace app\modules\StaticPageModule\controllers;

use Yii;
use app\modules\StaticPageModule\models\staticPage\{UpdatePageModel, CreatePageModel, ErrorPage};
use \app\modules\StaticPageModule\models\ratingItem\{CanRateModel};
use \app\modules\StaticPageModule\models\category\{CreateCategoryModel};
use app\modules\StaticPageModule\services\implementations\{StaticPageService, RatingItemService, CategoryService};
use yii\web\Controller;
use yii\filters\{VerbFilter,AccessControl};
use yii\data\Pagination;
use app\modules\StaticPageModule\configuration\Constants;
use app\modules\StaticPageModule\helpers\PageSorter;
use yii\data\ArrayDataProvider;

class StaticPageController extends Controller{
      /**
     * @inheritdoc
     */
    private  $pageService;
    private  $ratingService;
    private $categoryService;
            function __construct($id, $module, $config = array()) {
        parent::__construct($id, $module, $config);
        $this->pageService = new StaticPageService();
        $this->ratingService = new RatingItemService();
        $this->categoryService = new CategoryService();
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'get-page' => ['GET'],
                    'add-rating' => ['GET'],
                    'create-page' => ['GET', 'POST'],
                    'update-page' => ['GET', 'POST'],
                    'delete-page' => ['GET'],
                    'get-pages-by-tag' => ['GET'],
                    'admin-page' => ['GET'],
                    'null-rating' => ['GET']
                ]
            ], 
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create-page', 'update-page', 'delete-page', 'null-rating', 'admin-page'],
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
    public function actionGetPage(string $slug){
        if ($slug != null) {
           try{
               $status = Constants::GUEST;
                if(!Yii::$app->user->isGuest){
                    if(Yii::$app->user->identity->username == "admin")
                        $status = Constants::ADMIN;
                    else
                        $status = Constants::AUTHORIZED_USER;
                }
                $model = $this->pageService->getPageBySlug($slug, $status);
                $rating = $this->ratingService->getRatingItem($model->id, Yii::$app->request->userIP);
                $canRateModel = new CanRateModel();
                $canRateModel->canRate = 1;
                if($rating > 0){
                    $model->rating = $rating;
                    $canRateModel->canRate = 0;
                }
                return $this->render('getPage', [
                'model' => $model,
                'canRateModel' => $canRateModel
                ]);
           } catch (\Exception $e){
               $error = new ErrorPage();
               Yii::$app->response->setStatusCode(Constants::BAD_REQUEST);
               $error-> message = $e->getMessage();
                return $this->render('errorPage', ['model' => $error]);
           }
       }
    }
    public function actionNullRating(string $slug){
        try{
            $author = $this->pageService->getAuthorById($this->pageService->getIdBySlug($slug));
            if(Yii::$app->user->identity->username == "admin" || Yii::$app->user->identity->username == $author){
                $this->ratingService->deleteRatingItemsByPage($this->pageService->getIdBySlug($slug)); 
                $this->pageService->setRating($this->pageService->getIdBySlug($slug), 0);
            } else {
                throw new \Exception('You are not allowed to this action');
            }
           
        } catch (\Exception $ex) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            Yii::$app->response->setStatusCode(Constants::BAD_REQUEST);
            Yii::$app->response->isServerError = true;
            return ['code' => 400];
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['code' =>200];
    }
    
    public function actionAddRating(int $pageId, int $rating){
        if ($rating != null && $pageId != null) {
            try{
            $this->ratingService->addRatingItem($pageId, $rating, Yii::$app->request->userIP);
            $this->pageService->setRating($pageId, $this->ratingService->getAverageRatingOfPage($pageId));
            } catch (\Exception $e){
                Yii::$app->response->setStatusCode(Constants::BAD_REQUEST);
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                Yii::$app->response->isServerError = true;
                return ['code' => 400];
           }
        } 
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['code' =>200];
    }
    public function actionCreatePage(){
        $status = 0;
        if(Yii::$app->user->identity->username == "admin")
            $status = Constants::ADMIN;
        else
            $status = Constants::AUTHORIZED_USER;
        $pageModel = new CreatePageModel();
        $categoryModel = new CreateCategoryModel();
        $categoryModel->scenario = CreateCategoryModel::SCENARIO_CREATE_PAGE_AND_CATEGORY;
        if(Yii::$app->request->method == "GET"){
            return $this->render('createForm', [
                'model' => $pageModel,
                'categoryModel' => $categoryModel,
                'categories' => $this->categoryService->getCategoryNames($status, false)
                ]);
        }else if ($pageModel->load(Yii::$app->request->post())) {
            $id = 0;
            if($categoryModel->load(Yii::$app->request->post()) && $pageModel->categoryId == -1){
                if(!empty($categoryModel)){
                    $categoryModel->scenario = CreateCategoryModel::SCENARIO_CREATE_CATEGORY;
                    if($categoryModel->validate()){
                        $id = $this->pageService->createPageAndCategory($pageModel, $categoryModel, Yii::$app->user->identity->username);
                    } else {
                        Yii::$app->response->setStatusCode(Constants::BAD_REQUEST);
                        $error = new ErrorPage();
                        $error-> message = 'If you want to create model, you should fill all fields ';
                        return $this->render('errorPage', ['model' => $error]);
                    }
                }
            }else{
                try{
                    $id = $this->pageService->createPage($pageModel, Yii::$app->user->identity->username);
                } catch(\Exception $e){
                    Yii::$app->response->setStatusCode(Constants::BAD_REQUEST);
                    $error = new ErrorPage();
                    $error-> message = 'Input data is incorrect or entered slug already exists';
                    return $this->render('errorPage', ['model' => $error]);
                }
            }
            return $this->redirect('/page/'.$pageModel->slug.'.html');
        } else {
               Yii::$app->response->setStatusCode(Constants::BAD_REQUEST);
               $error = new ErrorPage();
               $error-> message = 'Something wrong!';
               return $this->render('errorPage', ['model' => $error]);
        }
    }
    public function actionUpdatePage(string $slug){
        try{
            $status = Constants::GUEST;
                if(!Yii::$app->user->isGuest){
                    if(Yii::$app->user->identity->username == "admin")
                        $status = Constants::ADMIN;
                    else
                        $status = Constants::AUTHORIZED_USER;
            }
            $getModel = $this->pageService->getPageBySlug($slug, $status);
        } catch (\Exception $e){
            $error = new ErrorPage();
            $error-> message = $e->getMessage();
             return $this->render('errorPage', ['model' => $error]);
        }
        if($getModel->author != Yii::$app->user->identity->username && $status != Constants::ADMIN){
            $error = new ErrorPage();
            Yii::$app->response->setStatusCode(Constants::BAD_REQUEST);
            $error-> message = "Action not allowed";
             return $this->render('errorPage', ['model' => $error]);
        }
        $updatePageModel = new UpdatePageModel();
        if(Yii::$app->request->method == "GET"){
            try{
                $updatePageModel->accessibilityStatus = $getModel->accessibilityStatus;
                $updatePageModel->slug = $getModel->slug;
                $updatePageModel->categoryId = $getModel->categoryId;
                $updatePageModel->description = $getModel->description;
                $updatePageModel->header = $getModel->header;
                $updatePageModel->id = $getModel->id;
                $updatePageModel->summary = $getModel->summary;
                $updatePageModel->tags = implode(" ", $getModel->tags);
                return $this->render('updateForm', [
                'model' => $updatePageModel,
                'categories' => $this->categoryService->getCategoryNames($status, false)
                ]);
            } catch (\Exception $e){
               $error = new ErrorPage();
               Yii::$app->response->setStatusCode(Constants::BAD_REQUEST);
               $error->message = $e->getMessage();
                return $this->render('errorPage', ['model' => $error]);
           }
        }else if($updatePageModel->load(Yii::$app->request->post())) {
            try{
            $this->pageService->updatePage($updatePageModel);
            return $this->redirect('/page/'.$this->pageService->getSlugById($updatePageModel->id).'.html');
            } catch (\Exception $e){
                Yii::$app->response->setStatusCode(Constants::BAD_REQUEST);
                $error = new ErrorPage();
                $error-> message = "Input data is incorrect or entered slug already exists";
                return $this->render('errorPage', ['model' => $error]);
            }
        }
    }
    public function  actionDeletePage(string $slug){
        if (!empty($slug)) {
            try{
                $author = $this->pageService->getAuthorById($this->pageService->getIdBySlug($slug));
            if(Yii::$app->user->identity->username == "admin" || Yii::$app->user->identity->username == $author){
                $this->pageService->deletePage($this->pageService->getIdBySlug($slug));
                return $this->goBack();
            } else {
                throw new \Exception('You are not allowed to this action');
            }
            } catch (\Exception $e){
                Yii::$app->response->setStatusCode(Constants::BAD_REQUEST);
                $error = new ErrorPage();
                $error-> message = $e->getMessage();
                return $this->render('errorPage', ['model' => $error]);
            }
        }
    }
    
    public function actionAdminPage(){
        if(Yii::$app->user->identity->username == "admin"){
            $dataProvider = new ArrayDataProvider([
                'allModels' => $this->pageService->getAllPagesQuery(null),
                'pagination' => [
                    'pageSize' => 10
                ]
            ]);
            return $this->render('adminPage', ['dataProvider' => $dataProvider]);
        }
    }
    public function actionUserPage(string $author){
         if(!empty($author)) {
            $dataProvider = new ArrayDataProvider([
                'allModels' => $this->pageService->getAllPagesQuery(Yii::$app->user->identity->username),
                'pagination' => [
                    'pageSize' => 10
                ]
            ]);
            return $this->render('adminPage', ['dataProvider' => $dataProvider]);
        }
    }
    public function actionGetPagesByTag(string $tag, $sortBy = null){
        
        if ($tag!=null) {
            try{
                $status = Constants::GUEST;
                if(!Yii::$app->user->isGuest){
                    if(Yii::$app->user->identity->username == "admin")
                        $status = Constants::ADMIN;
                    else
                        $status = Constants::AUTHORIZED_USER;
                }
                $model = $this->pageService->getPagesByTag($tag, $status);
                PageSorter::sort($model->pages, $sortBy);
            }catch (\Exception $e){
               $error = new ErrorPage();
               Yii::$app->response->setStatusCode(Constants::BAD_REQUEST);
               $error-> message = 'Tag not found or it is incorrect';
                return $this->render('errorPage', ['model' => $error]);
           }
            $pagination = new Pagination([
                'defaultPageSize' => 10,
                'totalCount' => count($model->pages)
            ]);
            $model->pages = array_slice($model->pages, $pagination->offset, $pagination->limit);
            return $this->render('getPagesByTag', [
            'model' => $model,
            'pagination' => $pagination
            ]);
        }
    }
}

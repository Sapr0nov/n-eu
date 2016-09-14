<?php

namespace bupy7\pages\controllers;

use Yii;
use bupy7\pages\models\Page;
use bupy7\pages\models\PageTranslate; // переводы
use bupy7\pages\models\PageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use bupy7\pages\Module;
use vova07\imperavi\actions\GetAction as ImperaviGetAction;
use vova07\imperavi\actions\UploadAction as ImperaviUploadAction;


class ManagerController extends Controller
{
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function actions()
    {
        $module = Yii::$app->getModule('pages');
        
        $actions = [];
        
        // add images that have already been uploaded
        if ($module->addImage) {
            $actions['images-get'] = [
                'class' => ImperaviGetAction::className(),
                'url' => Yii::getAlias($module->urlToImages),
                'path' => Yii::getAlias($module->pathToImages),
                'type' => ImperaviGetAction::TYPE_IMAGES,
            ];
        }
        // upload image
        if ($module->uploadImage) {
            $actions['image-upload'] = [
                'class' => ImperaviUploadAction::className(),
                'url' => Yii::getAlias($module->urlToImages),
                'path' => Yii::getAlias($module->pathToImages),
            ];
        }
        // add files that have already been uploaded
        if ($module->addFile) {
            $actions['files-get'] = [
                'class' => ImperaviGetAction::className(),
                'url' => Yii::getAlias($module->urlToFiles),
                'path' => Yii::getAlias($module->pathToFiles),
                'type' => ImperaviGetAction::TYPE_FILES,
            ];
        }
        // upload file
        if ($module->uploadFile) {
            $actions['file-upload'] = [
                'class' => ImperaviUploadAction::className(),
                'url' => Yii::getAlias($module->urlToFiles),
                'path' => Yii::getAlias($module->pathToFiles),
            ];
        }
        
        return $actions;
    }


    public function actionIndex()
    {
        $searchModel = new PageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionCreate()
    {
        return $this->actionUpdate(null);
    }

    public function actionUpdate($id = null)
    {
        if ($id === null) {
            $model = new Page;
        } else {
            $model = $this->findModel($id);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Module::t('SAVE_SUCCESS'));
            return $this->redirect(['update', 'id' => $model->id]);
        } 
        
        $module = Yii::$app->getModule('pages');
        
        return $this->render($id === null ? 'create' : 'update', [
            'model' => $model,
            'module' => $module,
        ]);
    }


    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete()) {
            Yii::$app->session->setFlash('success', Module::t('DELETE_SUCCESS'));
        }
        return $this->redirect(['index']);
    }


    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Module::t('PAGE_NOT_FOUND'));
    }
    
	
	
/* свое волие */
    public function actionTranslate()
    {
        return $this->actionUpdateTranslate(null);
    }
	
	public function actionUpdateTranslate($id = null)
    {
        if ($id === null) {
            $model = new PageTranslate;
        } else {
            $model = $this->findModel($id);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Module::t('SAVE_SUCCESS'));
            return $this->redirect(['update', 'id' => $model->id]);
        } 
        
        $module = Yii::$app->getModule('pages');
        
        return $this->render($id === null ? 'create2' : 'update2', [
            'model' => $model,
            'module' => $module,
        ]);
    }
}

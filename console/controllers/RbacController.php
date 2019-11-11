<?php

namespace console\controllers;

use common\models\User;
use common\rbac\EntityRule;
use kartik\select2\Select2Asset;
use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\rbac\Role;
use yii\validators\EmailValidator;
use yii\validators\ExistValidator;
use yii\validators\StringValidator;

class RbacController extends Controller
{
    public $defaultAction = 'init';

    public function actionInit()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            /* @var yii\rbac\DbManager $auth */
            $auth = Yii::$app->authManager;
            $assignments = [];
            foreach (User::find()->each() as $user) {
                $assignments[$user->id] = ArrayHelper::getColumn($auth->getAssignments($user->id), 'roleName');
            }
            $auth->removeAll();



            $entityRule = new EntityRule();
            $auth->add($entityRule);



            $backendCollectionList = $auth->createPermission('backend.collection.list');
            $backendCollectionList->description = 'Поиск коллекций';
            $backendCollectionList->ruleName = $entityRule->name;
            $auth->add($backendCollectionList);

            $backendCollectionImport = $auth->createPermission('backend.collection.import');
            $backendCollectionImport->description = 'Список коллекций';
            $backendCollectionImport->ruleName = $entityRule->name;
            $auth->add($backendCollectionImport);

            $backendCollectionIndex = $auth->createPermission('backend.collection.index');
            $backendCollectionIndex->description = 'Список коллекций';
            $backendCollectionIndex->ruleName = $entityRule->name;
            $auth->add($backendCollectionIndex);

            $backendCollectionView = $auth->createPermission('backend.collection.view');
            $backendCollectionView->description = 'Просмотр коллекций';
            $backendCollectionView->ruleName = $entityRule->name;
            $auth->add($backendCollectionView);

            $backendCollectionCreate = $auth->createPermission('backend.collection.create');
            $backendCollectionCreate->description = 'Создание коллекции';
            $backendCollectionCreate->ruleName = $entityRule->name;
            $auth->add($backendCollectionCreate);

            $backendCollectionUpdate = $auth->createPermission('backend.collection.update');
            $backendCollectionUpdate->description = 'Редактирование коллекции';
            $backendCollectionUpdate->ruleName = $entityRule->name;
            $auth->add($backendCollectionUpdate);

            $backendCollectionDelete = $auth->createPermission('backend.collection.delete');
            $backendCollectionDelete->description = 'Удаление коллекции';
            $backendCollectionDelete->ruleName = $entityRule->name;
            $auth->add($backendCollectionDelete);

            $backendCollectionLogIndex = $auth->createPermission('backend.collection.log.index');
            $backendCollectionLogIndex->description = 'Список изменений';
            $backendCollectionLogIndex->ruleName = $entityRule->name;
            $auth->add($backendCollectionLogIndex);

            $backendCollectionLogView = $auth->createPermission('backend.collection.log.view');
            $backendCollectionLogView->description = 'Просмотр изменений';
            $backendCollectionLogView->ruleName = $entityRule->name;
            $auth->add($backendCollectionLogView);

            $backendCollectionLogRestore = $auth->createPermission('backend.collection.log.restore');
            $backendCollectionLogRestore->description = 'Восстановление изменений';
            $backendCollectionLogRestore->ruleName = $entityRule->name;
            $auth->add($backendCollectionLogRestore);

            $backendManageCollection = $auth->createPermission('backend.collection');
            $backendManageCollection->description = 'Управление коллекциями';
            $auth->add($backendManageCollection);
            $auth->addChild($backendManageCollection, $backendCollectionList);
            $auth->addChild($backendManageCollection, $backendCollectionImport);
            $auth->addChild($backendManageCollection, $backendCollectionIndex);
            $auth->addChild($backendManageCollection, $backendCollectionView);
            $auth->addChild($backendManageCollection, $backendCollectionCreate);
            $auth->addChild($backendManageCollection, $backendCollectionUpdate);
            $auth->addChild($backendManageCollection, $backendCollectionDelete);
            $auth->addChild($backendManageCollection, $backendCollectionLogIndex);
            $auth->addChild($backendManageCollection, $backendCollectionLogView);
            $auth->addChild($backendManageCollection, $backendCollectionLogRestore);



            $backendFaqCategoryList = $auth->createPermission('backend.faq.category.list');
            $backendFaqCategoryList->description = 'Поиск категорий';
            $backendFaqCategoryList->ruleName = $entityRule->name;
            $auth->add($backendFaqCategoryList);

            $backendFaqCategoryIndex = $auth->createPermission('backend.faq.category.index');
            $backendFaqCategoryIndex->description = 'Список категорий';
            $backendFaqCategoryIndex->ruleName = $entityRule->name;
            $auth->add($backendFaqCategoryIndex);

            $backendFaqCategoryView = $auth->createPermission('backend.faq.category.view');
            $backendFaqCategoryView->description = 'Просмотр категории';
            $backendFaqCategoryView->ruleName = $entityRule->name;
            $auth->add($backendFaqCategoryView);

            $backendFaqCategoryCreate = $auth->createPermission('backend.faq.category.create');
            $backendFaqCategoryCreate->description = 'Создание категории';
            $backendFaqCategoryCreate->ruleName = $entityRule->name;
            $auth->add($backendFaqCategoryCreate);

            $backendFaqCategoryUpdate = $auth->createPermission('backend.faq.category.update');
            $backendFaqCategoryUpdate->description = 'Редактирование категории';
            $backendFaqCategoryUpdate->ruleName = $entityRule->name;
            $auth->add($backendFaqCategoryUpdate);

            $backendFaqCategoryDelete = $auth->createPermission('backend.faq.category.delete');
            $backendFaqCategoryDelete->description = 'Удаление категории';
            $backendFaqCategoryDelete->ruleName = $entityRule->name;
            $auth->add($backendFaqCategoryDelete);

            $backendFaqCategoryLogIndex = $auth->createPermission('backend.faq.category.log.index');
            $backendFaqCategoryLogIndex->description = 'Список изменений';
            $backendFaqCategoryLogIndex->ruleName = $entityRule->name;
            $auth->add($backendFaqCategoryLogIndex);

            $backendFaqCategoryLogView = $auth->createPermission('backend.faq.category.log.view');
            $backendFaqCategoryLogView->description = 'Просмотр изменений';
            $backendFaqCategoryLogView->ruleName = $entityRule->name;
            $auth->add($backendFaqCategoryLogView);

            $backendFaqCategoryLogRestore = $auth->createPermission('backend.faq.category.log.restore');
            $backendFaqCategoryLogRestore->description = 'Восстановление изменений';
            $backendFaqCategoryLogRestore->ruleName = $entityRule->name;
            $auth->add($backendFaqCategoryLogRestore);

            $backendManageFaqCategory = $auth->createPermission('backend.faq.category');
            $backendManageFaqCategory->description = 'Управление категориями';
            $auth->add($backendManageFaqCategory);
            $auth->addChild($backendManageFaqCategory, $backendFaqCategoryList);
            $auth->addChild($backendManageFaqCategory, $backendFaqCategoryIndex);
            $auth->addChild($backendManageFaqCategory, $backendFaqCategoryView);
            $auth->addChild($backendManageFaqCategory, $backendFaqCategoryCreate);
            $auth->addChild($backendManageFaqCategory, $backendFaqCategoryUpdate);
            $auth->addChild($backendManageFaqCategory, $backendFaqCategoryDelete);
            $auth->addChild($backendManageFaqCategory, $backendFaqCategoryLogIndex);
            $auth->addChild($backendManageFaqCategory, $backendFaqCategoryLogView);
            $auth->addChild($backendManageFaqCategory, $backendFaqCategoryLogRestore);



            $backendFaqIndex = $auth->createPermission('backend.faq.index');
            $backendFaqIndex->description = 'Список вопросов';
            $backendFaqIndex->ruleName = $entityRule->name;
            $auth->add($backendFaqIndex);

            $backendFaqView = $auth->createPermission('backend.faq.view');
            $backendFaqView->description = 'Просмотр вопроса';
            $backendFaqView->ruleName = $entityRule->name;
            $auth->add($backendFaqView);

            $backendFaqCreate = $auth->createPermission('backend.faq.create');
            $backendFaqCreate->description = 'Создание вопроса';
            $backendFaqCreate->ruleName = $entityRule->name;
            $auth->add($backendFaqCreate);

            $backendFaqUpdate = $auth->createPermission('backend.faq.update');
            $backendFaqUpdate->description = 'Редактирование вопроса';
            $backendFaqUpdate->ruleName = $entityRule->name;
            $auth->add($backendFaqUpdate);

            $backendFaqDelete = $auth->createPermission('backend.faq.delete');
            $backendFaqDelete->description = 'Удаление вопроса';
            $backendFaqDelete->ruleName = $entityRule->name;
            $auth->add($backendFaqDelete);

            $backendFaqLogIndex = $auth->createPermission('backend.faq.log.index');
            $backendFaqLogIndex->description = 'Список изменений';
            $backendFaqLogIndex->ruleName = $entityRule->name;
            $auth->add($backendFaqLogIndex);

            $backendFaqLogView = $auth->createPermission('backend.faq.log.view');
            $backendFaqLogView->description = 'Просмотр изменений';
            $backendFaqLogView->ruleName = $entityRule->name;
            $auth->add($backendFaqLogView);

            $backendFaqLogRestore = $auth->createPermission('backend.faq.log.restore');
            $backendFaqLogRestore->description = 'Восстановление изменений';
            $backendFaqLogRestore->ruleName = $entityRule->name;
            $auth->add($backendFaqLogRestore);

            $backendManageFaq = $auth->createPermission('backend.faq');
            $backendManageFaq->description = 'Управление вопросами';
            $auth->add($backendManageFaq);
            $auth->addChild($backendManageFaq, $backendFaqIndex);
            $auth->addChild($backendManageFaq, $backendFaqView);
            $auth->addChild($backendManageFaq, $backendFaqCreate);
            $auth->addChild($backendManageFaq, $backendFaqUpdate);
            $auth->addChild($backendManageFaq, $backendFaqDelete);
            $auth->addChild($backendManageFaq, $backendFaqLogIndex);
            $auth->addChild($backendManageFaq, $backendFaqLogView);
            $auth->addChild($backendManageFaq, $backendFaqLogRestore);



            $backendFormIndex = $auth->createPermission('backend.form.index');
            $backendFormIndex->description = 'Список форм';
            $backendFormIndex->ruleName = $entityRule->name;
            $auth->add($backendFormIndex);

            $backendFormView = $auth->createPermission('backend.form.view');
            $backendFormView->description = 'Просмотр формы';
            $backendFormView->ruleName = $entityRule->name;
            $auth->add($backendFormView);

            $backendFormCreate = $auth->createPermission('backend.form.create');
            $backendFormCreate->description = 'Создание формы';
            $backendFormCreate->ruleName = $entityRule->name;
            $auth->add($backendFormCreate);

            $backendFormUpdate = $auth->createPermission('backend.form.update');
            $backendFormUpdate->description = 'Редактирование формы';
            $backendFormUpdate->ruleName = $entityRule->name;
            $auth->add($backendFormUpdate);

            $backendFormDelete = $auth->createPermission('backend.form.delete');
            $backendFormDelete->description = 'Удаление формы';
            $backendFormDelete->ruleName = $entityRule->name;
            $auth->add($backendFormDelete);

            $backendFormLogIndex = $auth->createPermission('backend.form.log.index');
            $backendFormLogIndex->description = 'Список изменений';
            $backendFormLogIndex->ruleName = $entityRule->name;
            $auth->add($backendFormLogIndex);

            $backendFormLogView = $auth->createPermission('backend.form.log.view');
            $backendFormLogView->description = 'Просмотр изменений';
            $backendFormLogView->ruleName = $entityRule->name;
            $auth->add($backendFormLogView);

            $backendFormLogRestore = $auth->createPermission('backend.form.log.restore');
            $backendFormLogRestore->description = 'Восстановление изменений';
            $backendFormLogRestore->ruleName = $entityRule->name;
            $auth->add($backendFormLogRestore);

            $backendManageForm = $auth->createPermission('backend.form');
            $backendManageForm->description = 'Управление формами';
            $auth->add($backendManageForm);
            $auth->addChild($backendManageForm, $backendFormIndex);
            $auth->addChild($backendManageForm, $backendFormView);
            $auth->addChild($backendManageForm, $backendFormCreate);
            $auth->addChild($backendManageForm, $backendFormUpdate);
            $auth->addChild($backendManageForm, $backendFormDelete);
            $auth->addChild($backendManageForm, $backendFormLogIndex);
            $auth->addChild($backendManageForm, $backendFormLogView);
            $auth->addChild($backendManageForm, $backendFormLogRestore);



            $backendGalleryIndex = $auth->createPermission('backend.gallery.index');
            $backendGalleryIndex->description = 'Список галерей';
            $backendGalleryIndex->ruleName = $entityRule->name;
            $auth->add($backendGalleryIndex);

            $backendGalleryView = $auth->createPermission('backend.gallery.view');
            $backendGalleryView->description = 'Просмотр галереи';
            $backendGalleryView->ruleName = $entityRule->name;
            $auth->add($backendGalleryView);

            $backendGalleryCreate = $auth->createPermission('backend.gallery.create');
            $backendGalleryCreate->description = 'Создание галереи';
            $backendGalleryCreate->ruleName = $entityRule->name;
            $auth->add($backendGalleryCreate);

            $backendGalleryUpdate = $auth->createPermission('backend.gallery.update');
            $backendGalleryUpdate->description = 'Редактирование галереи';
            $backendGalleryUpdate->ruleName = $entityRule->name;
            $auth->add($backendGalleryUpdate);

            $backendGalleryDelete = $auth->createPermission('backend.gallery.delete');
            $backendGalleryDelete->description = 'Удаление галереи';
            $backendGalleryDelete->ruleName = $entityRule->name;
            $auth->add($backendGalleryDelete);

            $backendGalleryLogIndex = $auth->createPermission('backend.gallery.log.index');
            $backendGalleryLogIndex->description = 'Список изменений';
            $backendGalleryLogIndex->ruleName = $entityRule->name;
            $auth->add($backendGalleryLogIndex);

            $backendGalleryLogView = $auth->createPermission('backend.gallery.log.view');
            $backendGalleryLogView->description = 'Просмотр изменений';
            $backendGalleryLogView->ruleName = $entityRule->name;
            $auth->add($backendGalleryLogView);

            $backendGalleryLogRestore = $auth->createPermission('backend.gallery.log.restore');
            $backendGalleryLogRestore->description = 'Восстановление изменений';
            $backendGalleryLogRestore->ruleName = $entityRule->name;
            $auth->add($backendGalleryLogRestore);

            $backendManageGallery = $auth->createPermission('backend.gallery');
            $backendManageGallery->description = 'Управление галереями';
            $auth->add($backendManageGallery);
            $auth->addChild($backendManageGallery, $backendGalleryIndex);
            $auth->addChild($backendManageGallery, $backendGalleryView);
            $auth->addChild($backendManageGallery, $backendGalleryCreate);
            $auth->addChild($backendManageGallery, $backendGalleryUpdate);
            $auth->addChild($backendManageGallery, $backendGalleryDelete);
            $auth->addChild($backendManageGallery, $backendGalleryLogIndex);
            $auth->addChild($backendManageGallery, $backendGalleryLogView);
            $auth->addChild($backendManageGallery, $backendGalleryLogRestore);



            $backendMenuIndex = $auth->createPermission('backend.menu.index');
            $backendMenuIndex->description = 'Список меню';
            $backendMenuIndex->ruleName = $entityRule->name;
            $auth->add($backendMenuIndex);

            $backendMenuView = $auth->createPermission('backend.menu.view');
            $backendMenuView->description = 'Просмотр меню';
            $backendMenuView->ruleName = $entityRule->name;
            $auth->add($backendMenuView);

            $backendMenuCreate = $auth->createPermission('backend.menu.create');
            $backendMenuCreate->description = 'Создание меню';
            $backendMenuCreate->ruleName = $entityRule->name;
            $auth->add($backendMenuCreate);

            $backendMenuUpdate = $auth->createPermission('backend.menu.update');
            $backendMenuUpdate->description = 'Редактирование меню';
            $backendMenuUpdate->ruleName = $entityRule->name;
            $auth->add($backendMenuUpdate);

            $backendMenuDelete = $auth->createPermission('backend.menu.delete');
            $backendMenuDelete->description = 'Удаление меню';
            $backendMenuDelete->ruleName = $entityRule->name;
            $auth->add($backendMenuDelete);

            $backendMenuLogIndex = $auth->createPermission('backend.menu.log.index');
            $backendMenuLogIndex->description = 'Список изменений';
            $backendMenuLogIndex->ruleName = $entityRule->name;
            $auth->add($backendMenuLogIndex);

            $backendMenuLogView = $auth->createPermission('backend.menu.log.view');
            $backendMenuLogView->description = 'Просмотр изменений';
            $backendMenuLogView->ruleName = $entityRule->name;
            $auth->add($backendMenuLogView);

            $backendMenuLogRestore = $auth->createPermission('backend.menu.log.restore');
            $backendMenuLogRestore->description = 'Восстановление изменений';
            $backendMenuLogRestore->ruleName = $entityRule->name;
            $auth->add($backendMenuLogRestore);

            $backendManageMenu = $auth->createPermission('backend.menu');
            $backendManageMenu->description = 'Управление меню';
            $auth->add($backendManageMenu);
            $auth->addChild($backendManageMenu, $backendMenuIndex);
            $auth->addChild($backendManageMenu, $backendMenuView);
            $auth->addChild($backendManageMenu, $backendMenuCreate);
            $auth->addChild($backendManageMenu, $backendMenuUpdate);
            $auth->addChild($backendManageMenu, $backendMenuDelete);
            $auth->addChild($backendManageMenu, $backendMenuLogIndex);
            $auth->addChild($backendManageMenu, $backendMenuLogView);
            $auth->addChild($backendManageMenu, $backendMenuLogRestore);



            $backendNewsIndex = $auth->createPermission('backend.news.index');
            $backendNewsIndex->description = 'Список новостей';
            $backendNewsIndex->ruleName = $entityRule->name;
            $auth->add($backendNewsIndex);

            $backendNewsView = $auth->createPermission('backend.news.view');
            $backendNewsView->description = 'Просмотр новости';
            $backendNewsView->ruleName = $entityRule->name;
            $auth->add($backendNewsView);

            $backendNewsCreate = $auth->createPermission('backend.news.create');
            $backendNewsCreate->description = 'Создание новости';
            $backendNewsCreate->ruleName = $entityRule->name;
            $auth->add($backendNewsCreate);

            $backendNewsUpdate = $auth->createPermission('backend.news.update');
            $backendNewsUpdate->description = 'Редактирование новости';
            $backendNewsUpdate->ruleName = $entityRule->name;
            $auth->add($backendNewsUpdate);

            $backendNewsDelete = $auth->createPermission('backend.news.delete');
            $backendNewsDelete->description = 'Удаление новости';
            $backendNewsDelete->ruleName = $entityRule->name;
            $auth->add($backendNewsDelete);

            $backendNewsLogIndex = $auth->createPermission('backend.news.log.index');
            $backendNewsLogIndex->description = 'Список изменений';
            $backendNewsLogIndex->ruleName = $entityRule->name;
            $auth->add($backendNewsLogIndex);

            $backendNewsLogView = $auth->createPermission('backend.news.log.view');
            $backendNewsLogView->description = 'Просмотр изменений';
            $backendNewsLogView->ruleName = $entityRule->name;
            $auth->add($backendNewsLogView);

            $backendNewsLogRestore = $auth->createPermission('backend.news.log.restore');
            $backendNewsLogRestore->description = 'Восстановление изменений';
            $backendNewsLogRestore->ruleName = $entityRule->name;
            $auth->add($backendNewsLogRestore);

            $backendManageNews = $auth->createPermission('backend.news');
            $backendManageNews->description = 'Управление новостями';
            $auth->add($backendManageNews);
            $auth->addChild($backendManageNews, $backendNewsIndex);
            $auth->addChild($backendManageNews, $backendNewsView);
            $auth->addChild($backendManageNews, $backendNewsCreate);
            $auth->addChild($backendManageNews, $backendNewsUpdate);
            $auth->addChild($backendManageNews, $backendNewsDelete);
            $auth->addChild($backendManageNews, $backendNewsLogIndex);
            $auth->addChild($backendManageNews, $backendNewsLogView);
            $auth->addChild($backendManageNews, $backendNewsLogRestore);



            $backendOpendataIndex = $auth->createPermission('backend.opendata.index');
            $backendOpendataIndex->description = 'Список наборов';
            $backendOpendataIndex->ruleName = $entityRule->name;
            $auth->add($backendOpendataIndex);

            $backendOpendataView = $auth->createPermission('backend.opendata.view');
            $backendOpendataView->description = 'Просмотр набора';
            $backendOpendataView->ruleName = $entityRule->name;
            $auth->add($backendOpendataView);

            $backendOpendataCreate = $auth->createPermission('backend.opendata.create');
            $backendOpendataCreate->description = 'Создание набора';
            $backendOpendataCreate->ruleName = $entityRule->name;
            $auth->add($backendOpendataCreate);

            $backendOpendataUpdate = $auth->createPermission('backend.opendata.update');
            $backendOpendataUpdate->description = 'Редактирование набора';
            $backendOpendataUpdate->ruleName = $entityRule->name;
            $auth->add($backendOpendataUpdate);

            $backendOpendataDelete = $auth->createPermission('backend.opendata.delete');
            $backendOpendataDelete->description = 'Удаление набора';
            $backendOpendataDelete->ruleName = $entityRule->name;
            $auth->add($backendOpendataDelete);

            $backendOpendataLogIndex = $auth->createPermission('backend.opendata.log.index');
            $backendOpendataLogIndex->description = 'Список изменений';
            $backendOpendataLogIndex->ruleName = $entityRule->name;
            $auth->add($backendOpendataLogIndex);

            $backendOpendataLogView = $auth->createPermission('backend.opendata.log.view');
            $backendOpendataLogView->description = 'Просмотр изменений';
            $backendOpendataLogView->ruleName = $entityRule->name;
            $auth->add($backendOpendataLogView);

            $backendOpendataLogRestore = $auth->createPermission('backend.opendata.log.restore');
            $backendOpendataLogRestore->description = 'Восстановление изменений';
            $backendOpendataLogRestore->ruleName = $entityRule->name;
            $auth->add($backendOpendataLogRestore);

            $backendManageOpendata = $auth->createPermission('backend.opendata');
            $backendManageOpendata->description = 'Управление открытыми данными';
            $auth->add($backendManageOpendata);
            $auth->addChild($backendManageOpendata, $backendOpendataIndex);
            $auth->addChild($backendManageOpendata, $backendOpendataView);
            $auth->addChild($backendManageOpendata, $backendOpendataCreate);
            $auth->addChild($backendManageOpendata, $backendOpendataUpdate);
            $auth->addChild($backendManageOpendata, $backendOpendataDelete);
            $auth->addChild($backendManageOpendata, $backendOpendataLogIndex);
            $auth->addChild($backendManageOpendata, $backendOpendataLogView);
            $auth->addChild($backendManageOpendata, $backendOpendataLogRestore);



            $backendPageLayoutIndex = $auth->createPermission('backend.page.layout');
            $backendPageLayoutIndex->description = 'Редактирование шаблона раздела';
            $backendPageLayoutIndex->ruleName = $entityRule->name;
            $auth->add($backendPageLayoutIndex);

            $backendPageIndex = $auth->createPermission('backend.page.index');
            $backendPageIndex->description = 'Список разделов';
            $backendPageIndex->ruleName = $entityRule->name;
            $auth->add($backendPageIndex);

            $backendPageView = $auth->createPermission('backend.page.view');
            $backendPageView->description = 'Просмотр раздела';
            $backendPageView->ruleName = $entityRule->name;
            $auth->add($backendPageView);

            $backendPageCreate = $auth->createPermission('backend.page.create');
            $backendPageCreate->description = 'Создание раздела';
            $backendPageCreate->ruleName = $entityRule->name;
            $auth->add($backendPageCreate);

            $backendPageUpdate = $auth->createPermission('backend.page.update');
            $backendPageUpdate->description = 'Редактирование раздела';
            $backendPageUpdate->ruleName = $entityRule->name;
            $auth->add($backendPageUpdate);

            $backendPageDelete = $auth->createPermission('backend.page.delete');
            $backendPageDelete->description = 'Удаление раздела';
            $backendPageDelete->ruleName = $entityRule->name;
            $auth->add($backendPageDelete);

            $backendPageLogIndex = $auth->createPermission('backend.page.log.index');
            $backendPageLogIndex->description = 'Список изменений';
            $backendPageLogIndex->ruleName = $entityRule->name;
            $auth->add($backendPageLogIndex);

            $backendPageLogView = $auth->createPermission('backend.page.log.view');
            $backendPageLogView->description = 'Просмотр изменений';
            $backendPageLogView->ruleName = $entityRule->name;
            $auth->add($backendPageLogView);

            $backendPageLogRestore = $auth->createPermission('backend.page.log.restore');
            $backendPageLogRestore->description = 'Восстановление изменений';
            $backendPageLogRestore->ruleName = $entityRule->name;
            $auth->add($backendPageLogRestore);

            $backendManagePage = $auth->createPermission('backend.page');
            $backendManagePage->description = 'Управление разделами';
            $auth->add($backendManagePage);
            $auth->addChild($backendManagePage, $backendPageLayoutIndex);
            $auth->addChild($backendManagePage, $backendPageIndex);
            $auth->addChild($backendManagePage, $backendPageView);
            $auth->addChild($backendManagePage, $backendPageCreate);
            $auth->addChild($backendManagePage, $backendPageUpdate);
            $auth->addChild($backendManagePage, $backendPageDelete);
            $auth->addChild($backendManagePage, $backendPageLogIndex);
            $auth->addChild($backendManagePage, $backendPageLogView);
            $auth->addChild($backendManagePage, $backendPageLogRestore);



            $backendPollIndex = $auth->createPermission('backend.poll.index');
            $backendPollIndex->description = 'Список опросов';
            $backendPollIndex->ruleName = $entityRule->name;
            $auth->add($backendPollIndex);

            $backendPollView = $auth->createPermission('backend.poll.view');
            $backendPollView->description = 'Просмотр опроса';
            $backendPollView->ruleName = $entityRule->name;
            $auth->add($backendPollView);

            $backendPollCreate = $auth->createPermission('backend.poll.create');
            $backendPollCreate->description = 'Создание опроса';
            $backendPollCreate->ruleName = $entityRule->name;
            $auth->add($backendPollCreate);

            $backendPollQuestionCreate = $auth->createPermission('backend.poll.question.create');
            $backendPollQuestionCreate->description = 'Создание вопроса';
            $backendPollQuestionCreate->ruleName = $entityRule->name;
            $auth->add($backendPollQuestionCreate);

            $backendPollUpdate = $auth->createPermission('backend.poll.update');
            $backendPollUpdate->description = 'Редактирование опроса';
            $backendPollUpdate->ruleName = $entityRule->name;
            $auth->add($backendPollUpdate);

            $backendPollQuestionUpdate = $auth->createPermission('backend.poll.question.update');
            $backendPollQuestionUpdate->description = 'Редактирование вопроса';
            $backendPollQuestionUpdate->ruleName = $entityRule->name;
            $auth->add($backendPollQuestionUpdate);

            $backendPollDelete = $auth->createPermission('backend.poll.delete');
            $backendPollDelete->description = 'Удаление опроса';
            $backendPollDelete->ruleName = $entityRule->name;
            $auth->add($backendPollDelete);

            $backendPollQuestionDelete = $auth->createPermission('backend.poll.question.delete');
            $backendPollQuestionDelete->description = 'Удаление вопроса';
            $backendPollQuestionDelete->ruleName = $entityRule->name;
            $auth->add($backendPollQuestionDelete);

            $backendPollLogIndex = $auth->createPermission('backend.poll.log.index');
            $backendPollLogIndex->description = 'Список изменений';
            $backendPollLogIndex->ruleName = $entityRule->name;
            $auth->add($backendPollLogIndex);

            $backendPollLogView = $auth->createPermission('backend.poll.log.view');
            $backendPollLogView->description = 'Просмотр изменений';
            $backendPollLogView->ruleName = $entityRule->name;
            $auth->add($backendPollLogView);

            $backendPollLogRestore = $auth->createPermission('backend.poll.log.restore');
            $backendPollLogRestore->description = 'Восстановление изменений';
            $backendPollLogRestore->ruleName = $entityRule->name;
            $auth->add($backendPollLogRestore);

            $backendManagePoll = $auth->createPermission('backend.poll');
            $backendManagePoll->description = 'Управление опроса';
            $auth->add($backendManagePoll);
            $auth->addChild($backendManagePoll, $backendPollIndex);
            $auth->addChild($backendManagePoll, $backendPollView);
            $auth->addChild($backendManagePoll, $backendPollCreate);
            $auth->addChild($backendManagePoll, $backendPollQuestionCreate);
            $auth->addChild($backendManagePoll, $backendPollUpdate);
            $auth->addChild($backendManagePoll, $backendPollQuestionUpdate);
            $auth->addChild($backendManagePoll, $backendPollDelete);
            $auth->addChild($backendManagePoll, $backendPollQuestionDelete);
            $auth->addChild($backendManagePoll, $backendPollLogIndex);
            $auth->addChild($backendManagePoll, $backendPollLogView);
            $auth->addChild($backendManagePoll, $backendPollLogRestore);



            $backendUserList = $auth->createPermission('backend.user.list');
            $backendUserList->description = 'Поиск пользователей';
            $backendUserList->ruleName = $entityRule->name;
            $auth->add($backendUserList);

            $backendUserIndex = $auth->createPermission('backend.user.index');
            $backendUserIndex->description = 'Список пользователей';
            $backendUserIndex->ruleName = $entityRule->name;
            $auth->add($backendUserIndex);

            $backendUserView = $auth->createPermission('backend.user.view');
            $backendUserView->description = 'Просмотр пользователя';
            $backendUserView->ruleName = $entityRule->name;
            $auth->add($backendUserView);

            $backendUserCreate = $auth->createPermission('backend.user.create');
            $backendUserCreate->description = 'Создание пользователя';
            $backendUserCreate->ruleName = $entityRule->name;
            $auth->add($backendUserCreate);

            $backendUserUpdate = $auth->createPermission('backend.user.update');
            $backendUserUpdate->description = 'Редактирование пользователя';
            $backendUserUpdate->ruleName = $entityRule->name;
            $auth->add($backendUserUpdate);

            $backendUserDelete = $auth->createPermission('backend.user.delete');
            $backendUserDelete->description = 'Удаление пользователя';
            $backendUserDelete->ruleName = $entityRule->name;
            $auth->add($backendUserDelete);

            $backendUserLogIndex = $auth->createPermission('backend.user.log.index');
            $backendUserLogIndex->description = 'Список пользователей';
            $backendUserLogIndex->ruleName = $entityRule->name;
            $auth->add($backendUserLogIndex);

            $backendUserLogView = $auth->createPermission('backend.user.log.view');
            $backendUserLogView->description = 'Просмотр изменений';
            $backendUserLogView->ruleName = $entityRule->name;
            $auth->add($backendUserLogView);

            $backendUserLogRestore = $auth->createPermission('backend.user.log.restore');
            $backendUserLogRestore->description = 'Восстановление изменений';
            $backendUserLogRestore->ruleName = $entityRule->name;
            $auth->add($backendUserLogRestore);

            $backendManageUser = $auth->createPermission('backend.user');
            $backendManageUser->description = 'Управление разделами';
            $auth->add($backendManageUser);
            $auth->addChild($backendManageUser, $backendUserList);
            $auth->addChild($backendManageUser, $backendUserIndex);
            $auth->addChild($backendManageUser, $backendUserView);
            $auth->addChild($backendManageUser, $backendUserCreate);
            $auth->addChild($backendManageUser, $backendUserUpdate);
            $auth->addChild($backendManageUser, $backendUserDelete);
            $auth->addChild($backendManageUser, $backendUserLogIndex);
            $auth->addChild($backendManageUser, $backendUserLogView);
            $auth->addChild($backendManageUser, $backendUserLogRestore);



            $backendVarsIndex = $auth->createPermission('backend.vars.index');
            $backendVarsIndex->description = 'Список переменных';
            $backendVarsIndex->ruleName = $entityRule->name;
            $auth->add($backendVarsIndex);

            $backendVarsView = $auth->createPermission('backend.vars.view');
            $backendVarsView->description = 'Просмотр переменной';
            $backendVarsView->ruleName = $entityRule->name;
            $auth->add($backendVarsView);

            $backendVarsCreate = $auth->createPermission('backend.vars.create');
            $backendVarsCreate->description = 'Создание переменной';
            $backendVarsCreate->ruleName = $entityRule->name;
            $auth->add($backendVarsCreate);

            $backendVarsUpdate = $auth->createPermission('backend.vars.update');
            $backendVarsUpdate->description = 'Редактирование переменной';
            $backendVarsUpdate->ruleName = $entityRule->name;
            $auth->add($backendVarsUpdate);

            $backendVarsDelete = $auth->createPermission('backend.vars.delete');
            $backendVarsDelete->description = 'Удаление переменной';
            $backendVarsDelete->ruleName = $entityRule->name;
            $auth->add($backendVarsDelete);

            $backendVarsLogIndex = $auth->createPermission('backend.vars.log.index');
            $backendVarsLogIndex->description = 'Список переменных';
            $backendVarsLogIndex->ruleName = $entityRule->name;
            $auth->add($backendVarsLogIndex);

            $backendVarsLogView = $auth->createPermission('backend.vars.log.view');
            $backendVarsLogView->description = 'Просмотр изменений';
            $backendVarsLogView->ruleName = $entityRule->name;
            $auth->add($backendVarsLogView);

            $backendVarsLogRestore = $auth->createPermission('backend.vars.log.restore');
            $backendVarsLogRestore->description = 'Восстановление изменений';
            $backendVarsLogRestore->ruleName = $entityRule->name;
            $auth->add($backendVarsLogRestore);

            $backendManageVars = $auth->createPermission('backend.vars');
            $backendManageVars->description = 'Управление переменными';
            $auth->add($backendManageVars);
            $auth->addChild($backendManageVars, $backendVarsIndex);
            $auth->addChild($backendManageVars, $backendVarsView);
            $auth->addChild($backendManageVars, $backendVarsCreate);
            $auth->addChild($backendManageVars, $backendVarsUpdate);
            $auth->addChild($backendManageVars, $backendVarsDelete);
            $auth->addChild($backendManageVars, $backendVarsLogIndex);
            $auth->addChild($backendManageVars, $backendVarsLogView);
            $auth->addChild($backendManageVars, $backendVarsLogRestore);



            $backendManage = $auth->createPermission('backend');
            $backendManage->description = 'Административная часть';
            $auth->add($backendManage);



            $user = $auth->createRole('user');
            $user->description = 'Пользователь';
            $auth->add($user);



            $backendCollection = $auth->createRole('admin.collection');
            $backendCollection->description = 'Редактор списков';
            $auth->add($backendCollection);
            $auth->addChild($backendCollection, $backendManage);
            $auth->addChild($backendCollection, $backendManageCollection);



            $backendFaq = $auth->createRole('admin.faq');
            $backendFaq->description = 'Редактор вопросов';
            $auth->add($backendFaq);
            $auth->addChild($backendFaq, $backendManage);
            $auth->addChild($backendFaq, $backendManageFaqCategory);
            $auth->addChild($backendFaq, $backendManageFaq);



            $backendForm = $auth->createRole('admin.form');
            $backendForm->description = 'Редактор форм';
            $auth->add($backendForm);
            $auth->addChild($backendForm, $backendManage);
            $auth->addChild($backendForm, $backendManageForm);



            $backendGallery = $auth->createRole('admin.gallery');
            $backendGallery->description = 'Редактор галерей';
            $auth->add($backendGallery);
            $auth->addChild($backendGallery, $backendManage);
            $auth->addChild($backendGallery, $backendManageGallery);



            $backendMenu = $auth->createRole('admin.menu');
            $backendMenu->description = 'Редактор меню';
            $auth->add($backendMenu);
            $auth->addChild($backendMenu, $backendManage);
            $auth->addChild($backendMenu, $backendManageMenu);



            $backendNews = $auth->createRole('admin.news');
            $backendNews->description = 'Редактор новостей';
            $auth->add($backendNews);
            $auth->addChild($backendNews, $backendManage);
            $auth->addChild($backendNews, $backendManageNews);



            $backendOpendata = $auth->createRole('admin.opendata');
            $backendOpendata->description = 'Редактор открытых данных';
            $auth->add($backendOpendata);
            $auth->addChild($backendOpendata, $backendManage);
            $auth->addChild($backendOpendata, $backendManageOpendata);



            $backendPage = $auth->createRole('admin.page');
            $backendPage->description = 'Редактор разделов';
            $auth->add($backendPage);
            $auth->addChild($backendPage, $backendManage);
            $auth->addChild($backendPage, $backendManagePage);



            $backendPoll = $auth->createRole('admin.poll');
            $backendPoll->description = 'Редактор опросов';
            $auth->add($backendPoll);
            $auth->addChild($backendPoll, $backendManage);
            $auth->addChild($backendPoll, $backendManagePoll);



            $backendUser = $auth->createRole('admin.user');
            $backendUser->description = 'Редактор пользователей';
            $auth->add($backendUser);
            $auth->addChild($backendUser, $backendManage);
            $auth->addChild($backendUser, $backendManageUser);



            $backendVars = $auth->createRole('admin.vars');
            $backendVars->description = 'Редактор переменных';
            $auth->add($backendVars);
            $auth->addChild($backendVars, $backendManage);
            $auth->addChild($backendVars, $backendManageVars);



            $admin = $auth->createRole('admin');
            $admin->description = 'Администратор';
            $auth->add($admin);
            $auth->addChild($admin, $backendCollection);
            $auth->addChild($admin, $backendFaq);
            $auth->addChild($admin, $backendForm);
            $auth->addChild($admin, $backendGallery);
            $auth->addChild($admin, $backendMenu);
            $auth->addChild($admin, $backendNews);
            $auth->addChild($admin, $backendOpendata);
            $auth->addChild($admin, $backendPage);
            $auth->addChild($admin, $backendPoll);
            $auth->addChild($admin, $backendUser);
            $auth->addChild($admin, $backendVars);



            $root = $auth->createRole('root');
            $root->description = 'Суперпользователь';
            $auth->add($root);
            $auth->addChild($root, $user);
            $auth->addChild($root, $admin);



            foreach ($assignments as $user_id => $roleNames) {
                foreach ($roleNames as $roleName) {
                    if (($role = $auth->getRole($roleName)) !== null) {
                        $auth->assign($role, $user_id);
                    }

                    if (($permission = $auth->getPermission($roleName)) !== null) {
                        $auth->assign($permission, $user_id);
                    }
                }
            }


            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function actionCreateUser()
    {
        $auth = Yii::$app->authManager;

        $email = Console::prompt('Введите email: ', ['required' => true, 'validator' => function($value, &$error) {
            if (!(new EmailValidator())->validate($value)) {
                $error = 'Неверный email.';
                return false;
            }

            if ((new ExistValidator(['targetClass' => User::class, 'targetAttribute' => 'email']))->validate($value)) {
                $error = 'Пользователь с таким email уже существует.';
                return false;
            }

            return true;
        }, 'error' => 'Email не должен быть пустым.']);

        $username = Console::prompt('Введите имя: ', ['required' => true, 'validator' => function($value, &$error) {
            if ((new ExistValidator(['targetClass' => User::class, 'targetAttribute' => 'username']))->validate($value)) {
                $error = 'Пользователь с таким именем уже существует.';
                return false;
            }

            return true;
        }, 'error' => 'Имя не должено быть пустым.']);

        $password = Console::prompt('Введите пароль: ', ['required' => true, 'validator' => function($value, &$error) {
            if (!(new StringValidator(['min' => 6]))->validate($value)) {
                $error = 'Пароль должен быть больше 6 символов.';
                return false;
            }

            return true;
        }, 'error' => 'Пароль не должен быть пустым.']);

        /* @var Role|null $role */
        $role = Console::prompt('Введите роль: ', ['required' => true, 'validator' => function($value) use ($auth) {
            return $auth->getRole($value) ? true : false;
        }, 'error' => 'Неверная роль.']);

        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        if ($user->save()) {
            $auth->assign($auth->getRole($role), $user->id);

            Console::output('Прозователь успешно создан.');
        } else {
            Console::errorSummary($user, ['showAllErrors' => true]);
        }
    }

    public function actionChangePassword()
    {
        $email = Console::prompt('Введите email: ', ['required' => true, 'validator' => function($value, &$error) {
            if (!(new EmailValidator())->validate($value)) {
                $error = 'Неверный email.';
                return false;
            }

            if (!(new ExistValidator(['targetClass' => User::class, 'targetAttribute' => 'email']))->validate($value)) {
                $error = 'Пользователь не найден.';
                return false;
            }

            return true;
        }, 'error' => 'Email не должен быть пустым.']);

        $password = Console::prompt('Введите пароль: ', ['required' => true, 'validator' => function($value, &$error) {
            if (!(new StringValidator(['min' => 6]))->validate($value)) {
                $error = 'Пароль должен быть больше 6 символов.';
                return false;
            }

            return true;
        }, 'error' => 'Пароль не должен быть пустым.']);

        if (($user = User::findOne(['email' => $email])) === null) {
            Console::error('Пользователь не найден.');
            return false;
        }

        $user->setPassword($password);

        if ($user->save()) {
            Console::output('Пароль успешно изменен.');
        } else {
            Console::errorSummary($user, ['showAllErrors' => true]);
        }
    }

    public function actionAddRole()
    {
        $auth = Yii::$app->authManager;

        $email = Console::prompt('Введите email: ', ['required' => true, 'validator' => function($value) {
            return (new EmailValidator())->validate($value);
        }, 'error' => 'Неправильный email.']);

        if (($user = User::findOne(['email' => $email])) === null) {
            Console::error('Пользователь не найден.');
            return false;
        }

        $role = Console::prompt('Введите роль: ', ['required' => true, 'validator' => function($value) use ($auth) {
            return $auth->getRole($value) ? true : false;
        }, 'error' => 'Неверная роль.']);

        $auth->assign($auth->getRole($role), $user->id);

        Console::output('Роль успешно добавлена.');
    }
}

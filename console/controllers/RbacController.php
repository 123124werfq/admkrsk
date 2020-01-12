<?php

namespace console\controllers;

use common\models\User;
use common\rbac\EntityAccessRule;
use common\rbac\MenuAccessRule;
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


            $entityAccessRule = new EntityAccessRule();
            $auth->add($entityAccessRule);


            $menuAccessRule = new MenuAccessRule();
            $auth->add($menuAccessRule);

            $backendEntityAccess = $auth->createPermission('backend.entityAccess');
            $backendEntityAccess->description = 'Доступ к сущностям';
            $backendEntityAccess->ruleName = $entityAccessRule->name;
            $auth->add($backendEntityAccess);


            $backendAddressIndex = $auth->createPermission('backend.address.index');
            $backendAddressIndex->description = 'Список адресов';
            $auth->add($backendAddressIndex);

            $backendAddressView = $auth->createPermission('backend.address.view');
            $backendAddressView->description = 'Просмотр адресов';
            $auth->add($backendAddressView);

            $backendManageAddress = $auth->createPermission('backend.address');
            $backendManageAddress->description = 'Управление адресами';
            $auth->add($backendManageAddress);
            $auth->addChild($backendManageAddress, $backendAddressIndex);
            $auth->addChild($backendManageAddress, $backendAddressView);


            $backendApplicationIndex = $auth->createPermission('backend.application.index');
            $backendApplicationIndex->description = 'Список приложений';
            $auth->add($backendApplicationIndex);

            $backendApplicationView = $auth->createPermission('backend.application.view');
            $backendApplicationView->description = 'Просмотр приложения';
            $auth->add($backendApplicationView);

            $backendApplicationCreate = $auth->createPermission('backend.application.create');
            $backendApplicationCreate->description = 'Создание приложения';
            $auth->add($backendApplicationCreate);

            $backendApplicationUpdate = $auth->createPermission('backend.application.update');
            $backendApplicationUpdate->description = 'Редактирование приложения';
            $auth->add($backendApplicationUpdate);

            $backendApplicationDelete = $auth->createPermission('backend.application.delete');
            $backendApplicationDelete->description = 'Удаление приложения';
            $auth->add($backendApplicationDelete);

            $backendApplicationLogIndex = $auth->createPermission('backend.application.log.index');
            $backendApplicationLogIndex->description = 'Список изменений';
            $auth->add($backendApplicationLogIndex);

            $backendApplicationLogView = $auth->createPermission('backend.application.log.view');
            $backendApplicationLogView->description = 'Просмотр изменений';
            $auth->add($backendApplicationLogView);

            $backendApplicationLogRestore = $auth->createPermission('backend.application.log.restore');
            $backendApplicationLogRestore->description = 'Восстановление изменений';
            $auth->add($backendApplicationLogRestore);

            $backendManageApplication = $auth->createPermission('backend.application');
            $backendManageApplication->description = 'Управление приложениями';
            $auth->add($backendManageApplication);
            $auth->addChild($backendManageApplication, $backendApplicationIndex);
            $auth->addChild($backendManageApplication, $backendApplicationView);
            $auth->addChild($backendManageApplication, $backendApplicationCreate);
            $auth->addChild($backendManageApplication, $backendApplicationUpdate);
            $auth->addChild($backendManageApplication, $backendApplicationDelete);
            $auth->addChild($backendManageApplication, $backendApplicationLogIndex);
            $auth->addChild($backendManageApplication, $backendApplicationLogView);
            $auth->addChild($backendManageApplication, $backendApplicationLogRestore);


            $backendAlertIndex = $auth->createPermission('backend.alert.index');
            $backendAlertIndex->description = 'Список всплывающих сообщений';
            $auth->add($backendAlertIndex);

            $backendAlertView = $auth->createPermission('backend.alert.view');
            $backendAlertView->description = 'Просмотр всплывающего сообщения';
            $auth->add($backendAlertView);

            $backendAlertCreate = $auth->createPermission('backend.alert.create');
            $backendAlertCreate->description = 'Создание всплывающего сообщения';
            $auth->add($backendAlertCreate);

            $backendAlertUpdate = $auth->createPermission('backend.alert.update');
            $backendAlertUpdate->description = 'Редактирование всплывающего сообщения';
            $auth->add($backendAlertUpdate);

            $backendAlertDelete = $auth->createPermission('backend.alert.delete');
            $backendAlertDelete->description = 'Удаление всплывающего сообщения';
            $auth->add($backendAlertDelete);

            $backendAlertLogIndex = $auth->createPermission('backend.alert.log.index');
            $backendAlertLogIndex->description = 'Список изменений';
            $auth->add($backendAlertLogIndex);

            $backendAlertLogView = $auth->createPermission('backend.alert.log.view');
            $backendAlertLogView->description = 'Просмотр изменений';
            $auth->add($backendAlertLogView);

            $backendAlertLogRestore = $auth->createPermission('backend.alert.log.restore');
            $backendAlertLogRestore->description = 'Восстановление изменений';
            $auth->add($backendAlertLogRestore);

            $backendManageAlert = $auth->createPermission('backend.alert');
            $backendManageAlert->description = 'Управление всплывающими сообщениями';
            $auth->add($backendManageAlert);
            $auth->addChild($backendManageAlert, $backendAlertIndex);
            $auth->addChild($backendManageAlert, $backendAlertView);
            $auth->addChild($backendManageAlert, $backendAlertCreate);
            $auth->addChild($backendManageAlert, $backendAlertUpdate);
            $auth->addChild($backendManageAlert, $backendAlertDelete);
            $auth->addChild($backendManageAlert, $backendAlertLogIndex);
            $auth->addChild($backendManageAlert, $backendAlertLogView);
            $auth->addChild($backendManageAlert, $backendAlertLogRestore);


            $backendCollectionList = $auth->createPermission('backend.collection.list');
            $backendCollectionList->description = 'Поиск коллекций';
            $auth->add($backendCollectionList);

            $backendCollectionImport = $auth->createPermission('backend.collection.import');
            $backendCollectionImport->description = 'Список коллекций';
            $auth->add($backendCollectionImport);

            $backendCollectionIndex = $auth->createPermission('backend.collection.index');
            $backendCollectionIndex->description = 'Список коллекций';
            $auth->add($backendCollectionIndex);

            $backendCollectionView = $auth->createPermission('backend.collection.view');
            $backendCollectionView->description = 'Просмотр коллекций';
            $auth->add($backendCollectionView);

            $backendCollectionCreate = $auth->createPermission('backend.collection.create');
            $backendCollectionCreate->description = 'Создание коллекции';
            $auth->add($backendCollectionCreate);

            $backendCollectionUpdate = $auth->createPermission('backend.collection.update');
            $backendCollectionUpdate->description = 'Редактирование коллекции';
            $auth->add($backendCollectionUpdate);

            $backendCollectionDelete = $auth->createPermission('backend.collection.delete');
            $backendCollectionDelete->description = 'Удаление коллекции';
            $auth->add($backendCollectionDelete);

            $backendCollectionLogIndex = $auth->createPermission('backend.collection.log.index');
            $backendCollectionLogIndex->description = 'Список изменений';
            $auth->add($backendCollectionLogIndex);

            $backendCollectionLogView = $auth->createPermission('backend.collection.log.view');
            $backendCollectionLogView->description = 'Просмотр изменений';
            $auth->add($backendCollectionLogView);

            $backendCollectionLogRestore = $auth->createPermission('backend.collection.log.restore');
            $backendCollectionLogRestore->description = 'Восстановление изменений';
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


            $backendControllerPageIndex = $auth->createPermission('backend.controllerPage.index');
            $backendControllerPageIndex->description = 'Список резервированных путей';
            $auth->add($backendControllerPageIndex);

            $backendControllerPageView = $auth->createPermission('backend.controllerPage.view');
            $backendControllerPageView->description = 'Просмотр резервированного пути';
            $auth->add($backendControllerPageView);

            $backendControllerPageCreate = $auth->createPermission('backend.controllerPage.create');
            $backendControllerPageCreate->description = 'Создание резервированного пути';
            $auth->add($backendControllerPageCreate);

            $backendControllerPageUpdate = $auth->createPermission('backend.controllerPage.update');
            $backendControllerPageUpdate->description = 'Редактирование резервированного пути';
            $auth->add($backendControllerPageUpdate);

            $backendControllerPageDelete = $auth->createPermission('backend.controllerPage.delete');
            $backendControllerPageDelete->description = 'Удаление резервированного пути';
            $auth->add($backendControllerPageDelete);

            $backendControllerPageLogIndex = $auth->createPermission('backend.controllerPage.log.index');
            $backendControllerPageLogIndex->description = 'Список изменений';
            $auth->add($backendControllerPageLogIndex);

            $backendControllerPageLogView = $auth->createPermission('backend.controllerPage.log.view');
            $backendControllerPageLogView->description = 'Просмотр изменений';
            $auth->add($backendControllerPageLogView);

            $backendControllerPageLogRestore = $auth->createPermission('backend.controllerPage.log.restore');
            $backendControllerPageLogRestore->description = 'Восстановление изменений';
            $auth->add($backendControllerPageLogRestore);

            $backendManageControllerPage = $auth->createPermission('backend.controllerPage');
            $backendManageControllerPage->description = 'Управление резервированными путями';
            $auth->add($backendManageControllerPage);
            $auth->addChild($backendManageControllerPage, $backendControllerPageIndex);
            $auth->addChild($backendManageControllerPage, $backendControllerPageView);
            $auth->addChild($backendManageControllerPage, $backendControllerPageCreate);
            $auth->addChild($backendManageControllerPage, $backendControllerPageUpdate);
            $auth->addChild($backendManageControllerPage, $backendControllerPageDelete);
            $auth->addChild($backendManageControllerPage, $backendControllerPageLogIndex);
            $auth->addChild($backendManageControllerPage, $backendControllerPageLogView);
            $auth->addChild($backendManageControllerPage, $backendControllerPageLogRestore);


            $backendFaqIndex = $auth->createPermission('backend.faq.index');
            $backendFaqIndex->description = 'Список вопросов';
            $auth->add($backendFaqIndex);

            $backendFaqView = $auth->createPermission('backend.faq.view');
            $backendFaqView->description = 'Просмотр вопроса';
            $auth->add($backendFaqView);

            $backendFaqCreate = $auth->createPermission('backend.faq.create');
            $backendFaqCreate->description = 'Создание вопроса';
            $auth->add($backendFaqCreate);

            $backendFaqUpdate = $auth->createPermission('backend.faq.update');
            $backendFaqUpdate->description = 'Редактирование вопроса';
            $auth->add($backendFaqUpdate);

            $backendFaqDelete = $auth->createPermission('backend.faq.delete');
            $backendFaqDelete->description = 'Удаление вопроса';
            $auth->add($backendFaqDelete);

            $backendFaqLogIndex = $auth->createPermission('backend.faq.log.index');
            $backendFaqLogIndex->description = 'Список изменений';
            $auth->add($backendFaqLogIndex);

            $backendFaqLogView = $auth->createPermission('backend.faq.log.view');
            $backendFaqLogView->description = 'Просмотр изменений';
            $auth->add($backendFaqLogView);

            $backendFaqLogRestore = $auth->createPermission('backend.faq.log.restore');
            $backendFaqLogRestore->description = 'Восстановление изменений';
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


            $backendFaqCategoryList = $auth->createPermission('backend.faqCategory.list');
            $backendFaqCategoryList->description = 'Поиск категорий';
            $auth->add($backendFaqCategoryList);

            $backendFaqCategoryIndex = $auth->createPermission('backend.faqCategory.index');
            $backendFaqCategoryIndex->description = 'Список категорий';
            $auth->add($backendFaqCategoryIndex);

            $backendFaqCategoryView = $auth->createPermission('backend.faqCategory.view');
            $backendFaqCategoryView->description = 'Просмотр категории';
            $auth->add($backendFaqCategoryView);

            $backendFaqCategoryCreate = $auth->createPermission('backend.faqCategory.create');
            $backendFaqCategoryCreate->description = 'Создание категории';
            $auth->add($backendFaqCategoryCreate);

            $backendFaqCategoryUpdate = $auth->createPermission('backend.faqCategory.update');
            $backendFaqCategoryUpdate->description = 'Редактирование категории';
            $auth->add($backendFaqCategoryUpdate);

            $backendFaqCategoryDelete = $auth->createPermission('backend.faqCategory.delete');
            $backendFaqCategoryDelete->description = 'Удаление категории';
            $auth->add($backendFaqCategoryDelete);

            $backendFaqCategoryLogIndex = $auth->createPermission('backend.faqCategory.log.index');
            $backendFaqCategoryLogIndex->description = 'Список изменений';
            $auth->add($backendFaqCategoryLogIndex);

            $backendFaqCategoryLogView = $auth->createPermission('backend.faqCategory.log.view');
            $backendFaqCategoryLogView->description = 'Просмотр изменений';
            $auth->add($backendFaqCategoryLogView);

            $backendFaqCategoryLogRestore = $auth->createPermission('backend.faqCategory.log.restore');
            $backendFaqCategoryLogRestore->description = 'Восстановление изменений';
            $auth->add($backendFaqCategoryLogRestore);

            $backendManageFaqCategory = $auth->createPermission('backend.faqCategory');
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


            $backendFormIndex = $auth->createPermission('backend.form.index');
            $backendFormIndex->description = 'Список форм';
            $auth->add($backendFormIndex);

            $backendFormView = $auth->createPermission('backend.form.view');
            $backendFormView->description = 'Просмотр формы';
            $auth->add($backendFormView);

            $backendFormCreate = $auth->createPermission('backend.form.create');
            $backendFormCreate->description = 'Создание формы';
            $auth->add($backendFormCreate);

            $backendFormCreateRow = $auth->createPermission('backend.form.createRow');
            $backendFormCreateRow->description = 'Создание поля формы';
            $auth->add($backendFormCreateRow);

            $backendFormUpdateRow = $auth->createPermission('backend.form.updateRow');
            $backendFormUpdateRow->description = 'Редактирование поля формы';
            $auth->add($backendFormUpdateRow);

            $backendFormUpdate = $auth->createPermission('backend.form.update');
            $backendFormUpdate->description = 'Редактирование формы';
            $auth->add($backendFormUpdate);

            $backendFormGetForm = $auth->createPermission('backend.form.getForm');
            $backendFormGetForm->description = 'Список форм';
            $auth->add($backendFormGetForm);

            $backendFormOrder = $auth->createPermission('backend.form.order');
            $backendFormOrder->description = 'Сортировка полей формы';
            $auth->add($backendFormOrder);

            $backendFormDelete = $auth->createPermission('backend.form.delete');
            $backendFormDelete->description = 'Удаление формы';
            $auth->add($backendFormDelete);

            $backendFormLogIndex = $auth->createPermission('backend.form.log.index');
            $backendFormLogIndex->description = 'Список изменений';
            $auth->add($backendFormLogIndex);

            $backendFormLogView = $auth->createPermission('backend.form.log.view');
            $backendFormLogView->description = 'Просмотр изменений';
            $auth->add($backendFormLogView);

            $backendFormLogRestore = $auth->createPermission('backend.form.log.restore');
            $backendFormLogRestore->description = 'Восстановление изменений';
            $auth->add($backendFormLogRestore);

            $backendManageForm = $auth->createPermission('backend.form');
            $backendManageForm->description = 'Управление формами';
            $auth->add($backendManageForm);
            $auth->addChild($backendManageForm, $backendFormIndex);
            $auth->addChild($backendManageForm, $backendFormView);
            $auth->addChild($backendManageForm, $backendFormCreate);
            $auth->addChild($backendManageForm, $backendFormCreateRow);
            $auth->addChild($backendManageForm, $backendFormUpdateRow);
            $auth->addChild($backendManageForm, $backendFormUpdate);
            $auth->addChild($backendManageForm, $backendFormGetForm);
            $auth->addChild($backendManageForm, $backendFormOrder);
            $auth->addChild($backendManageForm, $backendFormDelete);
            $auth->addChild($backendManageForm, $backendFormLogIndex);
            $auth->addChild($backendManageForm, $backendFormLogView);
            $auth->addChild($backendManageForm, $backendFormLogRestore);


            $backendFormInputTypeIndex = $auth->createPermission('backend.formInputType.index');
            $backendFormInputTypeIndex->description = 'Список типов полей';
            $auth->add($backendFormInputTypeIndex);

            $backendFormInputTypeView = $auth->createPermission('backend.formInputType.view');
            $backendFormInputTypeView->description = 'Просмотр типа поля';
            $auth->add($backendFormInputTypeView);

            $backendFormInputTypeCreate = $auth->createPermission('backend.formInputType.create');
            $backendFormInputTypeCreate->description = 'Создание типа поля';
            $auth->add($backendFormInputTypeCreate);

            $backendFormInputTypeUpdate = $auth->createPermission('backend.formInputType.update');
            $backendFormInputTypeUpdate->description = 'Редактирование типа поля';
            $auth->add($backendFormInputTypeUpdate);

            $backendFormInputTypeDelete = $auth->createPermission('backend.formInputType.delete');
            $backendFormInputTypeDelete->description = 'Удаление типа поля';
            $auth->add($backendFormInputTypeDelete);

            $backendFormInputTypeLogIndex = $auth->createPermission('backend.formInputType.log.index');
            $backendFormInputTypeLogIndex->description = 'Список изменений';
            $auth->add($backendFormInputTypeLogIndex);

            $backendFormInputTypeLogView = $auth->createPermission('backend.formInputType.log.view');
            $backendFormInputTypeLogView->description = 'Просмотр изменений';
            $auth->add($backendFormInputTypeLogView);

            $backendFormInputTypeLogRestore = $auth->createPermission('backend.formInputType.log.restore');
            $backendFormInputTypeLogRestore->description = 'Восстановление изменений';
            $auth->add($backendFormInputTypeLogRestore);

            $backendManageFormInputType = $auth->createPermission('backend.formInputType');
            $backendManageFormInputType->description = 'Управление типами полей';
            $auth->add($backendManageFormInputType);
            $auth->addChild($backendManageFormInputType, $backendFormInputTypeIndex);
            $auth->addChild($backendManageFormInputType, $backendFormInputTypeView);
            $auth->addChild($backendManageFormInputType, $backendFormInputTypeCreate);
            $auth->addChild($backendManageFormInputType, $backendFormInputTypeUpdate);
            $auth->addChild($backendManageFormInputType, $backendFormInputTypeDelete);
            $auth->addChild($backendManageFormInputType, $backendFormInputTypeLogIndex);
            $auth->addChild($backendManageFormInputType, $backendFormInputTypeLogView);
            $auth->addChild($backendManageFormInputType, $backendFormInputTypeLogRestore);


            $backendGalleryIndex = $auth->createPermission('backend.gallery.index');
            $backendGalleryIndex->description = 'Список галерей';
            $auth->add($backendGalleryIndex);

            $backendGalleryView = $auth->createPermission('backend.gallery.view');
            $backendGalleryView->description = 'Просмотр галереи';
            $auth->add($backendGalleryView);

            $backendGalleryCreate = $auth->createPermission('backend.gallery.create');
            $backendGalleryCreate->description = 'Создание галереи';
            $auth->add($backendGalleryCreate);

            $backendGalleryUpdate = $auth->createPermission('backend.gallery.update');
            $backendGalleryUpdate->description = 'Редактирование галереи';
            $auth->add($backendGalleryUpdate);

            $backendGalleryDelete = $auth->createPermission('backend.gallery.delete');
            $backendGalleryDelete->description = 'Удаление галереи';
            $auth->add($backendGalleryDelete);

            $backendGalleryLogIndex = $auth->createPermission('backend.gallery.log.index');
            $backendGalleryLogIndex->description = 'Список изменений';
            $auth->add($backendGalleryLogIndex);

            $backendGalleryLogView = $auth->createPermission('backend.gallery.log.view');
            $backendGalleryLogView->description = 'Просмотр изменений';
            $auth->add($backendGalleryLogView);

            $backendGalleryLogRestore = $auth->createPermission('backend.gallery.log.restore');
            $backendGalleryLogRestore->description = 'Восстановление изменений';
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
            $auth->add($backendMenuIndex);

            $backendMenuView = $auth->createPermission('backend.menu.view');
            $backendMenuView->description = 'Просмотр меню';
            $auth->add($backendMenuView);

            $backendMenuCreate = $auth->createPermission('backend.menu.create');
            $backendMenuCreate->description = 'Создание меню';
            $auth->add($backendMenuCreate);

            $backendMenuUpdate = $auth->createPermission('backend.menu.update');
            $backendMenuUpdate->description = 'Редактирование меню';
            $auth->add($backendMenuUpdate);

            $backendMenuDelete = $auth->createPermission('backend.menu.delete');
            $backendMenuDelete->description = 'Удаление меню';
            $auth->add($backendMenuDelete);

            $backendMenuLogIndex = $auth->createPermission('backend.menu.log.index');
            $backendMenuLogIndex->description = 'Список изменений';
            $auth->add($backendMenuLogIndex);

            $backendMenuLogView = $auth->createPermission('backend.menu.log.view');
            $backendMenuLogView->description = 'Просмотр изменений';
            $auth->add($backendMenuLogView);

            $backendMenuLogRestore = $auth->createPermission('backend.menu.log.restore');
            $backendMenuLogRestore->description = 'Восстановление изменений';
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
            $auth->add($backendNewsIndex);

            $backendNewsView = $auth->createPermission('backend.news.view');
            $backendNewsView->description = 'Просмотр новости';
            $auth->add($backendNewsView);

            $backendNewsCreate = $auth->createPermission('backend.news.create');
            $backendNewsCreate->description = 'Создание новости';
            $auth->add($backendNewsCreate);

            $backendNewsUpdate = $auth->createPermission('backend.news.update');
            $backendNewsUpdate->description = 'Редактирование новости';
            $auth->add($backendNewsUpdate);

            $backendNewsDelete = $auth->createPermission('backend.news.delete');
            $backendNewsDelete->description = 'Удаление новости';
            $auth->add($backendNewsDelete);

            $backendNewsLogIndex = $auth->createPermission('backend.news.log.index');
            $backendNewsLogIndex->description = 'Список изменений';
            $auth->add($backendNewsLogIndex);

            $backendNewsLogView = $auth->createPermission('backend.news.log.view');
            $backendNewsLogView->description = 'Просмотр изменений';
            $auth->add($backendNewsLogView);

            $backendNewsLogRestore = $auth->createPermission('backend.news.log.restore');
            $backendNewsLogRestore->description = 'Восстановление изменений';
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
            $auth->add($backendOpendataIndex);

            $backendOpendataView = $auth->createPermission('backend.opendata.view');
            $backendOpendataView->description = 'Просмотр набора';
            $auth->add($backendOpendataView);

            $backendOpendataCreate = $auth->createPermission('backend.opendata.create');
            $backendOpendataCreate->description = 'Создание набора';
            $auth->add($backendOpendataCreate);

            $backendOpendataUpdate = $auth->createPermission('backend.opendata.update');
            $backendOpendataUpdate->description = 'Редактирование набора';
            $auth->add($backendOpendataUpdate);

            $backendOpendataDelete = $auth->createPermission('backend.opendata.delete');
            $backendOpendataDelete->description = 'Удаление набора';
            $auth->add($backendOpendataDelete);

            $backendOpendataLogIndex = $auth->createPermission('backend.opendata.log.index');
            $backendOpendataLogIndex->description = 'Список изменений';
            $auth->add($backendOpendataLogIndex);

            $backendOpendataLogView = $auth->createPermission('backend.opendata.log.view');
            $backendOpendataLogView->description = 'Просмотр изменений';
            $auth->add($backendOpendataLogView);

            $backendOpendataLogRestore = $auth->createPermission('backend.opendata.log.restore');
            $backendOpendataLogRestore->description = 'Восстановление изменений';
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
            $auth->add($backendPageLayoutIndex);

            $backendPageIndex = $auth->createPermission('backend.page.index');
            $backendPageIndex->description = 'Список разделов';
            $auth->add($backendPageIndex);

            $backendPageView = $auth->createPermission('backend.page.view');
            $backendPageView->description = 'Просмотр раздела';
            $auth->add($backendPageView);

            $backendPageCreate = $auth->createPermission('backend.page.create');
            $backendPageCreate->description = 'Создание раздела';
            $auth->add($backendPageCreate);

            $backendPageUpdate = $auth->createPermission('backend.page.update');
            $backendPageUpdate->description = 'Редактирование раздела';
            $auth->add($backendPageUpdate);

            $backendPageDelete = $auth->createPermission('backend.page.delete');
            $backendPageDelete->description = 'Удаление раздела';
            $auth->add($backendPageDelete);

            $backendPageLogIndex = $auth->createPermission('backend.page.log.index');
            $backendPageLogIndex->description = 'Список изменений';
            $auth->add($backendPageLogIndex);

            $backendPageLogView = $auth->createPermission('backend.page.log.view');
            $backendPageLogView->description = 'Просмотр изменений';
            $auth->add($backendPageLogView);

            $backendPageLogRestore = $auth->createPermission('backend.page.log.restore');
            $backendPageLogRestore->description = 'Восстановление изменений';
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
            $auth->add($backendPollIndex);

            $backendPollView = $auth->createPermission('backend.poll.view');
            $backendPollView->description = 'Просмотр опроса';
            $auth->add($backendPollView);

            $backendPollExport = $auth->createPermission('backend.poll.export');
            $backendPollExport->description = 'Экспорт опроса';
            $auth->add($backendPollExport);

            $backendPollCreate = $auth->createPermission('backend.poll.create');
            $backendPollCreate->description = 'Создание опроса';
            $auth->add($backendPollCreate);

            $backendPollQuestionCreate = $auth->createPermission('backend.poll.questionCreate');
            $backendPollQuestionCreate->description = 'Создание вопроса';
            $auth->add($backendPollQuestionCreate);

            $backendPollUpdate = $auth->createPermission('backend.poll.update');
            $backendPollUpdate->description = 'Редактирование опроса';
            $auth->add($backendPollUpdate);

            $backendPollQuestionUpdate = $auth->createPermission('backend.poll.questionUpdate');
            $backendPollQuestionUpdate->description = 'Редактирование вопроса';
            $auth->add($backendPollQuestionUpdate);

            $backendPollDelete = $auth->createPermission('backend.poll.delete');
            $backendPollDelete->description = 'Удаление опроса';
            $auth->add($backendPollDelete);

            $backendPollQuestionDelete = $auth->createPermission('backend.poll.questionDelete');
            $backendPollQuestionDelete->description = 'Удаление вопроса';
            $auth->add($backendPollQuestionDelete);

            $backendPollLogIndex = $auth->createPermission('backend.poll.log.index');
            $backendPollLogIndex->description = 'Список изменений';
            $auth->add($backendPollLogIndex);

            $backendPollLogView = $auth->createPermission('backend.poll.log.view');
            $backendPollLogView->description = 'Просмотр изменений';
            $auth->add($backendPollLogView);

            $backendPollLogRestore = $auth->createPermission('backend.poll.log.restore');
            $backendPollLogRestore->description = 'Восстановление изменений';
            $auth->add($backendPollLogRestore);

            $backendManagePoll = $auth->createPermission('backend.poll');
            $backendManagePoll->description = 'Управление опроса';
            $auth->add($backendManagePoll);
            $auth->addChild($backendManagePoll, $backendPollIndex);
            $auth->addChild($backendManagePoll, $backendPollView);
            $auth->addChild($backendManagePoll, $backendPollExport);
            $auth->addChild($backendManagePoll, $backendPollCreate);
            $auth->addChild($backendManagePoll, $backendPollQuestionCreate);
            $auth->addChild($backendManagePoll, $backendPollUpdate);
            $auth->addChild($backendManagePoll, $backendPollQuestionUpdate);
            $auth->addChild($backendManagePoll, $backendPollDelete);
            $auth->addChild($backendManagePoll, $backendPollQuestionDelete);
            $auth->addChild($backendManagePoll, $backendPollLogIndex);
            $auth->addChild($backendManagePoll, $backendPollLogView);
            $auth->addChild($backendManagePoll, $backendPollLogRestore);


            $backendProjectIndex = $auth->createPermission('backend.project.index');
            $backendProjectIndex->description = 'Список проектов';
            $auth->add($backendProjectIndex);

            $backendProjectView = $auth->createPermission('backend.project.view');
            $backendProjectView->description = 'Просмотр проекта';
            $auth->add($backendProjectView);

            $backendProjectCreate = $auth->createPermission('backend.project.create');
            $backendProjectCreate->description = 'Создание проекта';
            $auth->add($backendProjectCreate);

            $backendProjectUpdate = $auth->createPermission('backend.project.update');
            $backendProjectUpdate->description = 'Редактирование проекта';
            $auth->add($backendProjectUpdate);

            $backendProjectDelete = $auth->createPermission('backend.project.delete');
            $backendProjectDelete->description = 'Удаление проекта';
            $auth->add($backendProjectDelete);

            $backendProjectLogIndex = $auth->createPermission('backend.project.log.index');
            $backendProjectLogIndex->description = 'Список изменений';
            $auth->add($backendProjectLogIndex);

            $backendProjectLogView = $auth->createPermission('backend.project.log.view');
            $backendProjectLogView->description = 'Просмотр изменений';
            $auth->add($backendProjectLogView);

            $backendProjectLogRestore = $auth->createPermission('backend.project.log.restore');
            $backendProjectLogRestore->description = 'Восстановление изменений';
            $auth->add($backendProjectLogRestore);

            $backendManageProject = $auth->createPermission('backend.project');
            $backendManageProject->description = 'Управление проектами и событиями';
            $auth->add($backendManageProject);
            $auth->addChild($backendManageProject, $backendProjectIndex);
            $auth->addChild($backendManageProject, $backendProjectView);
            $auth->addChild($backendManageProject, $backendProjectCreate);
            $auth->addChild($backendManageProject, $backendProjectUpdate);
            $auth->addChild($backendManageProject, $backendProjectDelete);
            $auth->addChild($backendManageProject, $backendProjectLogIndex);
            $auth->addChild($backendManageProject, $backendProjectLogView);
            $auth->addChild($backendManageProject, $backendProjectLogRestore);


            $backendServiceIndex = $auth->createPermission('backend.service.index');
            $backendServiceIndex->description = 'Список услуг';
            $auth->add($backendServiceIndex);

            $backendServiceView = $auth->createPermission('backend.service.view');
            $backendServiceView->description = 'Просмотр услуги';
            $auth->add($backendServiceView);

            $backendServiceCreate = $auth->createPermission('backend.service.create');
            $backendServiceCreate->description = 'Создание услуги';
            $auth->add($backendServiceCreate);

            $backendServiceUpdate = $auth->createPermission('backend.service.update');
            $backendServiceUpdate->description = 'Редактирование услуги';
            $auth->add($backendServiceUpdate);

            $backendServiceDelete = $auth->createPermission('backend.service.delete');
            $backendServiceDelete->description = 'Удаление услуги';
            $auth->add($backendServiceDelete);

            $backendServiceLogIndex = $auth->createPermission('backend.service.log.index');
            $backendServiceLogIndex->description = 'Список изменений';
            $auth->add($backendServiceLogIndex);

            $backendServiceLogView = $auth->createPermission('backend.service.log.view');
            $backendServiceLogView->description = 'Просмотр изменений';
            $auth->add($backendServiceLogView);

            $backendServiceLogRestore = $auth->createPermission('backend.service.log.restore');
            $backendServiceLogRestore->description = 'Восстановление изменений';
            $auth->add($backendServiceLogRestore);

            $backendManageService = $auth->createPermission('backend.service');
            $backendManageService->description = 'Управление услугами';
            $auth->add($backendManageService);
            $auth->addChild($backendManageService, $backendServiceIndex);
            $auth->addChild($backendManageService, $backendServiceView);
            $auth->addChild($backendManageService, $backendServiceCreate);
            $auth->addChild($backendManageService, $backendServiceUpdate);
            $auth->addChild($backendManageService, $backendServiceDelete);
            $auth->addChild($backendManageService, $backendServiceLogIndex);
            $auth->addChild($backendManageService, $backendServiceLogView);
            $auth->addChild($backendManageService, $backendServiceLogRestore);


            $backendServiceSituationIndex = $auth->createPermission('backend.serviceSituation.index');
            $backendServiceSituationIndex->description = 'Список жизненных ситуаций';
            $auth->add($backendServiceSituationIndex);

            $backendServiceSituationView = $auth->createPermission('backend.serviceSituation.view');
            $backendServiceSituationView->description = 'Просмотр жизненной ситуации';
            $auth->add($backendServiceSituationView);

            $backendServiceSituationCreate = $auth->createPermission('backend.serviceSituation.create');
            $backendServiceSituationCreate->description = 'Создание жизненной ситуации';
            $auth->add($backendServiceSituationCreate);

            $backendServiceSituationUpdate = $auth->createPermission('backend.serviceSituation.update');
            $backendServiceSituationUpdate->description = 'Редактирование жизненной ситуации';
            $auth->add($backendServiceSituationUpdate);

            $backendServiceSituationDelete = $auth->createPermission('backend.serviceSituation.delete');
            $backendServiceSituationDelete->description = 'Удаление жизненной ситуации';
            $auth->add($backendServiceSituationDelete);

            $backendServiceSituationLogIndex = $auth->createPermission('backend.serviceSituation.log.index');
            $backendServiceSituationLogIndex->description = 'Список изменений';
            $auth->add($backendServiceSituationLogIndex);

            $backendServiceSituationLogView = $auth->createPermission('backend.serviceSituation.log.view');
            $backendServiceSituationLogView->description = 'Просмотр изменений';
            $auth->add($backendServiceSituationLogView);

            $backendServiceSituationLogRestore = $auth->createPermission('backend.serviceSituation.log.restore');
            $backendServiceSituationLogRestore->description = 'Восстановление изменений';
            $auth->add($backendServiceSituationLogRestore);

            $backendManageServiceSituation = $auth->createPermission('backend.serviceSituation');
            $backendManageServiceSituation->description = 'Управление жизненными ситуациями';
            $auth->add($backendManageServiceSituation);
            $auth->addChild($backendManageServiceSituation, $backendServiceSituationIndex);
            $auth->addChild($backendManageServiceSituation, $backendServiceSituationView);
            $auth->addChild($backendManageServiceSituation, $backendServiceSituationCreate);
            $auth->addChild($backendManageServiceSituation, $backendServiceSituationUpdate);
            $auth->addChild($backendManageServiceSituation, $backendServiceSituationDelete);
            $auth->addChild($backendManageServiceSituation, $backendServiceSituationLogIndex);
            $auth->addChild($backendManageServiceSituation, $backendServiceSituationLogView);
            $auth->addChild($backendManageServiceSituation, $backendServiceSituationLogRestore);


            $backendServiceRubricIndex = $auth->createPermission('backend.serviceRubric.index');
            $backendServiceRubricIndex->description = 'Список жизненных ситуаций';
            $auth->add($backendServiceRubricIndex);

            $backendServiceRubricView = $auth->createPermission('backend.serviceRubric.view');
            $backendServiceRubricView->description = 'Просмотр жизненной ситуации';
            $auth->add($backendServiceRubricView);

            $backendServiceRubricCreate = $auth->createPermission('backend.serviceRubric.create');
            $backendServiceRubricCreate->description = 'Создание жизненной ситуации';
            $auth->add($backendServiceRubricCreate);

            $backendServiceRubricUpdate = $auth->createPermission('backend.serviceRubric.update');
            $backendServiceRubricUpdate->description = 'Редактирование жизненной ситуации';
            $auth->add($backendServiceRubricUpdate);

            $backendServiceRubricDelete = $auth->createPermission('backend.serviceRubric.delete');
            $backendServiceRubricDelete->description = 'Удаление жизненной ситуации';
            $auth->add($backendServiceRubricDelete);

            $backendServiceRubricLogIndex = $auth->createPermission('backend.serviceRubric.log.index');
            $backendServiceRubricLogIndex->description = 'Список изменений';
            $auth->add($backendServiceRubricLogIndex);

            $backendServiceRubricLogView = $auth->createPermission('backend.serviceRubric.log.view');
            $backendServiceRubricLogView->description = 'Просмотр изменений';
            $auth->add($backendServiceRubricLogView);

            $backendServiceRubricLogRestore = $auth->createPermission('backend.serviceRubric.log.restore');
            $backendServiceRubricLogRestore->description = 'Восстановление изменений';
            $auth->add($backendServiceRubricLogRestore);

            $backendManageServiceRubric = $auth->createPermission('backend.serviceRubric');
            $backendManageServiceRubric->description = 'Управление жизненными ситуациями';
            $auth->add($backendManageServiceRubric);
            $auth->addChild($backendManageServiceRubric, $backendServiceRubricIndex);
            $auth->addChild($backendManageServiceRubric, $backendServiceRubricView);
            $auth->addChild($backendManageServiceRubric, $backendServiceRubricCreate);
            $auth->addChild($backendManageServiceRubric, $backendServiceRubricUpdate);
            $auth->addChild($backendManageServiceRubric, $backendServiceRubricDelete);
            $auth->addChild($backendManageServiceRubric, $backendServiceRubricLogIndex);
            $auth->addChild($backendManageServiceRubric, $backendServiceRubricLogView);
            $auth->addChild($backendManageServiceRubric, $backendServiceRubricLogRestore);


            $backendStatisticIndex = $auth->createPermission('backend.statistic.index');
            $backendStatisticIndex->description = 'Статистика';
            $auth->add($backendStatisticIndex);

            $backendManageStatistic = $auth->createPermission('backend.statistic');
            $backendManageStatistic->description = 'Статистика';
            $auth->add($backendManageStatistic);
            $auth->addChild($backendManageStatistic, $backendStatisticIndex);


            $backendUserList = $auth->createPermission('backend.user.list');
            $backendUserList->description = 'Поиск пользователей';
            $auth->add($backendUserList);

            $backendUserIndex = $auth->createPermission('backend.user.index');
            $backendUserIndex->description = 'Список пользователей';
            $auth->add($backendUserIndex);

            $backendUserView = $auth->createPermission('backend.user.view');
            $backendUserView->description = 'Просмотр пользователя';
            $auth->add($backendUserView);

            $backendUserCreate = $auth->createPermission('backend.user.create');
            $backendUserCreate->description = 'Создание пользователя';
            $auth->add($backendUserCreate);

            $backendUserUpdate = $auth->createPermission('backend.user.update');
            $backendUserUpdate->description = 'Редактирование пользователя';
            $auth->add($backendUserUpdate);

            $backendUserDelete = $auth->createPermission('backend.user.delete');
            $backendUserDelete->description = 'Удаление пользователя';
            $auth->add($backendUserDelete);

            $backendUserLogIndex = $auth->createPermission('backend.user.log.index');
            $backendUserLogIndex->description = 'Список пользователей';
            $auth->add($backendUserLogIndex);

            $backendUserLogView = $auth->createPermission('backend.user.log.view');
            $backendUserLogView->description = 'Просмотр изменений';
            $auth->add($backendUserLogView);

            $backendUserLogRestore = $auth->createPermission('backend.user.log.restore');
            $backendUserLogRestore->description = 'Восстановление изменений';
            $auth->add($backendUserLogRestore);

            $backendManageUser = $auth->createPermission('backend.user');
            $backendManageUser->description = 'Управление пользователями';
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


            $backendUserGroupList = $auth->createPermission('backend.userGroup.list');
            $backendUserGroupList->description = 'Поиск групп пользователей';
            $auth->add($backendUserGroupList);

            $backendUserGroupIndex = $auth->createPermission('backend.userGroup.index');
            $backendUserGroupIndex->description = 'Список групп пользователей';
            $auth->add($backendUserGroupIndex);

            $backendUserGroupView = $auth->createPermission('backend.userGroup.view');
            $backendUserGroupView->description = 'Просмотр группы пользователей';
            $auth->add($backendUserGroupView);

            $backendUserGroupCreate = $auth->createPermission('backend.userGroup.create');
            $backendUserGroupCreate->description = 'Создание группы пользователей';
            $auth->add($backendUserGroupCreate);

            $backendUserGroupUpdate = $auth->createPermission('backend.userGroup.update');
            $backendUserGroupUpdate->description = 'Редактирование группы пользователей';
            $auth->add($backendUserGroupUpdate);

            $backendUserGroupDelete = $auth->createPermission('backend.userGroup.delete');
            $backendUserGroupDelete->description = 'Удаление группы пользователей';
            $auth->add($backendUserGroupDelete);

            $backendUserGroupAssign = $auth->createPermission('backend.userGroup.assign');
            $backendUserGroupAssign->description = 'Добавить пользователя в группу';
            $auth->add($backendUserGroupAssign);

            $backendUserGroupRevoke = $auth->createPermission('backend.userGroup.revoke');
            $backendUserGroupRevoke->description = 'Удалить пользователя из группы';
            $auth->add($backendUserGroupRevoke);

            $backendUserGroupLogIndex = $auth->createPermission('backend.userGroup.log.index');
            $backendUserGroupLogIndex->description = 'Список пользователей';
            $auth->add($backendUserGroupLogIndex);

            $backendUserGroupLogView = $auth->createPermission('backend.userGroup.log.view');
            $backendUserGroupLogView->description = 'Просмотр изменений';
            $auth->add($backendUserGroupLogView);

            $backendUserGroupLogRestore = $auth->createPermission('backend.userGroup.log.restore');
            $backendUserGroupLogRestore->description = 'Восстановление изменений';
            $auth->add($backendUserGroupLogRestore);

            $backendManageUserGroup = $auth->createPermission('backend.userGroup');
            $backendManageUserGroup->description = 'Управление группами пользователей';
            $auth->add($backendManageUserGroup);
            $auth->addChild($backendManageUserGroup, $backendUserGroupList);
            $auth->addChild($backendManageUserGroup, $backendUserGroupIndex);
            $auth->addChild($backendManageUserGroup, $backendUserGroupView);
            $auth->addChild($backendManageUserGroup, $backendUserGroupCreate);
            $auth->addChild($backendManageUserGroup, $backendUserGroupUpdate);
            $auth->addChild($backendManageUserGroup, $backendUserGroupDelete);
            $auth->addChild($backendManageUserGroup, $backendUserGroupAssign);
            $auth->addChild($backendManageUserGroup, $backendUserGroupRevoke);
            $auth->addChild($backendManageUserGroup, $backendUserGroupLogIndex);
            $auth->addChild($backendManageUserGroup, $backendUserGroupLogView);
            $auth->addChild($backendManageUserGroup, $backendUserGroupLogRestore);


            $backendUserRoleIndex = $auth->createPermission('backend.userRole.index');
            $backendUserRoleIndex->description = 'Список ролей пользователей';
            $auth->add($backendUserRoleIndex);

            $backendUserRoleView = $auth->createPermission('backend.userRole.view');
            $backendUserRoleView->description = 'Просмотр роли пользователей';
            $auth->add($backendUserRoleView);

            $backendUserRoleAssign = $auth->createPermission('backend.userRole.assign');
            $backendUserRoleAssign->description = 'Добавить пользователю роль';
            $auth->add($backendUserRoleAssign);

            $backendUserRoleRevoke = $auth->createPermission('backend.userRole.revoke');
            $backendUserRoleRevoke->description = 'Удалить роль у пользователя';
            $auth->add($backendUserRoleRevoke);

            $backendManageUserRole = $auth->createPermission('backend.userRole');
            $backendManageUserRole->description = 'Управление ролями пользователей';
            $auth->add($backendManageUserRole);
            $auth->addChild($backendManageUserRole, $backendUserRoleIndex);
            $auth->addChild($backendManageUserRole, $backendUserRoleView);
            $auth->addChild($backendManageUserRole, $backendUserRoleAssign);
            $auth->addChild($backendManageUserRole, $backendUserRoleRevoke);


            $backendVarsIndex = $auth->createPermission('backend.vars.index');
            $backendVarsIndex->description = 'Список переменных';
            $auth->add($backendVarsIndex);

            $backendVarsView = $auth->createPermission('backend.vars.view');
            $backendVarsView->description = 'Просмотр переменной';
            $auth->add($backendVarsView);

            $backendVarsCreate = $auth->createPermission('backend.vars.create');
            $backendVarsCreate->description = 'Создание переменной';
            $auth->add($backendVarsCreate);

            $backendVarsUpdate = $auth->createPermission('backend.vars.update');
            $backendVarsUpdate->description = 'Редактирование переменной';
            $auth->add($backendVarsUpdate);

            $backendVarsDelete = $auth->createPermission('backend.vars.delete');
            $backendVarsDelete->description = 'Удаление переменной';
            $auth->add($backendVarsDelete);

            $backendVarsLogIndex = $auth->createPermission('backend.vars.log.index');
            $backendVarsLogIndex->description = 'Список переменных';
            $auth->add($backendVarsLogIndex);

            $backendVarsLogView = $auth->createPermission('backend.vars.log.view');
            $backendVarsLogView->description = 'Просмотр изменений';
            $auth->add($backendVarsLogView);

            $backendVarsLogRestore = $auth->createPermission('backend.vars.log.restore');
            $backendVarsLogRestore->description = 'Восстановление изменений';
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
            $auth->addChild($backendManage, $backendEntityAccess);


            $user = $auth->createRole('user');
            $user->description = 'Пользователь';
            $auth->add($user);


            $backendAddress = $auth->createRole('admin.address');
            $backendAddress->description = 'Редактор адресов';
            $auth->add($backendAddress);
            $auth->addChild($backendAddress, $backendManage);
            $auth->addChild($backendAddress, $backendManageAddress);


            $backendMenuApplication = $auth->createPermission('menu.application');
            $backendMenuApplication->description = 'Приложения (API)';
            $backendMenuApplication->ruleName = $menuAccessRule->name;
            $auth->add($backendMenuApplication);
            $auth->addChild($backendManage, $backendMenuApplication);

            $backendApplication = $auth->createRole('admin.application');
            $backendApplication->description = 'Редактор приложений';
            $auth->add($backendApplication);
            $auth->addChild($backendApplication, $backendManage);
            $auth->addChild($backendApplication, $backendManageApplication);


            $backendMenuAlert = $auth->createPermission('menu.alert');
            $backendMenuAlert->description = 'Всплывающие сообщения';
            $backendMenuAlert->ruleName = $menuAccessRule->name;
            $auth->add($backendMenuAlert);
            $auth->addChild($backendManage, $backendMenuAlert);

            $backendAlert = $auth->createRole('admin.alert');
            $backendAlert->description = 'Редактор всплывающих сообщений';
            $auth->add($backendAlert);
            $auth->addChild($backendAlert, $backendManage);
            $auth->addChild($backendAlert, $backendManageAlert);


            $backendMenuCollection = $auth->createPermission('menu.collection');
            $backendMenuCollection->description = 'Списки';
            $backendMenuCollection->ruleName = $menuAccessRule->name;
            $auth->add($backendMenuCollection);
            $auth->addChild($backendManage, $backendMenuCollection);

            $backendCollection = $auth->createRole('admin.collection');
            $backendCollection->description = 'Редактор списков';
            $auth->add($backendCollection);
            $auth->addChild($backendCollection, $backendManage);
            $auth->addChild($backendCollection, $backendManageCollection);
            $auth->addChild($backendCollection, $backendMenuCollection);


            $backendMenuControllerPage = $auth->createPermission('menu.controllerPage');
            $backendMenuControllerPage->description = 'Резервированные пути';
            $backendMenuControllerPage->ruleName = $menuAccessRule->name;
            $auth->add($backendMenuControllerPage);
            $auth->addChild($backendManage, $backendMenuControllerPage);

            $backendControllerPage = $auth->createRole('admin.controllerPage');
            $backendControllerPage->description = 'Редактор резервированных путей';
            $auth->add($backendControllerPage);
            $auth->addChild($backendControllerPage, $backendManage);
            $auth->addChild($backendControllerPage, $backendManageControllerPage);


            $backendMenuFaq = $auth->createPermission('menu.faq');
            $backendMenuFaq->description = 'Вопросы и ответы';
            $backendMenuFaq->ruleName = $menuAccessRule->name;
            $auth->add($backendMenuFaq);
            $auth->addChild($backendManage, $backendMenuFaq);

            $backendFaq = $auth->createRole('admin.faq');
            $backendFaq->description = 'Редактор вопросов';
            $auth->add($backendFaq);
            $auth->addChild($backendFaq, $backendManage);
            $auth->addChild($backendFaq, $backendManageFaq);


            $backendMenuFaqCategory = $auth->createPermission('menu.faqCategory');
            $backendMenuFaqCategory->description = 'Категории';
            $backendMenuFaqCategory->ruleName = $menuAccessRule->name;
            $auth->add($backendMenuFaqCategory);
            $auth->addChild($backendManage, $backendMenuFaqCategory);

            $backendFaqCategory = $auth->createRole('admin.faqCategory');
            $backendFaqCategory->description = 'Редактор категорий вопросов';
            $auth->add($backendFaqCategory);
            $auth->addChild($backendFaqCategory, $backendManage);
            $auth->addChild($backendFaqCategory, $backendManageFaqCategory);


            $backendMenuForm = $auth->createPermission('menu.form');
            $backendMenuForm->description = 'Формы';
            $backendMenuForm->ruleName = $menuAccessRule->name;
            $auth->add($backendMenuForm);
            $auth->addChild($backendManage, $backendMenuForm);

            $backendForm = $auth->createRole('admin.form');
            $backendForm->description = 'Редактор форм';
            $auth->add($backendForm);
            $auth->addChild($backendForm, $backendManage);
            $auth->addChild($backendForm, $backendManageForm);


            $backendMenuFormInputType = $auth->createPermission('menu.formInputType');
            $backendMenuFormInputType->description = 'Поведения полей';
            $backendMenuFormInputType->ruleName = $menuAccessRule->name;
            $auth->add($backendMenuFormInputType);
            $auth->addChild($backendManage, $backendMenuFormInputType);

            $backendFormInputType = $auth->createRole('admin.formInputType');
            $backendFormInputType->description = 'Редактор типов форм';
            $auth->add($backendFormInputType);
            $auth->addChild($backendFormInputType, $backendManage);
            $auth->addChild($backendFormInputType, $backendManageFormInputType);


            $backendMenuGallery = $auth->createPermission('menu.gallery');
            $backendMenuGallery->description = 'Галереи';
            $backendMenuGallery->ruleName = $menuAccessRule->name;
            $auth->add($backendMenuGallery);
            $auth->addChild($backendManage, $backendMenuGallery);

            $backendGallery = $auth->createRole('admin.gallery');
            $backendGallery->description = 'Редактор галерей';
            $auth->add($backendGallery);
            $auth->addChild($backendGallery, $backendManage);
            $auth->addChild($backendGallery, $backendManageGallery);


            $backendMenuMenu = $auth->createPermission('menu.menu');
            $backendMenuMenu->description = 'Меню';
            $backendMenuMenu->ruleName = $menuAccessRule->name;
            $auth->add($backendMenuMenu);
            $auth->addChild($backendManage, $backendMenuMenu);

            $backendMenu = $auth->createRole('admin.menu');
            $backendMenu->description = 'Редактор меню';
            $auth->add($backendMenu);
            $auth->addChild($backendMenu, $backendManage);
            $auth->addChild($backendMenu, $backendManageMenu);


            $backendMenuNews = $auth->createPermission('menu.news');
            $backendMenuNews->description = 'Новости';
            $backendMenuNews->ruleName = $menuAccessRule->name;
            $auth->add($backendMenuNews);
            $auth->addChild($backendManage, $backendMenuNews);

            $backendNews = $auth->createRole('admin.news');
            $backendNews->description = 'Редактор новостей';
            $auth->add($backendNews);
            $auth->addChild($backendNews, $backendManage);
            $auth->addChild($backendNews, $backendManageNews);


            $backendMenuOpendata = $auth->createPermission('menu.opendata');
            $backendMenuOpendata->description = 'Открытые данные';
            $backendMenuOpendata->ruleName = $menuAccessRule->name;
            $auth->add($backendMenuOpendata);
            $auth->addChild($backendManage, $backendMenuOpendata);

            $backendOpendata = $auth->createRole('admin.opendata');
            $backendOpendata->description = 'Редактор открытых данных';
            $auth->add($backendOpendata);
            $auth->addChild($backendOpendata, $backendManage);
            $auth->addChild($backendOpendata, $backendManageOpendata);


            $backendMenuPage = $auth->createPermission('menu.page');
            $backendMenuPage->description = 'Разделы';
            $backendMenuPage->ruleName = $menuAccessRule->name;
            $auth->add($backendMenuPage);
            $auth->addChild($backendManage, $backendMenuPage);

            $backendPage = $auth->createRole('admin.page');
            $backendPage->description = 'Редактор разделов';
            $auth->add($backendPage);
            $auth->addChild($backendPage, $backendManage);
            $auth->addChild($backendPage, $backendManagePage);


            $backendMenuPoll = $auth->createPermission('menu.poll');
            $backendMenuPoll->description = 'Опросы';
            $backendMenuPoll->ruleName = $menuAccessRule->name;
            $auth->add($backendMenuPoll);
            $auth->addChild($backendManage, $backendMenuPoll);

            $backendPoll = $auth->createRole('admin.poll');
            $backendPoll->description = 'Редактор опросов';
            $auth->add($backendPoll);
            $auth->addChild($backendPoll, $backendManage);
            $auth->addChild($backendPoll, $backendManagePoll);


            $backendMenuProject = $auth->createPermission('menu.project');
            $backendMenuProject->description = 'Проекты';
            $backendMenuProject->ruleName = $menuAccessRule->name;
            $auth->add($backendMenuProject);
            $auth->addChild($backendManage, $backendMenuProject);

            $backendProject = $auth->createRole('admin.project');
            $backendProject->description = 'Редактор проектов и событий';
            $auth->add($backendProject);
            $auth->addChild($backendProject, $backendManage);
            $auth->addChild($backendProject, $backendManageProject);


            $backendMenuService = $auth->createPermission('menu.service');
            $backendMenuService->description = 'Услуги';
            $backendMenuService->ruleName = $menuAccessRule->name;
            $auth->add($backendMenuService);
            $auth->addChild($backendManage, $backendMenuService);

            $backendService = $auth->createRole('admin.service');
            $backendService->description = 'Редактор услуг';
            $auth->add($backendService);
            $auth->addChild($backendService, $backendManage);
            $auth->addChild($backendService, $backendManageService);


            $backendMenuServiceSituation = $auth->createPermission('menu.serviceSituation');
            $backendMenuServiceSituation->description = 'Жизненные ситуации';
            $backendMenuServiceSituation->ruleName = $menuAccessRule->name;
            $auth->add($backendMenuServiceSituation);
            $auth->addChild($backendManage, $backendMenuServiceSituation);

            $backendServiceSituation = $auth->createRole('admin.serviceSituation');
            $backendServiceSituation->description = 'Редактор жизненных ситуаций';
            $auth->add($backendServiceSituation);
            $auth->addChild($backendServiceSituation, $backendManage);
            $auth->addChild($backendServiceSituation, $backendManageServiceSituation);


            $backendMenuServiceRubric = $auth->createPermission('menu.serviceRubric');
            $backendMenuServiceRubric->description = 'Рубрикатор';
            $backendMenuServiceRubric->ruleName = $menuAccessRule->name;
            $auth->add($backendMenuServiceRubric);
            $auth->addChild($backendManage, $backendMenuServiceRubric);

            $backendServiceRubric = $auth->createRole('admin.serviceRubric');
            $backendServiceRubric->description = 'Редактор рубрик услуг';
            $auth->add($backendServiceRubric);
            $auth->addChild($backendServiceRubric, $backendManage);
            $auth->addChild($backendServiceRubric, $backendManageServiceRubric);


            $backendMenuStatistic = $auth->createPermission('menu.statistic');
            $backendMenuStatistic->description = 'Статистика';
            $backendMenuStatistic->ruleName = $menuAccessRule->name;
            $auth->add($backendMenuStatistic);
            $auth->addChild($backendManage, $backendMenuStatistic);

            $backendStatistic = $auth->createRole('admin.statistic');
            $backendStatistic->description = 'Статистика';
            $auth->add($backendStatistic);
            $auth->addChild($backendStatistic, $backendManage);
            $auth->addChild($backendStatistic, $backendManageStatistic);


            $backendMenuUser = $auth->createPermission('menu.user');
            $backendMenuUser->description = 'Пользователи';
            $backendMenuUser->ruleName = $menuAccessRule->name;
            $auth->add($backendMenuUser);
            $auth->addChild($backendManage, $backendMenuUser);

            $backendUser = $auth->createRole('admin.user');
            $backendUser->description = 'Редактор пользователей';
            $auth->add($backendUser);
            $auth->addChild($backendUser, $backendManage);
            $auth->addChild($backendUser, $backendManageUser);


            $backendMenuUserGroup = $auth->createPermission('menu.userGroup');
            $backendMenuUserGroup->description = 'Группы';
            $backendMenuUserGroup->ruleName = $menuAccessRule->name;
            $auth->add($backendMenuUserGroup);
            $auth->addChild($backendManage, $backendMenuUserGroup);

            $backendUserGroup = $auth->createRole('admin.userGroup');
            $backendUserGroup->description = 'Редактор групп пользователей';
            $auth->add($backendUserGroup);
            $auth->addChild($backendUserGroup, $backendManage);
            $auth->addChild($backendUserGroup, $backendManageUserGroup);


            $backendMenuUserRole = $auth->createPermission('menu.userRole');
            $backendMenuUserRole->description = 'Роли';
            $backendMenuUserRole->ruleName = $menuAccessRule->name;
            $auth->add($backendMenuUserRole);
            $auth->addChild($backendManage, $backendMenuUserRole);

            $backendUserRole = $auth->createRole('admin.userRole');
            $backendUserRole->description = 'Редактор ролей пользователей';
            $auth->add($backendUserRole);
            $auth->addChild($backendUserRole, $backendManage);
            $auth->addChild($backendUserRole, $backendManageUserRole);


            $backendMenuVars = $auth->createPermission('menu.vars');
            $backendMenuVars->description = 'Переменные';
            $backendMenuVars->ruleName = $menuAccessRule->name;
            $auth->add($backendMenuVars);
            $auth->addChild($backendManage, $backendMenuVars);

            $backendVars = $auth->createRole('admin.vars');
            $backendVars->description = 'Редактор переменных';
            $auth->add($backendVars);
            $auth->addChild($backendVars, $backendManage);
            $auth->addChild($backendVars, $backendManageVars);


            $admin = $auth->createRole('admin');
            $admin->description = 'Администратор';
            $auth->add($admin);
            $auth->addChild($admin, $backendAddress);
            $auth->addChild($admin, $backendApplication);
            $auth->addChild($admin, $backendAlert);
            $auth->addChild($admin, $backendCollection);
            $auth->addChild($admin, $backendControllerPage);
            $auth->addChild($admin, $backendFaq);
            $auth->addChild($admin, $backendFaqCategory);
            $auth->addChild($admin, $backendForm);
            $auth->addChild($admin, $backendFormInputType);
            $auth->addChild($admin, $backendGallery);
            $auth->addChild($admin, $backendMenu);
            $auth->addChild($admin, $backendNews);
            $auth->addChild($admin, $backendOpendata);
            $auth->addChild($admin, $backendPage);
            $auth->addChild($admin, $backendPoll);
            $auth->addChild($admin, $backendProject);
            $auth->addChild($admin, $backendService);
            $auth->addChild($admin, $backendServiceSituation);
            $auth->addChild($admin, $backendServiceRubric);
            $auth->addChild($admin, $backendStatistic);
            $auth->addChild($admin, $backendUser);
            $auth->addChild($admin, $backendUserGroup);
            $auth->addChild($admin, $backendUserRole);
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

            $auth->invalidateCache();
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

        $email = Console::prompt('Введите email: ', ['required' => true, 'validator' => function ($value, &$error) {
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

        $username = Console::prompt('Введите имя: ', ['required' => true, 'validator' => function ($value, &$error) {
            if ((new ExistValidator(['targetClass' => User::class, 'targetAttribute' => 'username']))->validate($value)) {
                $error = 'Пользователь с таким именем уже существует.';
                return false;
            }

            return true;
        }, 'error' => 'Имя не должено быть пустым.']);

        $password = Console::prompt('Введите пароль: ', ['required' => true, 'validator' => function ($value, &$error) {
            if (!(new StringValidator(['min' => 6]))->validate($value)) {
                $error = 'Пароль должен быть больше 6 символов.';
                return false;
            }

            return true;
        }, 'error' => 'Пароль не должен быть пустым.']);

        /* @var Role|null $role */
        $role = Console::prompt('Введите роль: ', ['required' => true, 'validator' => function ($value) use ($auth) {
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
            $auth->invalidateCache();

            Console::output('Прозователь успешно создан.');
        } else {
            Console::errorSummary($user, ['showAllErrors' => true]);
        }
    }

    public function actionChangePassword()
    {
        $email = Console::prompt('Введите email: ', ['required' => true, 'validator' => function ($value, &$error) {
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

        $password = Console::prompt('Введите пароль: ', ['required' => true, 'validator' => function ($value, &$error) {
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

        $email = Console::prompt('Введите email: ', ['required' => true, 'validator' => function ($value) {
            return (new EmailValidator())->validate($value);
        }, 'error' => 'Неправильный email.']);

        if (($user = User::findOne(['email' => $email])) === null) {
            Console::error('Пользователь не найден.');
            return false;
        }

        $role = Console::prompt('Введите роль: ', ['required' => true, 'validator' => function ($value) use ($auth) {
            return $auth->getRole($value) ? true : false;
        }, 'error' => 'Неверная роль.']);

        if (!$auth->checkAccess($user->id, $role)) {
            $auth->assign($auth->getRole($role), $user->id);
            $auth->invalidateCache();
        }

        Console::output('Роль успешно добавлена.');
    }
}

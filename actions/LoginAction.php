<?php

namespace yii2mod\user\actions;

use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class LoginAction
 *
 * @package yii2mod\user\actions
 */
class LoginAction extends Action
{
    /**
     * @var string name of the view, which should be rendered
     */
    public $view = '@vendor/yii2mod/yii2-user/views/login';

    /**
     * @var string Login Form className
     */
    public $modelClass = 'yii2mod\user\models\LoginForm';

    /**
     * @var string layout the name of the layout to be applied to this view
     */
    public $layout;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->layout !== null) {
            $this->controller->layout = $this->layout;
        }
    }

    /**
     * Logs in a user.
     *
     * @return string
     */
    public function run()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirectTo(Yii::$app->getHomeUrl());
        }

        $model = Yii::createObject($this->modelClass);
        $load = $model->load(Yii::$app->request->post());

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($model);
        }

        if ($load && $model->login()) {
            return $this->redirectTo(Yii::$app->getUser()->getReturnUrl());
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}

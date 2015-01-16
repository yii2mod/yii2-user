<?php

namespace yii2mod\user\actions;

use yii\base\Action;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class LoginAction
 * @package yii2mod\user\actions
 */
class LoginAction extends Action
{
    /**
     * @var string
     */
    public $view = '@vendor/yii2mod/yii2-user/views/login';

    /**
     * @var string Login Form className
     */
    public $modelClass = 'yii2mod\user\models\LoginForm';

    /**
     * @return string
     */
    public function run()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->controller->goHome();
        }
        $model = new $this->modelClass;
        $load = $model->load(\Yii::$app->request->post());

        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($load && $model->login()) {
            $model->getUser()->updateLastLogin();
            return $this->controller->goBack();
        } else {
            return $this->controller->render($this->view, [
                'model' => $model,
            ]);
        }
    }

}

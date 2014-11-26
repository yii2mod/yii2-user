<?php
namespace yii2mod\user\actions;


use yii\base\Action;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii2mod\user\models\LoginForm;

/**
 * Class LoginAction
 * @package yii2mod\user\actions
 */
class LoginAction extends Action
{
    /**
     * @var string
     */
    public $view = '@vendor/yii2mod/user/views/login';

    /**
     * @return string
     */
    public function run()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->controller->goHome();
        }
        $model = new LoginForm();
        $load = $model->load(\Yii::$app->request->post());
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($load && $model->login()) {
            if(\Yii::$app->getUser()->can('salesRep')) {
                return \Yii::$app->response->redirect('/admin');
            }
            else {
                return $this->controller->goBack();
            }

        } else {
            return $this->controller->render($this->view, [
                'model' => $model,
            ]);
        }
    }

}
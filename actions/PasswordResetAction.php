<?php
namespace yii2mod\user\actions;

use Yii;
use yii\base\Action;
use yii\web\BadRequestHttpException;
use yii2mod\user\models\ResetPasswordForm;

/**
 * Class LoginAction
 * @package yii2mod\user\actions
 */
class PasswordResetAction extends Action
{
    public $view = '@vendor/yii2mod/yii2-user/views/resetPassword';

    /**
     * @param $token
     *
     * @throws \yii\web\BadRequestHttpException
     * @return string
     */
    public function run($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');
            return $this->controller->goHome();
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }

}
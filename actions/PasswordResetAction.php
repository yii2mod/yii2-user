<?php

namespace yii2mod\user\actions;

use Yii;
use yii\web\BadRequestHttpException;
use yii\base\InvalidParamException;

/**
 * Class PasswordResetAction
 * @package yii2mod\user\actions
 */
class PasswordResetAction extends Action
{
    /**
     * @var string name of the view, which should be rendered
     */
    public $view = '@vendor/yii2mod/yii2-user/views/resetPassword';

    /**
     * @var string reset password model class
     */
    public $modelClass = 'yii2mod\user\models\ResetPasswordForm';

    /**
     * @var string message to be set on success.
     */
    public $successMessage = 'New password was saved.';

    /**
     * Reset password for a user.
     *
     * @param $token
     * @return string|\yii\web\Response
     *
     * @throws \yii\web\BadRequestHttpException
     */
    public function run($token)
    {
        try {
            $model = Yii::createObject($this->modelClass, [$token]);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('yii2mod.user', $this->successMessage));
            return $this->redirectTo(Yii::$app->getHomeUrl());
        }

        return $this->controller->render($this->view, [
            'model' => $model
        ]);
    }
}
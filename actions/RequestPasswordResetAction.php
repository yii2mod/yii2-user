<?php

namespace yii2mod\user\actions;

use Yii;

/**
 * Class RequestPasswordResetAction
 * @package yii2mod\user\actions
 */
class RequestPasswordResetAction extends Action
{
    /**
     * @var string view path
     */
    public $view = '@vendor/yii2mod/yii2-user/views/requestPasswordResetToken';

    /**
     * @var string password reset request form class
     */
    public $modelClass = 'yii2mod\user\models\PasswordResetRequestForm';

    /**
     * @var string success message to the user when the mail is sent successfully
     */
    public $successMessage = 'Check your email for further instructions.';

    /**
     * @var string error message for the user when the email was not sent
     */
    public $errorMessage = 'Sorry, we are unable to reset password for email provided.';

    /**
     * Request password reset for a user.
     *
     * @return string|\yii\web\Response
     */
    public function run()
    {
        $model = Yii::createObject($this->modelClass);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('yii2mod.user', $this->successMessage));
                return $this->redirectTo(Yii::$app->getHomeUrl());
            } else {
                Yii::$app->getSession()->setFlash('error', Yii::t('yii2mod.user', $this->errorMessage));
            }
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}
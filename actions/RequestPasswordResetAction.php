<?php

namespace yii2mod\user\actions;

use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii2mod\user\traits\EventTrait;

/**
 * Class RequestPasswordResetAction
 *
 * @package yii2mod\user\actions
 */
class RequestPasswordResetAction extends Action
{
    use EventTrait;

    /**
     * Event is triggered before requesting password reset.
     * Triggered with \yii2mod\user\events\FormEvent.
     */
    const EVENT_BEFORE_REQUEST = 'beforeRequest';

    /**
     * Event is triggered after requesting password reset.
     * Triggered with \yii2mod\user\events\FormEvent.
     */
    const EVENT_AFTER_REQUEST = 'afterRequest';

    /**
     * @var string name of the view, which should be rendered
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
        $event = $this->getFormEvent($model);

        $this->trigger(self::EVENT_BEFORE_REQUEST, $event);

        $load = $model->load(Yii::$app->request->post());

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($model);
        }

        if ($load && $model->validate()) {
            if ($model->sendEmail()) {
                $this->trigger(self::EVENT_AFTER_REQUEST, $event);
                Yii::$app->getSession()->setFlash('success', $this->successMessage);

                return $this->redirectTo(Yii::$app->getHomeUrl());
            } else {
                Yii::$app->getSession()->setFlash('error', $this->errorMessage);
            }
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}

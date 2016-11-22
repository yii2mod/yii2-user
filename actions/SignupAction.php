<?php

namespace yii2mod\user\actions;

use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii2mod\user\traits\EventTrait;

/**
 * Class SignupAction
 *
 * @package yii2mod\user\actions
 */
class SignupAction extends Action
{
    use EventTrait;

    /**
     * Event is triggered after creating SignupForm class.
     * Triggered with \yii2mod\user\events\FormEvent.
     */
    const EVENT_BEFORE_SIGNUP = 'beforeSignup';

    /**
     * Event is triggered after successful signup.
     * Triggered with \yii2mod\user\events\FormEvent.
     */
    const EVENT_AFTER_SIGNUP = 'afterSignup';

    /**
     * @var string name of the view, which should be rendered
     */
    public $view = '@vendor/yii2mod/yii2-user/views/signup';

    /**
     * @var string signup form class
     */
    public $modelClass = 'yii2mod\user\models\SignupForm';

    /**
     * Signup a user.
     *
     * @return string|\yii\web\Response
     */
    public function run()
    {
        $model = Yii::createObject($this->modelClass);
        $event = $this->getFormEvent($model);

        $this->trigger(self::EVENT_BEFORE_SIGNUP, $event);

        $load = $model->load(Yii::$app->request->post());

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($model);
        }

        if ($load && ($user = $model->signup()) !== null) {
            $this->trigger(self::EVENT_AFTER_SIGNUP, $event);
            if (Yii::$app->getUser()->login($user)) {
                return $this->redirectTo(Yii::$app->getUser()->getReturnUrl());
            }
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}

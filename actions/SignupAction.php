<?php

namespace yii2mod\user\actions;

use Yii;
use yii\base\Action;

/**
 * Class SignupAction
 * @package yii2mod\user\actions
 */
class SignupAction extends Action
{
    /**
     * @var string signup view path
     */
    public $view = '@vendor/yii2mod/yii2-user/views/signup';

    /**
     * @var string signup form class
     */
    public $modelClass = 'yii2mod\user\models\SignupForm';

    /**
     * Signup action
     * @return string
     */
    public function run()
    {
        $model = Yii::createObject($this->modelClass);

        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->controller->goBack();
                }
            }
        }

        return $this->controller->render($this->view, [
            'model' => $model
        ]);
    }

}

<?php
namespace yii2mod\user\actions;

use yii\base\Action;

/**
 * Class RequestPasswordResetAction
 * @package yii2mod\user\actions
 */
class RequestPasswordResetAction extends Action
{
    /**
     * @var string
     */
    public $view = '@vendor/yii2mod/yii2-user/views/requestPasswordResetToken';

    /**
     * @var string password reset request form class
     */
    public $modelClass = 'yii2mod\user\models\PasswordResetRequestForm';

    /**
     * @return string|\yii\web\Response
     */
    public function run()
    {
        $model = new $this->modelClass;
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                \Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');
                return $this->controller->goHome();
            } else {
                \Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
} 

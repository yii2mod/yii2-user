<?php
namespace yii2mod\user\actions;

use yii\base\Action;
use yii2mod\user\models\SignupForm;

/**
 * Class SignupAction
 * @package yii2mod\user\actions
 * @author Igor Chepurnoy
 */
class SignupAction extends Action
{
    /**
     * View path
     * @var string
     */
    public $view = '@vendor/yii2mod/yii2-user/views/signup';

    /**
     * Run func
     * @return string
     */
    public function run()
    {
        $model = new SignupForm();
        if ($model->load(\Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (\Yii::$app->getUser()->login($user)) {
                    return $this->controller->goBack();
                }
            }
        }
        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }

}
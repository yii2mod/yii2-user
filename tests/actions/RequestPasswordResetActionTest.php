<?php

namespace yii2mod\user\tests\actions;

use Yii;
use yii\web\Response;
use yii2mod\user\actions\PasswordResetAction;
use yii2mod\user\actions\RequestPasswordResetAction;
use yii2mod\user\models\UserModel;
use yii2mod\user\tests\TestCase;

/**
 * Class RequestPasswordResetActionTest
 *
 * @package yii2mod\user\tests\actions
 */
class RequestPasswordResetActionTest extends TestCase
{
    /**
     * Runs the action.
     *
     * @param array $config
     *
     * @return array|\yii\web\Response response
     */
    protected function runAction(array $config = [])
    {
        $action = new RequestPasswordResetAction('request-reset-password', $this->createController(), $config);

        return $action->run($config);
    }

    // Tests:

    public function testViewRequestResetPassword()
    {
        $response = $this->runAction();
        $this->assertEquals('@vendor/yii2mod/yii2-user/views/requestPasswordResetToken', $response['view']);
    }

    public function testSuccessRequestResetPassword()
    {
        // send request
        $email = 'demo@mail.com';
        Yii::$app->request->bodyParams = [
            'PasswordResetRequestForm' => [
                'email' => $email,
            ],
        ];
        $this->runAction();
        $this->assertTrue(file_exists($this->getMessageFile()));

        $emailMessage = file_get_contents($this->getMessageFile());
        $this->assertContains($email, $emailMessage);

        // try to reset password
        $user = UserModel::find()->one();
        Yii::$app->request->bodyParams = [
            'ResetPasswordForm' => [
                'password' => 'new-password',
                'confirmPassword' => 'new-password',
            ],
        ];
        $passwordResetResponse = (new PasswordResetAction('reset-password', $this->createController()))->run($user->password_reset_token);

        $this->assertInstanceOf(Response::class, $passwordResetResponse);
    }

    /**
     * Get message file
     *
     * @return string
     */
    private function getMessageFile()
    {
        return Yii::getAlias(Yii::$app->mailer->fileTransportPath) . '/testing_message.eml';
    }
}

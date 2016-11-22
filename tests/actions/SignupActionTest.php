<?php

namespace yii2mod\user\tests\actions;

use Yii;
use yii2mod\user\actions\SignupAction;
use yii2mod\user\tests\TestCase;

/**
 * Class SignupActionTest
 *
 * @package yii2mod\user\tests\actions
 */
class SignupActionTest extends TestCase
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
        $action = new SignupAction('signup', $this->createController(), $config);

        return $action->run();
    }

    // Tests:

    public function testViewSignup()
    {
        $response = $this->runAction();
        $this->assertEquals('@vendor/yii2mod/yii2-user/views/signup', $response['view']);
    }

    public function testSignupSuccess()
    {
        Yii::$app->request->bodyParams = [
            'SignupForm' => [
                'email' => 'test-test@mail.com',
                'password' => 'password',
                'username' => 'test-user',
            ],
        ];
        $this->runAction();
        $this->assertFalse(Yii::$app->user->isGuest);
    }

    public function testSignupError()
    {
        Yii::$app->request->bodyParams = [
            'SignupForm' => [
                'email' => 'demo@mail.com',
                'password' => 'failed-password',
            ],
        ];
        $response = $this->runAction();
        $this->assertFalse($response['params']['model']->validate());
    }
}

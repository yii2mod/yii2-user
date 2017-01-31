<?php

namespace yii2mod\user\tests\actions;

use Yii;
use yii2mod\user\actions\LoginAction;
use yii2mod\user\tests\TestCase;

/**
 * Class LoginActionTest
 *
 * @package yii2mod\user\tests\actions
 */
class LoginActionTest extends TestCase
{
    /**
     * Runs the action.
     *
     * @param array $config
     *
     * @return string
     */
    protected function runAction(array $config = [])
    {
        $action = new LoginAction('login', $this->createController(), $config);

        return $action->run();
    }

    // Tests:

    public function testViewLogin()
    {
        $response = $this->runAction();
        $this->assertEquals('@vendor/yii2mod/yii2-user/views/login', $response['view']);
    }

    public function testLoginSuccess()
    {
        Yii::$app->request->bodyParams = [
            'LoginForm' => [
                'email' => 'demo@mail.com',
                'password' => 'password',
            ],
        ];
        $this->runAction();
        $this->assertFalse(Yii::$app->user->isGuest);
    }

    public function testLoginError()
    {
        Yii::$app->request->bodyParams = [
            'LoginForm' => [
                'email' => 'demo@mail.com',
                'password' => 'failed-password',
            ],
        ];
        $this->runAction();
        $this->assertTrue(Yii::$app->user->isGuest);
    }
}

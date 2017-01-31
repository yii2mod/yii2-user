<?php

namespace yii2mod\user\tests\actions;

use Yii;
use yii2mod\user\actions\LoginAction;
use yii2mod\user\actions\LogoutAction;
use yii2mod\user\tests\TestCase;

/**
 * Class LogoutActionTest
 *
 * @package yii2mod\user\tests\actions
 */
class LogoutActionTest extends TestCase
{
    /**
     * Runs the action.
     *
     * @param $actionClass
     * @param $id
     * @param array $config
     *
     * @return array|\yii\web\Response response
     */
    protected function runAction($actionClass, $id, array $config = [])
    {
        $action = new $actionClass($id, $this->createController(), $config);

        return $action->run();
    }

    // Tests:

    public function testLogout()
    {
        // login as the demo user
        Yii::$app->request->bodyParams = [
            'LoginForm' => [
                'email' => 'demo@mail.com',
                'password' => 'password',
            ],
        ];
        $this->runAction(LoginAction::class, 'login');
        $this->assertFalse(Yii::$app->user->isGuest);

        // logout
        $this->runAction(LogoutAction::class, 'logout');
        $this->assertTrue(Yii::$app->user->isGuest);
    }
}

<?php

namespace yii2mod\user\tests\models;

use yii2mod\user\events\CreateUserEvent;
use yii2mod\user\models\UserModel;
use yii2mod\user\tests\TestCase;

/**
 * Class UserModelTest
 *
 * @package yii2mod\user\tests\models
 */
class UserModelTest extends TestCase
{
    public function testCheckValidation()
    {
        $model = new UserModel(['scenario' => 'create']);

        $this->assertFalse($model->validate());
        $this->assertArrayHasKey('username', $model->errors);
        $this->assertArrayHasKey('email', $model->errors);
        $this->assertArrayHasKey('plainPassword', $model->errors);
        $this->assertNull($model->create());
    }

    public function testCreateUserWithAlreadyExistEmail()
    {
        $model = new UserModel(['scenario' => 'create']);
        $model->email = 'demo@mail.com';
        $model->plainPassword = 'password';
        $model->username = 'demo';

        $this->assertNull($model->create());
        $this->assertArrayHasKey('email', $model->errors);
    }

    public function testCorrectCreateUser()
    {
        $model = new UserModel(['scenario' => 'create']);
        $model->email = 'new-user@example.org';
        $model->plainPassword = 'password';
        $model->username = 'new-user';

        $this->assertTrue($model->validate());
        $this->assertEmpty($model->errors);
        $this->assertInstanceOf(UserModel::class, $model->create());
    }

    public function testCreateUserEvents()
    {
        $model = new UserModel(['scenario' => 'create']);
        $model->email = 'new-user@example.org';
        $model->plainPassword = 'password';
        $model->username = 'new-user';

        $model->on(UserModel::BEFORE_CREATE, function (CreateUserEvent $event) {
            $this->assertInstanceOf(UserModel::class, $event->getUser());
            $this->assertTrue($event->getUser()->isNewRecord);
        });

        $model->on(UserModel::AFTER_CREATE, function (CreateUserEvent $event) {
            $this->assertInstanceOf(UserModel::class, $event->getUser());
            $this->assertEquals('new-user', $event->getUser()->username);
            $this->assertEquals('new-user@example.org', $event->getUser()->email);
            $this->assertFalse($event->getUser()->isNewRecord);
        });

        $this->assertInstanceOf(UserModel::class, $model->create());
    }
}

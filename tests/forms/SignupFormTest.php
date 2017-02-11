<?php

namespace yii2mod\user\tests\forms;

use yii2mod\user\models\SignupForm;
use yii2mod\user\models\UserModel;
use yii2mod\user\tests\TestCase;

/**
 * Class SignupFormTest
 *
 * @package yii2mod\user\tests\forms
 */
class SignupFormTest extends TestCase
{
    public function testSignupWithoutCredentials()
    {
        $model = new SignupForm();

        $this->assertNull($model->signup());
    }

    public function testSignupWithAlreadyExistUsername()
    {
        $model = new SignupForm();
        $model->email = 'demo@mail.com';
        $model->password = 'password';
        $model->username = 'demo';

        $this->assertNull($model->signup());
        $this->assertArrayHasKey('username', $model->errors);
    }

    public function testSignupWithAlreadyExistEmail()
    {
        $model = new SignupForm();
        $model->email = 'demo@mail.com';
        $model->password = 'password';
        $model->username = 'demo';

        $this->assertNull($model->signup());
        $this->assertNull($model->getUser());
        $this->assertArrayHasKey('email', $model->errors);
    }

    public function testSignupCorrect()
    {
        $model = new SignupForm();
        $model->email = 'demo2@mail.com';
        $model->password = 'password';
        $model->username = 'demo2';

        $this->assertInstanceOf(UserModel::class, $model->signup());
        $this->assertInstanceOf(UserModel::class, $model->getUser());
    }
}

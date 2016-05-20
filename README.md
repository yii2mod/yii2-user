User module
===========
Flexible user registration and authentication module for Yii2

[![Latest Stable Version](https://poser.pugx.org/yii2mod/yii2-user/v/stable)](https://packagist.org/packages/yii2mod/yii2-user) [![Total Downloads](https://poser.pugx.org/yii2mod/yii2-user/downloads)](https://packagist.org/packages/yii2mod/yii2-user) [![License](https://poser.pugx.org/yii2mod/yii2-user/license)](https://packagist.org/packages/yii2mod/yii2-user)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yii2mod/yii2-user/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yii2mod/yii2-user/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/yii2mod/yii2-user/badges/build.png?b=master)](https://scrutinizer-ci.com/g/yii2mod/yii2-user/build-status/master)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yii2mod/yii2-user "*"
```

or add

```
"yii2mod/yii2-user": "*"
```

to the require section of your `composer.json` file.

Configuration
======================================
1) Your need to create the UserModel class that be extends of [BaseUserModel](https://github.com/yii2mod/yii2-user/blob/master/models/BaseUserModel.php) and configure the property `identityClass` for `user` component in your project configuration, for example:
```php
'user' => [
    'identityClass' => 'app\models\UserModel',
],
```

2) For sending emails you need to configure the `mail` and `mailer` components in the configuration of your project.

3) If you don't have the `passwordResetToken.php` template file in the mail folder of your project, then you need to create it, for example:
```php
<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/password-reset', 'token' => $user->passwordResetToken]);
?>

Hello <?php echo Html::encode($user->username) ?>,

Follow the link below to reset your password:

<?php echo Html::a(Html::encode($resetLink), $resetLink) ?>

```
> This template used for password reset email.

Usage
======================================
If you use this extension separate from the [base template](https://github.com/yii2mod/base), then you need execute cms init migration by the following command: 
```
php yii migrate/up --migrationPath=@vendor/yii2mod/yii2-user/migrations
```

Add to SiteController (or configure via `$route` param in urlManager):
```php
    /**
     * @return array
     */
    public function actions()
    {
        return [
            'login' => [
                'class' => 'yii2mod\user\actions\LoginAction'
            ],
            'logout' => [
                'class' => 'yii2mod\user\actions\LogoutAction'
            ],
            'signup' => [
                'class' => 'yii2mod\user\actions\SignupAction'
            ],
            'request-password-reset' => [
                'class' => 'yii2mod\user\actions\RequestPasswordResetAction'
            ],
            'password-reset' => [
                'class' => 'yii2mod\user\actions\PasswordResetAction'
            ],
        ];
    }
```
You can then access to this actions through the following URL:

1. http://localhost/site/login
2. http://localhost/site/logout
3. http://localhost/site/signup
4. http://localhost/site/request-password-reset
5. http://localhost/site/password-reset






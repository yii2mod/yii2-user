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
2. http://localhost/site/request-password-reset
3. http://localhost/site/signup
4. http://localhost/site/logout




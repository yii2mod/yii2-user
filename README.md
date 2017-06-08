User module
===========
Flexible user registration and authentication module for Yii2

[![Latest Stable Version](https://poser.pugx.org/yii2mod/yii2-user/v/stable)](https://packagist.org/packages/yii2mod/yii2-user) [![Total Downloads](https://poser.pugx.org/yii2mod/yii2-user/downloads)](https://packagist.org/packages/yii2mod/yii2-user) [![License](https://poser.pugx.org/yii2mod/yii2-user/license)](https://packagist.org/packages/yii2mod/yii2-user)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yii2mod/yii2-user/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yii2mod/yii2-user/?branch=master) [![Build Status](https://travis-ci.org/yii2mod/yii2-user.svg?branch=master)](https://travis-ci.org/yii2mod/yii2-user)

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

## Actions

This extension provides several independent action classes, which provides particular operation support:

1. **[[yii2mod\user\actions\LoginAction]]** - Logs in a user. The following additional parameters are available:
  - `view` - name of the view, which should be rendered.
  - `modelClass` - login model class name. 
  - `layout` - the name of the layout to be applied to this view.
  - `returnUrl`  - url which user should be redirected to on success.
2. **[[yii2mod\user\actions\LogoutAction]]** - Logs out the current user. The following additional parameters are available:
  - `returnUrl`  - url which user should be redirected to on success.
3. **[[yii2mod\user\actions\SignupAction]]** - Signup a user. The following additional parameters are available:
  - `view` - name of the view, which should be rendered.
  - `modelClass` - signup model class name.
  - `returnUrl` - url which user should be redirected to on success.
4. **[[yii2mod\user\actions\RequestPasswordResetAction]]** - Request password reset for a user. The following additional parameters are available:
  - `view` - name of the view, which should be rendered.
  - `modelClass` - request password model class.
  - `successMessage` - message to the user when the mail is sent successfully.
  - `errorMessage` - error message for the user when the email was not sent.
  - `returnUrl` - url which user should be redirected to on success.
5. **[[yii2mod\user\actions\PasswordResetAction]]** - Reset password for a user. The following additional parameters are available:
  - `view` - name of the view, which should be rendered.
  - `modelClass` - reset password model class.
  - `successMessage` - message to be set on success.
  - `returnUrl` - url which user should be redirected to on success.

Configuration
=============
1) If you use this extension without [base template](https://github.com/yii2mod/base), then you need execute migration by the following command:
```
php yii migrate/up --migrationPath=@vendor/yii2mod/yii2-user/migrations
```
2) You need to configure the `params` section in your project configuration:
```php
'params' => [
   'user.passwordResetTokenExpire' => 3600
]
```
3) Your need to create the UserModel class that be extends of [UserModel](https://github.com/yii2mod/yii2-user/blob/master/models/BaseUserModel.php) and configure the property `identityClass` for `user` component in your project configuration, for example:
```php
'user' => [
    'identityClass' => 'yii2mod\user\models\UserModel',
    // for update last login date for user, you can call the `afterLogin` event as follows
    'on afterLogin' => function ($event) {
        $event->identity->updateLastLogin();
    }
],
```

4) For sending emails you need to configure the `mailer` component in the configuration of your project.

5) If you don't have the `passwordResetToken.php` template file in the mail folder of your project, then you need to create it, for example:
```php
<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/password-reset', 'token' => $user->password_reset_token]);
?>

Hello <?php echo Html::encode($user->username) ?>,

Follow the link below to reset your password:

<?php echo Html::a(Html::encode($resetLink), $resetLink) ?>

```
> This template used for password reset email.

6) Add to SiteController (or configure via `$route` param in urlManager):
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

7) Also some actions send flash messages, so you should use an AlertWidget to render flash messages on your site.

Using action events
-------------------

You may use the following events:

```php
    /**
     * @return array
     */
    public function actions()
    {
        return [
            'login' => [
                'class' => 'yii2mod\user\actions\LoginAction',
                'on beforeLogin' => function ($event) {
                    // your custom code
                },
                'on afterLogin' => function ($event) {
                    // your custom code
                },
            ],
            'logout' => [
                'class' => 'yii2mod\user\actions\LogoutAction',
                'on beforeLogout' => function ($event) {
                    // your custom code
                },
                'on afterLogout' => function ($event) {
                    // your custom code
                },
            ],
            'signup' => [
                'class' => 'yii2mod\user\actions\SignupAction',
                'on beforeSignup' => function ($event) {
                    // your custom code
                },
                'on afterSignup' => function ($event) {
                    // your custom code
                },
            ],
            'request-password-reset' => [
                'class' => 'yii2mod\user\actions\RequestPasswordResetAction',
                'on beforeRequest' => function ($event) {
                    // your custom code
                },
                'on afterRequest' => function ($event) {
                    // your custom code
                },
            ],
            'password-reset' => [
                'class' => 'yii2mod\user\actions\PasswordResetAction',
                'on beforeReset' => function ($event) {
                    // your custom code
                },
                'on afterReset' => function ($event) {
                    // your custom code
                },
            ],
        ];
    }
```

# Console commands

## Setup
To enable console commands, you need to add module into console config of you app.
`/config/console.php` in yii2-app-basic template, or `/console/config/main.php` in yii2-app-advanced.

```php

    return [
        'id' => 'app-console',
        'modules' => [
            'user' => [
                'class' => 'yii2mod\user\ConsoleModule',
            ],
        ],

```

## Available console actions

- **user/create** - Creates a new user.

```sh

./yii user/create <email> <username> <password>

- email (required): string
- username (required): string
- password (required): string

```

- **user/role/assign** - Assign role to the user.

```sh

./yii user/role/assign <roleName> <email>

- roleName (required): string
- email (required): string

```

- **user/role/revoke** - Revoke role from the user.

```sh

./yii user/role/revoke <roleName> <email>

- roleName (required): string
- email (required): string

```

- **user/delete** - Deletes a user.

```sh

./yii user/delete <email>

- email (required): string

```

- **user/update-password** - Updates user's password to given.

```sh

./yii user/update-password <email> <password>

- email (required): string
- password (required): string

```

Internationalization
----------------------

All text and messages introduced in this extension are translatable under category 'yii2mod.user'.
You may use translations provided within this extension, using following application configuration:

```php
return [
    'components' => [
        'i18n' => [
            'translations' => [
                'yii2mod.user' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@yii2mod/user/messages',
                ],
                // ...
            ],
        ],
        // ...
    ],
    // ...
];
```

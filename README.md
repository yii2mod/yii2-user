User module
===========
User module
* [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yii2mod/yii2-user/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yii2mod/yii2-user/?branch=master)
* [![Build Status](https://scrutinizer-ci.com/g/yii2mod/yii2-user/badges/build.png?b=master)](https://scrutinizer-ci.com/g/yii2mod/yii2-user/build-status/master)

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

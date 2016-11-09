<?php

namespace yii2mod\user\tests;

use yii\helpers\ArrayHelper;
use Yii;
use yii2mod\user\tests\data\Controller;

/**
 * This is the base class for all yii framework unit tests.
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->mockApplication();
        $this->setupTestDbData();
        Yii::$app->mailer->fileTransportCallback = function () {
            return 'testing_message.eml';
        };
    }

    protected function tearDown()
    {
        $this->destroyApplication();
    }

    /**
     * Populates Yii::$app with a new application
     * The application will be destroyed on tearDown() automatically.
     * @param array $config The application configuration, if needed
     * @param string $appClass name of the application class to create
     */
    protected function mockApplication($config = [], $appClass = '\yii\web\Application')
    {
        new $appClass(ArrayHelper::merge([
            'id' => 'testapp',
            'basePath' => __DIR__,
            'vendorPath' => $this->getVendorPath(),
            'components' => [
                'db' => [
                    'class' => 'yii\db\Connection',
                    'dsn' => 'sqlite::memory:',
                ],
                'user' => [
                    'identityClass' => 'yii2mod\user\models\BaseUserModel',
                ],
                'request' => [
                    'hostInfo' => 'http://domain.com',
                    'scriptUrl' => 'index.php'
                ],
                'mailer' => [
                    'class' => 'yii\swiftmailer\Mailer',
                    'useFileTransport' => true,
                    'htmlLayout' => false,
                    'viewPath' => __DIR__ . '/data/mail'
                ],
                'i18n' => [
                    'translations' => [
                        'yii2mod.user' => [
                            'class' => 'yii\i18n\PhpMessageSource',
                            'basePath' => '@yii2mod/user/messages',
                        ]
                    ]
                ]
            ],
            'params' => [
                'adminEmail' => 'admin@mail.com',
                'user.passwordResetTokenExpire' => 3600
            ]
        ], $config));
    }

    /**
     * @return string vendor path
     */
    protected function getVendorPath()
    {
        return dirname(__DIR__) . '/vendor';
    }

    /**
     * Destroys application in Yii::$app by setting it to null.
     */
    protected function destroyApplication()
    {
        Yii::$app = null;
    }

    /**
     * @param array $config controller config.
     * @return Controller controller instance.
     */
    protected function createController($config = [])
    {
        return new Controller('test', Yii::$app, $config);
    }

    /**
     * Setup tables for test ActiveRecord
     */
    protected function setupTestDbData()
    {
        $db = Yii::$app->getDb();

        // Structure :

        $db->createCommand()->createTable('user', [
            'id' => 'pk',
            'username' => 'string not null unique',
            'authKey' => 'string(32) not null',
            'passwordHash' => 'string not null',
            'passwordResetToken' => 'string unique',
            'email' => 'string not null unique',
            'status' => 'integer not null default 1',
            'createdAt' => 'integer not null',
            'updatedAt' => 'integer not null',
            'lastLogin' => 'integer',
        ])->execute();

        // Data :

        $db->createCommand()->insert('user', [
            'username' => 'demo',
            'authKey' => Yii::$app->getSecurity()->generateRandomString(),
            'passwordHash' => Yii::$app->getSecurity()->generatePasswordHash('password'),
            'email' => 'demo@mail.com',
            'createdAt' => time(),
            'updatedAt' => time()
        ])->execute();
    }
}
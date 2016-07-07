<?php

namespace yii2mod\user\tests;

use yii\helpers\ArrayHelper;
use Yii;

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
                    'scriptUrl' => 'http://domain.com'
                ],
            ],
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
     * Setup tables for test ActiveRecord
     */
    protected function setupTestDbData()
    {
        $db = Yii::$app->getDb();

        // Structure :

        $db->createCommand()->createTable('User', [
            'id' => 'pk',
            'username' => 'string',
            'authKey' => 'string',
            'passwordHash' => 'string',
            'passwordResetToken' => 'string',
            'email' => 'string',
            'status' => 'integer NOT NULL DEFAULT 10',
            'createdAt' => 'integer',
            'updatedAt' => 'integer',
            'lastLogin' => 'integer',
        ])->execute();

        $db->createCommand()->createTable('UserDetails', [
            'userId' => 'integer'
        ])->execute();

        // Data :

        $db->createCommand()->insert('User', [
            'username' => 'demo',
            'authKey' => '',
            'passwordHash' => Yii::$app->getSecurity()->generatePasswordHash('password'),
            'passwordResetToken' => '',
            'email' => 'demo@mail.com',
            'status' => 1
        ])->execute();
        
        $db->createCommand()->insert('UserDetails', [
            'userId' => 1
        ])->execute();
    }
}
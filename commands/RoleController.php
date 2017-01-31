<?php

namespace yii2mod\user\commands;

use Yii;
use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\Console;
use yii\rbac\Role;
use yii2mod\user\models\UserModel;

/**
 * Class RoleController
 *
 * @package yii2mod\user\commands
 */
class RoleController extends Controller
{
    /**
     * @var \yii\rbac\ManagerInterface
     */
    private $_authManager;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->_authManager = Yii::$app->authManager;
    }

    /**
     * Assign role to the user
     *
     * @param $roleName
     * @param $email
     *
     * @return int
     *
     * @throws Exception
     */
    public function actionAssign($roleName, $email)
    {
        $user = UserModel::findByEmail($email);

        if (empty($user)) {
            throw new Exception(Yii::t('yii2mod.user', 'User is not found.'));
        }

        $role = $this->findRole($roleName);

        if (in_array($roleName, array_keys($this->_authManager->getRolesByUser($user->id)))) {
            $this->stdout(Yii::t('yii2mod.user', 'This role already assigned to this user.') . "\n", Console::FG_RED);

            return self::EXIT_CODE_NORMAL;
        }

        $this->_authManager->assign($role, $user->id);

        $this->stdout(Yii::t('yii2mod.user', 'The role has been successfully assigned to the user.') . "\n", Console::FG_GREEN);

        return self::EXIT_CODE_NORMAL;
    }

    /**
     * Revoke role from the user
     *
     * @param $roleName
     * @param $email
     *
     * @return int
     *
     * @throws Exception
     */
    public function actionRevoke($roleName, $email)
    {
        $user = UserModel::findByEmail($email);

        if (empty($user)) {
            throw new Exception(Yii::t('yii2mod.user', 'User is not found.'));
        }

        $role = $this->findRole($roleName);

        if (!in_array($roleName, array_keys($this->_authManager->getRolesByUser($user->id)))) {
            $this->stdout(Yii::t('yii2mod.user', 'This role is not assigned to this user.') . "\n", Console::FG_RED);

            return self::EXIT_CODE_NORMAL;
        }

        $this->_authManager->revoke($role, $user->id);

        $this->stdout(Yii::t('yii2mod.user', 'The role has been successfully revoked from the user.') . "\n", Console::FG_GREEN);

        return self::EXIT_CODE_NORMAL;
    }

    /**
     * Returns the named role.
     *
     * @param string $roleName
     *
     * @return Role
     *
     * @throws Exception
     */
    protected function findRole($roleName)
    {
        if (($role = $this->_authManager->getRole($roleName)) !== null) {
            return $role;
        }

        throw new Exception(Yii::t('yii2mod.user', 'The role "{roleName}" is not found.', [
            'roleName' => $roleName,
        ]));
    }
}

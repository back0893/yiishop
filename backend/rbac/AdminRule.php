<?php
namespace backend\rbac;

use yii\rbac\Item;
use yii\rbac\Rule;

class AdminRule extends Rule{
    public $name='Admin';

    /**
     * Executes the rule.
     *
     * @param string|int $user the user ID. This should be either an integer or a string representing
     * the unique identifier of a user. See [[\yii\web\User::id]].
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to [[CheckAccessInterface::checkAccess()]].
     * @return bool a value indicating whether the rule permits the auth item it is associated with.
     * 这是专为admin准备的超级权限,不需要进行验证,直接通过规则
     */
    public function execute($user, $item, $params)
    {
        return true;
    }
}
<?php

namespace app\components;

use app\models\User;
use yii\rbac\PhpManager;
use yii\web\IdentityInterface;

class RbacPhpManager extends PhpManager
{
    public function getAssignments($userId)
    {
        $identity = \yii::$app->user->identity;
        /* @var User $user */
        $user = $identity && $identity->id == $userId ?
            $identity : User::findIdentity($userId);
        
        if (!$user || !$user->enabled || $user->inTrash) {
            return [];
        }
        
        foreach ($this->assignments as $key => $assignment) {
            if ($user->role & $key) {
                return $this->assignments[$key];
            }
        }
        return [];
    }
    
}
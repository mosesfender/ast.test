<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

use app\models\User;
use yii\web\View;

/**
 * @var View   $this
 * @var User[] $model
 */

?>

<ul class="items">
    <?php foreach ($model as $user) { ?>
        <li class="user form-check form-switch">
            <input type="checkbox" class="form-check-input" role="switch"
                   name="<?= $user->formName() ?>[]"
                   id="user_check_<?= $user->id ?>"
                   value="<?= $user->id ?>"
                <?= !is_null($user->event_id) ? ' checked ' : '' ?>
                <?= !$user->enabled || $user->inTrash ? ' disabled ' : '' ?>
                   autocomplete="off">
            <label class="form-check-label" for="user_check_<?= $user->id ?>"><?= $user->fullname ?></label>
        </li>
    <?php } ?>
</ul>

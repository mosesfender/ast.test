<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\components;

use yii\grid\ActionColumn;

class UserActionColumn extends ActionColumn
{
    
    public $template = '{view} {update} {delete} {password}';
    
    public $icons
        = [
            'view'     => '<span class="si si-read"></span>',
            'update'   => '<span class="si si-write"></span>',
            'delete'   => '<span class="si si-delete"></span>',
            'password' => '<span class="si si-password"></span>',
        ];
    
    protected function initDefaultButtons()
    {
        $this->initDefaultButton('view', 'view', ['data-toggle' => 'tooltip']);
        $this->initDefaultButton('update', 'update', ['data-toggle' => 'tooltip']);
        $this->initDefaultButton('delete', 'delete', [
            'data-confirm' => \yii::t('yii', 'Are you sure you want to delete this item?'),
            'data-method'  => 'delete',
            'data-toggle'  => 'tooltip'
        ]);
        $this->initDefaultButton('password', 'password',
                                 ['title'       => \yii::t('app', 'Change password'),
                                  'data-toggle' => 'tooltip']);
    }
    
    public function renderDataCell($model, $key, $index)
    {
        return parent::renderDataCell($model, $key, $index);
    }
    
    
}

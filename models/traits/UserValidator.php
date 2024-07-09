<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\models\traits;

use yii\validators\InlineValidator;

trait UserValidator
{
    
    /**
     * Фильтр. Убирает из атрибутов имени пользователя всё ненужное и устанавливает в null пустую строку.
     *
     * @param string          $attribute
     * @param array|null      $params
     * @param InlineValidator $validator
     * @param string          $current
     *
     * @return bool
     */
    public function validUserName(string $attribute, ?array $params, InlineValidator $validator, string $current): bool
    {
        $this->{$attribute} = trim(preg_replace('/[^a-zA-Zа-яА-ЯёЁ]/u', '', $current));
        if(empty($this->{$attribute}))
            $this->{$attribute} = null;
        return true;
    }
    
    /**
     * @param string               $attribute
     * @param array|null           $params
     * @param InlineValidator|null $validator
     * @param mixed|null           $current
     */
    public function validateFlags(
        string $attribute, array $params = null, InlineValidator $validator = null, mixed $current = null)
    {
        $_flags = 0;
        try {
            $current = \yii::$app->request->post('User', 0)['flags'];
        } catch (\Exception $e) {
            $current = 0;
        }
        $current = is_array($current) ? $current : [$current];
        foreach ($current as $flg)
            $_flags |= $flg;
        
        if ($_flags & static::FLG_DELETED) {
            $_flags = $_flags & ~static::FLG_ENABLED;
        }
        
        $this->flags = $_flags;
        
        if($this->role & static::ROLE_SUPER && (!$this->enabled || $this->inTrash)){
            $this->addError($attribute, \yii::t('app', 'Administrator cannot be disabled or deleted'));
        }
    }
}


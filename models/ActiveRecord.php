<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\models;

use yii\base\InvalidConfigException;

class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * Флаг, определяющий как именовать поля в форме.
     * Если он включен, поля будут называться с именем модели. @example ModelName[field_name]
     * Иначе чистое имя поля.
     *
     * @see \app\models\ActiveRecord::formName Перекрытие метода, возвращающего имя формы модели.
     * @var bool|null
     */
    public ?bool $scopeFormName = true;
    
    /**
     * Перекрытие метода, возвращающего имя формы модели.
     * Если в модели флаг \app\models\ActiveRecord::$scopeFormName установлен в false,
     * то имена полей в форме будут без имени модели.
     *
     * @return string
     * @throws InvalidConfigException
     * @see \app\models\ActiveRecord::$scopeFormName
     */
    public function formName()
    {
        if ($this->scopeFormName) {
            return parent::formName();
        }
        return '';
    }
}


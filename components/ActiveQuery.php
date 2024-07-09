<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\components;

class ActiveQuery extends \yii\db\ActiveQuery
{
    private ?string $_alias = '';
    
    public function getAlias()
    {
        if (!$this->_alias) {
            $this->_alias = $this->getTableNameAndAlias()[1];
        }
        return $this->_alias;
    }
}



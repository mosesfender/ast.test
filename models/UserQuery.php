<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\models;

use app\components\ActiveQuery;
use yii\db\Expression;

class UserQuery extends ActiveQuery
{
    /**
     * Добавляет в запрос условие поиска по идентификатору.
     *
     * @param int|array $id
     *
     * @return $this
     */
    public function byID(int|array $id): static
    {
        $this->andWhere(["{$this->getAlias()}.id" => $id]);
        return $this;
    }
    
    /**
     * Добавляет в запрос выбор пользователей в корзине, или не в корзине.
     *
     * @param bool $val
     *
     * @return $this
     */
    public function trash(bool $val = false): static
    {
        $exp = ['&', "{$this->getAlias()}.flags", User::FLG_DELETED];
        $this->andWhere($val ? $exp : ['NOT', $exp]);
        return $this;
    }
    
    /**
     * Выбор пользователей по ролям в поле role.
     * Роли являются битами, поэтому поиск можно по нескольким ролям, например $role может быть 1 | 4 | 2
     *
     * @param int $role
     *
     * @return $this
     */
    public function role(int $role): static
    {
        $this->andWhere(['&', "{$this->getAlias()}.role", $role]);
        return $this;
    }
    
    /**
     * Добавляет в запрос подзапрос, конкатенирующий три поля "фамилия", "имя", "отчество"
     * в строки shortname - "Иванов И. И." или "Иванов И." если отчество не указано,
     * fullname - "Иванов Иван Иванович" или "Иванов Иван" если отчество не указано.
     *
     * @return $this
     */
    public function nameSpelling(): static
    {
        $this->addSelect(["{$this->getAlias()}.*", 'user_ns.shortname', 'user_ns.fullname']);
        $this->innerJoin(['user_ns' => (new UserQuery($this->modelClass))
            ->select(
                [
                    'id',
                    'shortname' => 'CONCAT(second_name, " ", substr(first_name, 1, 1), ". ", COALESCE(CONCAT(substr(sur_name, 1, 1), "."), ""))',
                    'fullname'  => 'CONCAT(second_name, " ", first_name, " ", COALESCE(sur_name, ""))'
                ]
            )],
                         ['user.id' => new Expression('user_ns.id')]
        );
        return $this;
    }
    
    /**
     * Сахар в оптимизацию запросов.
     * Добавляет в запрос левое соединение, выбирающее признак участия пользователя в мероприятии $id, если таковое
     * имеется. У пользователей без такового буде null. Признак назначается полю User::event_id.
     *
     * @param int|null $id ID мероприятия
     *
     * @return $this
     */
    public function event(?int $id): static
    {
        return $this->leftJoin(['ue' => UserEvent::tableName()], [
            'AND',
            ["{$this->getAlias()}.id" => new Expression('ue.user_id')],
            ['ue.event_id' => $id]
        ]);
    }
}

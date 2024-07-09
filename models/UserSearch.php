<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\models;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class UserSearch extends User
{
    public ?string $usernameOrEmail = '';
    public ?int $inTrash = 0;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usernameOrEmail', 'phone', 'id', 'inTrash'], 'safe'],
            ['role', function ($attribute, $params) {
                $tmp = 0;
                if (is_array($this->$attribute) && !empty($this->$attribute)) {
                    foreach ($this->$attribute as $role) {
                        $tmp |= (int)$role;
                    }
                } else {
                    foreach (static::roleList() as $key => $role) {
                        $tmp |= $key;
                    }
                }
                $this->$attribute = $tmp;
                return true;
            }, 'skipOnEmpty' => false]
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }
    
    /**
     * @param array $params
     *
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function search(array $params)
    {
        $query = self::find()
            ->nameSpelling();
        if ($params['trash']) $query->trash(true);
       
        $this->load($params);
        $this->validate();
        
        if($this->inTrash){
            $query->andFilterWhere(['&', 'flags', User::FLG_DELETED]);
        } else {
            $query->andFilterWhere(['NOT', ['&', 'flags', User::FLG_DELETED]]);
        }
        $query->andFilterWhere(['&', 'role', $this->role]);
        $query->andFilterWhere(
            [
                "{$query->getAlias()}.id" => $this->id,
            ]
        );
        $query->andFilterWhere(
            ['OR',
             ['RLIKE', 'username', $this->usernameOrEmail],
             ['RLIKE', 'email', $this->usernameOrEmail],
             ['RLIKE', 'fullname', $this->usernameOrEmail],
            ]
        );
        
        $dataProvider = new ActiveDataProvider(
            [
                'query'      => $query,
                'sort'       => [
                    'attributes' => [
                        'fio' => [
                            'asc'     => ['second_name' => SORT_ASC, 'first_name' => SORT_ASC, 'sur_name' => SORT_ASC],
                            'desc'    => ['second_name' => SORT_DESC, 'first_name' => SORT_DESC, 'sur_name' => SORT_DESC],
                            'default' => SORT_ASC,
                            'label'   => \yii::t('app', 'Full name')
                        ],
                        'username',
                        'email',
                    ],
                ],
                'pagination' => [
                    'pageSize' => $_COOKIE['ast_pagesize'] ?? 5,
                ]
            ]
        );
        
        return $dataProvider;
    }
}

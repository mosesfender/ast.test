<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Event;
use yii\db\Expression;

/**
 * EventSearch represents the model behind the search form of `app\models\Event`.
 */
class EventSearch extends Event
{
    
    public ?int $inTrash = 0;
    public ?string $beginTimePast = '';
    public ?string $beginTimeFuture = '';
    public ?string $beginTimeStart = '';
    public ?string $beginTimeEnd = '';
    public array|string|null $authors = [];
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'flags', 'inTrash'], 'integer'],
            [['title', 'description', 'beginTimePast', 'beginTimeFuture', 'beginTimeStart', 'beginTimeEnd',
                 'authors'], 'safe'],
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
     * Creates data provider instance with search query applied
     *
     * @param array|null $params
     *
     * @return ActiveDataProvider
     */
    public function search(?array $params): ActiveDataProvider
    {
        $query = static::find();
        
        $this->load($params);
        $this->validate();

        if (!empty($this->beginTimePast) && !empty($this->beginTimeFuture)) {
            $query->andFilterWhere(['OR',
                                    ['<=', 'begin_time', new Expression('NOW()')],
                                    ['>=', 'begin_time', new Expression('NOW()')],
                                   ]);
        } elseif (!empty($this->beginTimePast) && empty($this->beginTimeFuture)) {
            $query->andFilterWhere(['<=', 'begin_time', new Expression('NOW()')]);
        } elseif (empty($this->beginTimePast) && !empty($this->beginTimeFuture)) {
            $query->andFilterWhere(['>=', 'begin_time', new Expression('NOW()')]);
        }
        
        if (!empty($this->beginTimeStart))
            $query->andFilterWhere(['>=', 'begin_time', $this->beginTimeStart]);
        if (!empty($this->beginTimeEnd))
            $query->andFilterWhere(['<=', 'begin_time', $this->beginTimeEnd]);
        
        $query->andFilterWhere(['RLIKE', 'title', $this->title])
            ->andFilterWhere(['RLIKE', 'description', $this->description]);
        
        $query->andFilterWhere(['created_by' => $this->authors]);
        
        if ($this->inTrash) {
            $query->andWhere(['&', 'flags', User::FLG_DELETED]);
        } else {
            $query->andWhere(['NOT', ['&', 'flags', User::FLG_DELETED]]);
        }
    
        return new ActiveDataProvider(
            [
                'query'      => $query,
                'sort'       => [
                    'attributes' => [
                        'id',
                        'title',
                        'begin_time'
                    ]
                ],
                'pagination' => [
                    'pageSize' => $_COOKIE['ast_pagesize'] ?? 5,
                ]
            ]
        );
    }
}

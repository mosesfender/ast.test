<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "event".
 *
 * @property int         $id
 * @property string      $title       Название мероприятия
 * @property string|null $description Описание мероприятия
 * @property string|null $begin_time  Время начала мероприятия
 * @property int|null    $flags       Битовые признаки мероприятия. См константы app\models\Event::ME_*
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int         $created_by
 *
 * @property UserEvent[] $userEvents
 * @property User[]      $users
 * @property User        $creator
 *
 * @property bool        $enabled
 * @property bool        inTrash
 */
class Event extends ActiveRecord
{
    const ME_ENABLED = 0x1;    // Флаг мероприятие доступно
    const ME_DELETED = 0x1000; // Флаг мероприятие в корзине
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title', 'description'], 'string'],
            [['begin_time'], 'safe'],
            [['flags', 'created_by'], 'integer'],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('app', 'ID'),
            'title'       => Yii::t('app', 'Event title'),
            'description' => Yii::t('app', 'Event description'),
            'begin_time'  => Yii::t('app', 'Begin time'),
            'flags'       => Yii::t('app', 'Flags'),
            'created_at'  => Yii::t('app', 'Created At'),
            'created_by'  => Yii::t('app', 'Created By'),
            'updated_at'  => Yii::t('app', 'Updated At'),
        ];
    }
    
    /**
     * Gets query for [[UserEvents]].
     *
     * @return ActiveQuery
     */
    public function getUserEvents()
    {
        return $this->hasMany(UserEvent::class, ['event_id' => 'id']);
    }
    
    /**
     * Gets query for [[Users]].
     *
     * @return ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->orderBy('fullname ASC')
            ->nameSpelling()
            ->via('userEvents');
    }
    
    public function getCreator(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'created_by'])->nameSpelling();
    }
    
    public function setInTrash(int $val)
    {
        $this->flags = $val ? $this->flags | static::ME_DELETED : $this->flags & ~static::ME_DELETED;
    }
    
    public function getInTrash(): int
    {
        return $this->flags & static::ME_DELETED;
    }
    
    public function setEnabled(int $val)
    {
        $this->flags = $val ? $this->flags | static::ME_ENABLED : $this->flags & ~static::ME_ENABLED;
    }
    
    public function getEnabled(): int
    {
        return $this->flags & static::ME_ENABLED;
    }
    
    /**
     * Список флагов данной конструкции
     *
     * @param int|null $flg
     *
     * @return array|string
     */
    public static function flagList(int $flg = null): array|string
    {
        if ($flg === 0) {
            return \yii::t('app', 'Disabled');
        }
        
        $result = [
            static::ME_ENABLED => \yii::t('app', 'Enabled'),
            static::ME_DELETED => \yii::t('app', 'Deleted'),
        ];
        if ($flg) {
            return $result[$flg];
        }
        return $result;
    }
}

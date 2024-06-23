<?php

namespace app\models;

use Yii;
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
 *
 * @property UserEvent[] $userEvents
 * @property User[]      $users
 */
class Event extends ActiveRecord
{
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
            [['begin_time', 'created_at', 'updated_at'], 'safe'],
            [['flags'], 'integer'],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('app', 'ID'),
            'title'       => Yii::t('app', 'Название мероприятия'),
            'description' => Yii::t('app', 'Описание мероприятия'),
            'begin_time'  => Yii::t('app', 'Время начала мероприятия'),
            'flags'       => Yii::t('app', 'Битовые признаки мероприятия. См константы app\\models\\Event::ME_*'),
            'created_at'  => Yii::t('app', 'Created At'),
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
        return $this->hasMany(User::class, ['id' => 'user_id'])->via('userEvents');
    }
}

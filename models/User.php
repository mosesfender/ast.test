<?php

namespace app\models;

use yii\base\Exception;
use yii\db\ActiveQuery;
use app\models\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int         $id
 * @property string      $username
 * @property string      $password
 * @property string|null $authKey
 * @property string|null $accessToken
 * @property int         $role
 * @property int         $flags
 *
 * @property string      $first_name
 * @property string      $second_name
 * @property string|null $sur_name
 * @property string      $phone
 * @property string      $email
 *
 * @property UserEvent[] $userEvents
 * @property Event[]     $events
 */
class User extends ActiveRecord implements IdentityInterface
{
    /* Роли пользователей в данной конструкции */
    const ROLE_SUPER           = 0x1;
    const ROLE_EVENT_ORGANIZER = 0x2;
    
    /* Флаги пользователя */
    const FLG_ENABLED = 0x1; // Пользователь может авторизоваться
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'email', 'first_name', 'second_name'], 'required'],
            [['username'], 'string', 'max' => 32],
            [['password'], 'string'],
            [['first_name', 'second_name'], 'string'],
            [['sur_name'], 'string'],
            [['role', 'flags'], 'integer'],
            ['email', 'email'],
            ['email', 'unique'],
            ['phone', 'string', 'max' => 12],
        ];
    }
    
    static function roles()
    {
        return [
            static::ROLE_SUPER           => \yii::t('app', 'Админ'),
            static::ROLE_EVENT_ORGANIZER => \yii::t('app', 'Организатор мероприятия'),
        ];
    }
    
    /**
     * Поиск пользователя по username.
     *
     * @param string $username // Должен быть уже отфильтрованным от грязи в LoginForm
     *
     * @return User|null
     */
    public static function findByUsername(string $username): ?User
    {
        return static::findOne(['username' => $username]);
    }
    
    public function validatePassword($password)
    {
        return \yii::$app->getSecurity()->validatePassword($password, $this->password);
    }
    
    /**
     * Gets query for [[UserEvents]].
     *
     * @return ActiveQuery
     */
    public function getUserEvents()
    {
        return $this->hasMany(UserEvent::class, ['user_id' => 'id']);
    }
    
    /**
     * Gets query for [[Events]].
     *
     * @return ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::class, ['id' => 'event_id'])->via('userEvent');
    }
    
    /** Implementation IdentityInterface methods */
    
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }
    
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['accessToken' => $token]);
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getAuthKey()
    {
        return $this->authKey;
    }
    
    public function validateAuthKey($authKey)
    {
        return $this->authKey = $authKey;
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                try {
                    $this->password = \yii::$app->getSecurity()->generatePasswordHash($this->password);
                    $this->accessToken = \yii::$app->getSecurity()->generateRandomString(32);
                    $this->authKey = \yii::$app->getSecurity()->generateRandomString(32);
                } catch (Exception $e) {
                }
            }
            return true;
        }
        return false;
    }
}

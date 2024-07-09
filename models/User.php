<?php
/*
 * Copyright (c) Sergey Siunov 2024
 * @email sergey@siunov.ru
 */

namespace app\models;

use yii\web\IdentityInterface;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\validators\InlineValidator;
use app\models\traits\UserValidator;
use yii\base\Exception;
use yii\base\InvalidConfigException;

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
 *
 * @property bool        $enabled
 * @property bool        inTrash
 */
class User extends ActiveRecord implements IdentityInterface
{
    use UserValidator;
    
    /* Роли пользователей в данной конструкции */
    const ROLE_SUPER           = 0x1;
    const ROLE_EVENT_ORGANIZER = 0x2;
    const ROLE_SIMPLE_USER     = 0x4;
    
    /* Флаги пользователя */
    const FLG_ENABLED = 0x1;    // Пользователь может авторизоваться
    const FLG_DELETED = 0x1000; // Пользователь удалён
    
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    
    public ?string $shortname;
    public ?string $fullname;
    public ?int $event_id;
    
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
            [['username', 'email', 'first_name', 'second_name'], 'required'],
            ['username', 'unique'],
            ['email', 'unique'],
            ['password', 'required'],
            [['first_name', 'second_name'], 'validUserName', 'skipOnError' => false, 'skipOnEmpty' => false],
            ['sur_name', 'validUserName', 'skipOnError' => false, 'skipOnEmpty' => true],
            [['password'], 'string', 'min' => 6, 'max' => 32],
            [['username'], 'string', 'min' => 3, 'max' => 32],
            [['first_name', 'second_name'], 'string', 'min' => 1, 'skipOnEmpty' => false],
            [['role'], 'integer'],
            ['email', 'email'],
            ['email', 'unique'],
            ['phone', 'string', 'max' => 12],
            ['flags', 'validateFlags'],
        ];
    }
    
   
    public function attributeLabels()
    {
        return [
            'username'    => \yii::t('app', 'Username'),
            'password'    => \yii::t('app', 'Password'),
            'first_name'  => \yii::t('app', 'First Name'),
            'second_name' => \yii::t('app', 'Second Name'),
            'sur_name'    => \yii::t('app', 'Sur Name'),
            'phone'       => \yii::t('app', 'Phone'),
            'email'       => \yii::t('app', 'Email'),
            'shortname'   => \yii::t('app', 'Short name'),
            'fullname'    => \yii::t('app', 'Full name'),
        ];
    }
    
    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(),
                                  [
                                      static::SCENARIO_CREATE => [
                                          'username', 'password', 'email', 'first_name',
                                          'second_name', 'sur_name', 'phone', 'email', 'role', 'flags'
                                      ],
                                      static::SCENARIO_UPDATE => [
                                          'username', 'email', 'first_name',
                                          'second_name', 'sur_name', 'phone', 'email', 'role', 'flags'
                                      ],
                                  ]
        );
    }
    
    static function roles()
    {
        return [
            static::ROLE_SUPER           => \yii::t('app', 'Super'),
            static::ROLE_EVENT_ORGANIZER => \yii::t('app', 'Event organizer'),
            static::ROLE_SIMPLE_USER     => \yii::t('app', 'Simple user'),
        ];
    }
    
    /**
     * @return UserQuery|object
     * @throws InvalidConfigException
     */
    public static function find()
    {
        return \yii::createObject(UserQuery::class, [get_called_class()]);
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
    
    /**
     * Список ролей данной конструкции
     *
     * @param int|null $role
     *
     * @return array|string
     */
    public static function roleList(int $role = null): array|string
    {
        $result = [
            static::ROLE_SUPER           => \yii::t('app', 'Super'),
            static::ROLE_EVENT_ORGANIZER => \yii::t('app', 'Event organizer'),
            static::ROLE_SIMPLE_USER     => \yii::t('app', 'Simple user'),
        ];
        if ($role) {
            return $result[$role];
        }
        return $result;
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
            static::FLG_ENABLED => \yii::t('app', 'Enabled'),
            static::FLG_DELETED => \yii::t('app', 'Deleted'),
        ];
        if ($flg) {
            return $result[$flg];
        }
        return $result;
    }
    
    public function setInTrash(int $val)
    {
        $this->flags = $val ? $this->flags | static::FLG_DELETED : $this->flags & ~static::FLG_DELETED;
    }
    
    public function getInTrash(): int
    {
        return $this->flags & static::FLG_DELETED;
    }
    
    public function setEnabled(int $val)
    {
        $this->flags = $val ? $this->flags | static::FLG_ENABLED : $this->flags & ~static::FLG_ENABLED;
    }
    
    public function getEnabled(): int
    {
        return $this->flags & static::FLG_ENABLED;
    }
}

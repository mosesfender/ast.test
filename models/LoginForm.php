<?php

namespace app\models;

use Yii;
use app\components\ModelException;
use yii\base\Model;
use yii\web\IdentityInterface;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class LoginForm extends Model
{
    const SCENARIO_SIGN_IN = 'signin'; // Сценарий входа
    const SCENARIO_SIGN_UP = 'signup'; // Сценарий регистрации
    
    public string $username   = '';
    public string $password   = '';
    public bool   $rememberMe = true;
    
    private ?IdentityInterface $_user = null;
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username', 'password'], 'trim'],
            [['username', 'password'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            
            ['username', 'unique',
             'targetAttribute' => 'username',
             'targetClass'     => User::class,
             'on'              => self::SCENARIO_SIGN_UP,
             'message'         => \yii::t('app', 'This username has already been taken.')],
            
            ['username', 'exist',
             'targetAttribute' => 'username',
             'targetClass'     => User::class,
             'on'              => self::SCENARIO_SIGN_IN,
             'message'         => \yii::t('app', 'No user with this name found.')],
            
            ['password', 'validatePassword'],
            ['rememberMe', 'boolean'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'username'   => \yii::t('app', 'Username'),
            'password'   => \yii::t('app', 'Password'),
            'rememberMe' => \yii::t('app', 'Remember me'),
        ];
    }
    
    public function init()
    {
        parent::init();
        $this->_user = null;
    }
    
    
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array  $params    the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, \yii::t('app', 'Incorrect password.'));
            }
        }
    }
    
    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     * @throws ModelException
     */
    public function login()
    {
        $this->scenario = self::SCENARIO_SIGN_IN;
        if ($isValid = $this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            throw ModelException::create('Validate error', $this->errors);
        }
    }
    
    /**
     * Finds user by [[username]]
     *
     * @return IdentityInterface|null
     */
    public function getUser(): ?IdentityInterface
    {
        if (is_null($this->_user)) {
            $this->_user = User::findByUsername($this->username);
        }
        
        return $this->_user;
    }
}

<?php

use app\models\User;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m240619_153002_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     * @throws \yii\db\Exception|\yii\base\Exception
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id'          => $this->primaryKey(),
            'username'    => $this->string(32)->notNull(),
            'password'    => $this->text()->notNull(),
            'email'       => $this->text()->notNull(),
            'phone'       => $this->text()->null(),
            'second_name' => $this->text()->notNull(),
            'first_name'  => $this->text()->notNull(),
            'sur_name'    => $this->text()->null(),
            'authKey'     => $this->text()->null(),
            'accessToken' => $this->text()->null(),
            'role'        => $this->integer(11)
                ->defaultValue(0)
                ->comment('Битовые флаги роли пользователя. См константы app\models\User::ROLE_*'),
            'flags'       => $this->integer(11)
                ->defaultValue(0)
                ->comment('Битовые флаги пользователя. См константы app\models\User::FLG_*'),
            'created_at'  => 'DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at'  => 'DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP'
        ]);
        
        /* Сразу создадим суперадмина -u admin -p 123456 */
        $model = (new User([
                               'username'    => 'admin',
                               'password'    => \yii::$app->getSecurity()->generatePasswordHash('123456'),
                               'email'       => 'sergey@siunov.ru',
                               'phone'       => '+79776348536',
                               'second_name' => 'Siunov',
                               'first_name'  => 'Sergey',
                               'sur_name'    => '',
                               'authKey'     => \Yii::$app->getSecurity()->generateRandomString(),
                               'role'        => User::ROLE_SUPER,
                               'flags'       => User::FLG_ENABLED
                           ]));
        
        if (!$model->save(false)) {
            echo "\nПри создании пользователя произошли ошибки: \n";
            foreach ($model->errors as $key => $error) {
                $error = implode("\n\t", $error);
                echo "{$key}: {$error}\n";
            }
            return false;
        }
    
        $this->addForeignKey('FK_event_created_by', '{{%event%}}', 'created_by',
                             'user', 'id');
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('FK_event_created_by', '{{%event%}}');
        $this->dropTable('{{%user}}');
    }
}

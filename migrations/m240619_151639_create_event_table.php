<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%event}}`.
 */
class m240619_151639_create_event_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%event}}', [
            'id'          => $this->primaryKey(),
            'title'       => $this->text()->notNull()->comment('Название мероприятия'),
            'description' => $this->text()->null()->comment('Описание мероприятия'),
            'begin_time'  => $this->dateTime()->null()->comment('Время начала мероприятия'),
            'flags'       => $this->integer(11)
                ->defaultValue(0)
                ->comment('Битовые признаки мероприятия. См константы app\models\Event::ME_*'),
            'created_by'  => $this->integer(11)->comment('Пользователь, создавший мероприятие. user.id'),
            'created_at'  => 'DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at'  => 'DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP'
        ]);
   
    }
    
    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%event}}');
    }
}

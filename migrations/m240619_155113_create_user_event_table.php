<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_event}}`.
 */
class m240619_155113_create_user_event_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_event}}', [
            'user_id'  => $this->integer()->notNull()->comment('user.id'),
            'event_id' => $this->integer()->notNull()->comment('event.id'),
        ]);
        
        $this->addForeignKey('FK_user_event_user_id', '{{user_event}}', 'user_id',
                             '{{user}}', 'id', 'CASCADE');
        $this->addForeignKey('FK_user_event_event_id', '{{user_event}}', 'event_id',
                             '{{event}}', 'id', 'CASCADE');
        $this->addPrimaryKey('PK_user_event', '{{user_event}}', ['user_id', 'event_id']);
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('FK_user_event_user_id', '{{user_event}}');
        $this->dropForeignKey('FK_user_event_event_id', '{{user_event}}');
        $this->dropPrimaryKey('PK_user_event', '{{user_event}}');
        $this->dropTable('{{%user_event}}');
    }
}

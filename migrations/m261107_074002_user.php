<?php

use yii\db\Migration;

/**
 * Class m261107_074002_user
 */
class m261107_074002_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(255)->notNull(),
            'mail' => $this->string(255)->notNull(),
            'password' => $this->string(255)->notNull(),
            'fullname' => $this->string(255)->notNull(),
            'phone' => $this->string(255)->notNull(),
            'role' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m261107_074002_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m261107_074002_user cannot be reverted.\n";

        return false;
    }
    */
}

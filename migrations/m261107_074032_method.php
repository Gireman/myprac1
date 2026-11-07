<?php

use yii\db\Migration;

/**
 * Class m261107_074032_method
 */
class m261107_074032_method extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%method}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m261107_074032_method cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m261107_074032_method cannot be reverted.\n";

        return false;
    }
    */
}

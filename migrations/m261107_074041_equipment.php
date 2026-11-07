<?php

use yii\db\Migration;

/**
 * Class m261107_074041_equipment
 */
class m261107_074041_equipment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%equipment}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m261107_074041_equipment cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m261107_074041_equipment cannot be reverted.\n";

        return false;
    }
    */
}

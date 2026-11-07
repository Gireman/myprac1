<?php

use yii\db\Migration;

/**
 * Class m261107_074121_recipe_step
 */
class m261107_074121_recipe_step extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%recipe_step}}', [
            'id' => $this->primaryKey(),
            'id_recipe' => $this->integer(),
            'position' => $this->integer(),
            'title' => $this->string(255)->notNull(),
            'text' => $this->string(255)->notNull(),
        ]);

        $this->addForeignKey(
            'recipe_step_ibfk_1',
            '{{%recipe_step}}', 'id_recipe',
            '{{%recipe}}', 'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m261107_074121_recipe_step cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m261107_074121_recipe_step cannot be reverted.\n";

        return false;
    }
    */
}

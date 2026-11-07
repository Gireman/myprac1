<?php

use yii\db\Migration;

/**
 * Class m261107_074116_recipe_note
 */
class m261107_074116_recipe_note extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%recipe_note}}', [
            'id' => $this->primaryKey(),
            'id_recipe' => $this->integer(),
            'position' => $this->integer(),
            'id_note' => $this->integer(),
        ]);

        $this->addForeignKey(
            'recipe_note_ibfk_1',
            '{{%recipe_note}}', 'id_recipe',
            '{{%recipe}}', 'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'recipe_note_ibfk_2',
            '{{%recipe_note}}', 'id_note',
            '{{%note}}', 'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m261107_074116_recipe_note cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m261107_074116_recipe_note cannot be reverted.\n";

        return false;
    }
    */
}

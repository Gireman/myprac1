<?php

use yii\db\Migration;

/**
 * Class m261107_074109_recipe_equipment
 */
class m261107_074109_recipe_equipment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%recipe_equipment}}', [
            'id' => $this->primaryKey(),
            'id_recipe' => $this->integer(),
            'position' => $this->integer(),
            'id_equipment' => $this->integer(),
        ]);

        $this->addForeignKey(
            'recipe_equipment_ibfk_1',
            '{{%recipe_equipment}}', 'id_recipe',
            '{{%recipe}}', 'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'recipe_equipment_ibfk_2',
            '{{%recipe_equipment}}', 'id_equipment',
            '{{%equipment}}', 'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m261107_074109_recipe_equipment cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m261107_074109_recipe_equipment cannot be reverted.\n";

        return false;
    }
    */
}

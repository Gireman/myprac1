<?php

use yii\db\Migration;

/**
 * Class m261107_074101_recipe
 */
class m261107_074101_recipe extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%recipe}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'id_method' => $this->integer(),
            'level' => "ENUM('Легко','Средне','Точно') NOT NULL",
            'roast' => $this->string(255)->notNull(),
            'description' => $this->string(255)->notNull(),
            'card_minutes' => $this->integer(),
            'img' => $this->string(255)->notNull(),
            'img_alt' => $this->string(255)->notNull(),
            'pill_color' => $this->string(255)->notNull(),
            'fact_coffee' => $this->string(255)->notNull(),
            'fact_water' => $this->string(255)->notNull(),
            'fact_time' => $this->string(255)->notNull(),
            'tip' => $this->string(255)->notNull(),
            'taste_acidity' => $this->integer(),
            'taste_body' => $this->integer(),
            'taste_sweetness' => $this->integer(),
            'has_milk' => $this->integer(),
            'taste_minutes' => $this->integer(),
        ]);

        $this->addForeignKey(
            'recipe_ibfk_1',
            '{{%recipe}}', 'id_method',
            '{{%method}}', 'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m261107_074101_recipe cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m261107_074101_recipe cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Migration;

/**
 * Class m261107_074056_favourite
 */
class m261107_074056_favourite extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%favourite}}', [
            'id' => $this->primaryKey(),
            'id_user' => $this->integer(),
            'id_recipe' => $this->integer(),
        ]);

        $this->addForeignKey(
            'favourite_ibfk_1',
            '{{%favourite}}', 'id_user',
            '{{%user}}', 'id',
            'RESTRICT'
        );

        $this->addForeignKey(
            'favourite_ibfk_2',
            '{{%favourite}}', 'id_recipe',
            '{{%recipe}}', 'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m261107_074056_favourite cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m261107_074056_favourite cannot be reverted.\n";

        return false;
    }
    */
}

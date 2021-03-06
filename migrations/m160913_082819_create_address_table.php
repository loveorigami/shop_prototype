<?php

use yii\db\Migration;

/**
 * Handles the creation for table `address`.
 */
class m160913_082819_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(5)->unsigned()->notNull(),
            'address'=>$this->string(500)->notNull()->unique(),
        ], 'ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}

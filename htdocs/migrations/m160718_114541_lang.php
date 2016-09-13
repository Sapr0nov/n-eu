<?php

use yii\db\Migration;

class m160718_114541_lang extends Migration
{
    public function up()
    {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
    }

    $this->createTable('vest_lang', [
        'id' => $this->primaryKey(),
        'url' => $this->string()->notNull(),
        'local' => $this->string()->notNull(),
        'name' => $this->string()->notNull(),
        'default' => $this->smallInteger()->notNull()->defaultValue(0),
        'date_update' => $this->integer()->notNull(),
        'date_create' => $this->integer()->notNull(),
    ], $tableOptions);

    $this->batchInsert('vest_lang', ['url', 'local', 'name', 'default', 'date_update', 'date_create'], [
        ['en', 'en-EN', 'English', 0, time(), time()],
        ['ru', 'ru-RU', 'Русский', 1, time(), time()],
    ]);
}

    public function down()
    {
         $this->dropTable('{{%lang}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}

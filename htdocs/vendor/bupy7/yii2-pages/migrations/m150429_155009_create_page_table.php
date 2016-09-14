<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * @author Vasilij Belosludcev http://mihaly4.ru
 * @since 1.0.0
 */
class m150429_155009_create_page_table extends Migration
{
    
    private $_tableName;
    
    
    public function up()
    {
        $this->_tableName = 'vest_pages';
        $this->createTable(
            $this->_tableName,
            [
                'id' => $this->primaryKey(),
                'title' => $this->string()->notNull(),
                'alias' => $this->string()->notNull(),
                'published' => 'boolean NOT NULL DEFAULT 1',
                'content' => $this->text(),
                'title_browser' => $this->string(),
                'meta_keywords' => $this->string()->notNull(200),
                'meta_description'  => $this->string()->notNull(160),
                'created_at' => "TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00'",
                'updated_at' => "TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00'",
            ]
        );
        $this->createIndex('alias', $this->_tableName, ['alias'], true);
        $this->createIndex('alias_and_published', $this->_tableName, ['alias', 'published']);
    }

    public function down()
    {
        $this->dropTable($this->_tableName);
    }
}

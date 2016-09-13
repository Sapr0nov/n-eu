<?php
use yii\db\Migration;

class m160714_150750_create_tables extends Migration
{
  public function up()
	{
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('vest_user',[
        'id' => $this->primaryKey(),
        'username' => $this->string()->notNull(),
        'password' => $this->string()->notNull(),
        'auth_key' => $this->string()->notNull(),
        'token' => $this->string()->notNull(),
        'email' => $this->string()->notNull(),
    ], $tableOptions);
	$this->createIndex('username', 'vest_user', 'username', true);
    $this->execute($this->addUserSql());
    }

    public function down()
    {
        echo "m160714_150750_create_tables cannot be reverted.\n";

        return false;
    }
	private function addUserSql()
	{
		$password = Yii::$app->security->generatePasswordHash('admin');
		$auth_key = Yii::$app->security->generateRandomString();
		$token = Yii::$app->security->generateRandomString() . '_' . time();
		return "INSERT INTO `vest_user` (`username`, `email`, `password`, `auth_key`, `token`) VALUES ('admin', 'admin@vestnik20.ru', '$password', '$auth_key', '$token')";
	}
    public function safeUp()
    {

    }


 
	public function safeDown()
	{
    }
}
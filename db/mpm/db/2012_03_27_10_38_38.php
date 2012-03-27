<?php

class Migration_2012_03_27_10_38_38 extends MpmMigration
{

	public function up(PDO &$pdo)
	{
		$pdo->query("ALTER TABLE `Groups` ADD COLUMN `last_user_id` int NULL AFTER `name`;");
	}

	public function down(PDO &$pdo)
	{
		$pdo->query("ALTER TABLE `Groups` DROP COLUMN `last_user_id`;");
	}

}

?>
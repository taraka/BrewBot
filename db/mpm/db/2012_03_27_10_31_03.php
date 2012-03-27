<?php

class Migration_2012_03_27_10_31_03 extends MpmMigration
{

	public function up(PDO &$pdo)
	{
		$pdo->query("ALTER TABLE `GroupUsers` ADD COLUMN `ordinal` int NULL DEFAULT 0 AFTER `user_id`;");
	}

	public function down(PDO &$pdo)
	{
		$pdo->query("ALTER TABLE `GroupUsers` Drop COLUMN `ordinal`;");
	}

}

?>
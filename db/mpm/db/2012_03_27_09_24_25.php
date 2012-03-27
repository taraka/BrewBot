<?php

class Migration_2012_03_27_09_24_25 extends MpmMigration
{

	public function up(PDO &$pdo)
	{
		$pdo->query("ALTER TABLE `GroupTimeslots` ADD INDEX `timeslot`(timeslot);");
	}

	public function down(PDO &$pdo)
	{
		$pdo->query("ALTER TABLE `GroupTimeslots` DROP INDEX `timeslot`;");
	}

}

?>
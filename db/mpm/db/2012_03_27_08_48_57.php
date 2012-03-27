<?php

class Migration_2012_03_27_08_48_57 extends MpmMigration
{

	public function up(PDO &$pdo)
	{
		$pdo->query("CREATE TABLE `GroupTimeslots` (
			`group_id` int NOT NULL,
			`timeslot` int NOT NULL,
			PRIMARY KEY (`group_id`, `timeslot`)
		) ENGINE=`InnoDB`;");
	}

	public function down(PDO &$pdo)
	{
		$pdo->query("DROP TABLE `GroupTimeslots`");
	}

}

?>
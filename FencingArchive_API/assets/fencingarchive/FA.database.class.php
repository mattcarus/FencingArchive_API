<?php
/*	FencingArchive.net Database Class
 *
 *	Version 0.1b
 */

class Database
{
	private $db_link;
	
	function __construct()
	{
		$this->db_link = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD) or die ('Unable to connect to database server');
		mysql_select_db(DB_DATABASE, $this->db_link);
	}
	
	function query($sql)
	{
		return mysql_query($sql, $this->db_link);
	}
}
?>
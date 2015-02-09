<?php
/*	FencingArchive.net API Client Class
 *
 *	Version 0.1b
 */

class Rankings {
	public $category = '';
	public $weapon = '';
	public $rankings = array();
	
	function __construct($category, $weapon)
	{
		$this->getRankings($category, $weapon);
	}
	
	function Rankings($category, $weapon)
	{
		$this->getRankings($category, $weapon);
	}
	
	public function getRankings($category, $weapon)
	{
		$viewName = "`rankings_$category.v`";
		
		$db = new Database();
		
		$sql = "SET @rank=0; SELECT @rank:=@rank+1 AS rank, id, totalPoints FROM $viewName;";
		
		echo $sql;
		
		$results = $db->query($sql);
		
		$this->category = $category;
		$this->weapon = $weapon;
		
		while ( $row = mysql_fetch_assoc($results) )
		{
			array_push($this->rankings, array('rank' => $row['rank'], 'totalPoints' => $row['totalPoints'], 'fencer' => new Fencer($row['id'])));
		}
	}

}

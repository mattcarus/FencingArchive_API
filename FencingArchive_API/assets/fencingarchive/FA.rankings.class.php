<?php
/*	FencingArchive.net API Client Class
 *
 *	Version 0.1b
 */

class Rankings {
	public $category = '';
	public $weapon = '';
	public $rankings = array();
	
	function __construct($category, $weapon, $topn)
	{
		$this->getRankings($category, $weapon, $topn);
	}
	
	function Rankings($category, $weapon, $topn)
	{
		$this->getRankings($category, $weapon, $topn);
	}
	
	public function getRankings($category, $weapon, $topn)
	{
		$viewName = "`rankings_$category.v`";
		
		$limit = "";
		
		if ( $topn > 0 )
		{
			$limit = " LIMIT 0, $topn";
		}
		
		
		$db = new Database();
		
		$db->query("SET @rank=0;");
		$results = $db->query("SELECT @rank:=@rank+1 AS rank, id, totalPoints FROM $viewName WHERE `weapon`='$weapon' $limit;");
		
		$this->category = $category;
		$this->weapon = $weapon;
		
		while ( $row = mysql_fetch_assoc($results) )
		{
			$fencer = new Fencer($row['id']);
			array_push($this->rankings, array('rank' => $row['rank'], 'totalPoints' => $row['totalPoints'], 'fencer' => $fencer));
		}
	}
}

<?php
/*	FencingArchive.net API Client Class
 *
 *	Version 0.1b
 */

class Rankings {
	public $category = '';
	public $weapon = '';
	public $name = '';
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
		switch ( $weapon )
		{
			case "ME":
				$this->name = ucfirst($category) . " Men's Epee";
				break;
			case "MF":
				$this->name = ucfirst($category) . " Men's Foil";
				break;
			case "MS":
				$this->name = ucfirst($category) . " Men's Sabre";
				break;
			case "WE":
				$this->name = ucfirst($category) . " Women's Epee";
				break;
			case "WF":
				$this->name = ucfirst($category) . " Women's Foil";
				break;
			case "WS":
				$this->name = ucfirst($category) . " Women's Sabre";
				break;
										
		}
		
		while ( $row = mysql_fetch_assoc($results) )
		{
			$fencer = new Fencer($row['id']);
			array_push($this->rankings, array('rank' => $row['rank'], 'totalPoints' => $row['totalPoints'], 'fencer' => $fencer));
		}
	}
	
	public function getFencersRanking($fid)
	{
		foreach ( $this->rankings as $ranking )
		{
			if ( $ranking->fencer->fid == $fid )
			{
				return $ranking->rank;
			}
			return False;
		}
	}
}

<?php
/*	FencingArchive.net API Client Class
 *
 *	Version 0.1b
 */

class Series {
	public $sid = '';
	public $name = '';
	public $weapon = '';
	public $date = '';
	public $views = '';
	
	function __construct($sid)
	{
		$this->getSeries($sid);
	}
	
	function Series($sid)
	{
		$this->getSeries($sid);
	}
	
	public function getSeries($sid)
	{
		$db = new Database();
		
		$row = mysql_fetch_assoc($db->query("SELECT * FROM `series` WHERE `id` = '$sid';"));
		
		$this->sid = $sid;
		$this->name = $row['name'];
		$this->weapon = $row['weapon'];
		$this->date = $row['date'];
	}
	
	public function getName()
	{
		$name = $this->name;

		$name .= ( $this->weapon == "ME" ? " Mens Epee" : "");
		$name .= ( $this->weapon == "MF" ? " Mens Foil" : "");
		$name .= ( $this->weapon == "MS" ? " Mens Sabre" : "");
		$name .= ( $this->weapon == "WE" ? " Womens Epee" : "");
		$name .= ( $this->weapon == "WF" ? " Womens Foil" : "");
		$name .= ( $this->weapon == "WS" ? " Womens Sabre" : "");
		$name .= ( $this->weapon == "XE" ? " Mixed Epee" : "");
		$name .= ( $this->weapon == "XF" ? " Mixed Foil" : "");
		$name .= ( $this->weapon == "XS" ? " Mixed Sabre" : "");

		return $name;
	}
	
	public function getDate()
	{
		return $this->date;
	}
	
	public function getLink()
	{
		return "<a href=\"" . BASE_URL . "/series/" . $this->sid . "\">" . $this->getName() . "</a>";
	}

	public function getRankings($topN = '')
	{
		$db = new Database();
		
		$rankings = array();
		
		if ( $topN != '' )
			$limit = "AND position <= '$topN'";
		
		$results = $db->query("SELECT fid, position FROM `rankings` WHERE `sid` = '" . $this->sid . "' $limit;");
		
		while ( $row = mysql_fetch_assoc($results) )
		{
			array_push($rankings, array('position' => $row['position'], 'fencer' => new Fencer($row['fid'])));
		}
		
		return $rankings;
	}
}

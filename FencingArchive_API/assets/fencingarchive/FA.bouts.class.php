<?php
/*	FencingArchive.net API Client Class
 *
 *	Version 0.1b
 */

class Bouts {
	public $bouts = array();
	
	function __construct()
	{

	}
	
	function Bouts()
	{

	}
	
	public function allFencerBouts($fid)
	{
		$this->fencerBouts("WHERE winner_id=$fid OR loser_id=$fid");
	}
	
	public function competitionFencerBouts($fid, $cid)
	{
		$this->fencerBouts("WHERE (winner_id=$fid OR loser_id=$fid) AND cid=$cid");
	}
	
	private function fencerBouts($whereClause)
	{
		$db = new Database();
		$results = $db->query("SELECT id FROM bouts $whereClause ;");
		
		while ( $row = mysql_fetch_assoc($results) )
		{
			array_push( $this->bouts, new Bout($row['id']) );
		}
	}
}

class Bout {
	public $winner;
	public $loser;
	public $winner_score = 0;
	public $loser_score = 0;
	public $phase = "";
	public $competition;
	
	function __construct($id)
	{
		$this->Bout($id);
	}
	
	function Bout($id)
	{
		$db = new Database();
		$result = $db->query("SELECT id FROM bouts WHERE id=$id;");
		
		$row = mysql_fetch_assoc($result);
		$this->winner = new Fencer($row['winner_id']);
		$this->loser = new Fencer($row['loser_id']);
		$this->winner_score = $row['winner_score'];
		$this->loser_score = $row['loser_score'];
		$this->phase = $row['phase'];
		$this->competition = new Competition($row['cid']);
	}
}
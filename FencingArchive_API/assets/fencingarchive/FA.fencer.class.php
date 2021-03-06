<?php
/*	FencingArchive.net API Client Class
 *
 *	Version 0.1b
 */

class Fencer {
	public $fid = '';								// Fencer ID
	public $name = '';								// Human-formatted name
	public $surname = '';							// Surname
	public $forename = '';							// Forename
	public $clubs = array();						// Club IDs
	public $weapon = '';							// Weapon
	public $image_url = '';							// Image URL
	public $profile_url = '';						// Profile URL
	public $banner_url = '';						// Forum Banner URL
	public $tag_url = '';							// QR Tag URL
	public $nationality = '';						// Nationality
	public $medals = array();						// Associative array of Medals
	public $results = array();						// Array of competition results
	public $rankings = array();						// Array of rankings
	public $bouts = array();						// Array of bout results
	public $popularity = array();					// Array of popularity values (0 is earliest)
	public $isvalid = false;							// Indicates whether or not the object has been populated

	// New-style PHP constructor (PHP5+)
	function __construct($fid) {
		$this->getFencer($fid);
	}
	
	// Old-style PHP constructor (PHP <5.3.3)
	function Fencer($fid) {
		$this->getFencer($fid);
	}
	
	public function getFencer($fid) {
		$this->fid = $fid;
		$db = new Database();
		
		$result = $db->query("SELECT * FROM `fencers` WHERE `id` = '$fid';");
		
		if ( mysql_num_rows($result) > 0 )
		{
			$row = mysql_fetch_assoc($result);
			
			$this->surname = $row['surname'];
			$this->forename = $row['forename'];
			$this->nationality = $row['nationality'];
			$this->name = $this->getName();
			
			if ( $row['image'] ) {
				$this->image_url = API_URL . "/image/" . $row['image'];
			} else {
				$this->image_url = API_URL . "/image/1";
			}	
			$this->profile_url = BASE_URL . "/fencer/" . $fid;
			$this->banner_url = BASE_URL . "/forum_banner.php?id=$fid";
			$this->tag_url = "http://fencingarchive.net/qr_tag.php?type=fid&code=$fid&size=6";
			$this->elo = $row['elo'];
			
			// Populate popularity array
			$this->popularity = explode("|", substr($row['popularity'], 0, strlen($row['popularity'])-1));
			
			// Find medal numbers

			$row = mysql_fetch_assoc($db->query("SELECT COUNT(cid) as medals FROM `results` WHERE fid=$fid AND position=1;"));
			$this->medals['gold'] = $row['medals'];
			
			$row = mysql_fetch_assoc($db->query("SELECT COUNT(cid) as medals FROM `results` WHERE fid=$fid AND position=2;"));
			$this->medals['silver'] = $row['medals'];
			
			$row = mysql_fetch_assoc($db->query("SELECT COUNT(cid) as medals FROM `results` WHERE fid=$fid AND position=3;"));
			$this->medals['bronze'] = $row['medals'];
			
/*			
			// Populate bouts array
			$results = $db->query("SELECT * FROM `fencers_bouts.v` WHERE `winner_id`=$fid OR `loser_id`=$fid;");
			while ( $row = mysql_fetch_assoc($results) )
			{
				array_push($this->bouts, array('winner_id' => $row['winner_id'], 'winner_score' => $row['winner_score'], 'loser_score' => $row['loser_score'], 'loser_id' => $row['loser_id']));
			}
			
			// Populate Rankings Array
			$results = $db->query("SELECT * FROM `fencers_rankings.v` WHERE `fid`=$fid ORDER BY `date` DESC;;");
			while ( $row = mysql_fetch_assoc($results) )
			{
				array_push($this->rankings, array('position' => $row['position'], 'series' => new Series($row['sid'])));
			}
*/			
			// Mark the object as valid
			$this->isvalid = true;
			
			$ranking = new Rankings('senior', 'ME', 0);
			$this->rankings['ME'] = $ranking->getFencersRanking($this->fid);
			
			$ranking = new Rankings('senior', 'MF', 0);
			$this->rankings['MF'] = $ranking->getFencersRanking($this->fid);
			
			$ranking = new Rankings('senior', 'MS', 0);
			$this->rankings['MS'] = $ranking->getFencersRanking($this->fid);
			
			$ranking = new Rankings('senior', 'WE', 0);
			$this->rankings['WE'] = $ranking->getFencersRanking($this->fid);
			
			$ranking = new Rankings('senior', 'WF', 0);
			$this->rankings['WF'] = $ranking->getFencersRanking($this->fid);
			
			$ranking = new Rankings('senior', 'WS', 0);
			$this->rankings['WS'] = $ranking->getFencersRanking($this->fid);
		}
		
		$clubs = $db->query("SELECT * FROM `club_members` LEFT JOIN clubs ON club_id=clubs.id WHERE `fencer_id`=$fid;");
		
		while ( $row = mysql_fetch_assoc($clubs) )
		{
			array_push($this->clubs, array('id' => $row['club_id'], 'name' => $row['name']));
		}
	}

	public function getName() {
		return $this->surname . ", " . $this->forename . " (" . $this->nationality . ")";
	}
	
	public function getForename() {
		return $this->forename;
	}
	
	public function getSurname() {
		return $this->surname;
	}
	
	public function getClubs() {
		return $this->clubs;
	}
	
	public function getWeapon() {
		return $this->weapon;
	}
	
	public function getImageURL() {
		return $this->image_url;
	}
	
	public function getProfileURL() {
		return $this->profile_url;
	}
	
	public function getBannerURL() {
		return $this->banner_url;
	}
	
	public function getTagURL() {
		return $this->tag_url;
	}
	
	public function getNationality() {
		return $this->nationality;
	}
	
	public function getMedals($colour) {
		return $this->medals[$colour];
	}
	
	public function getResults() {
		return $this->results;
	}
	
	public function getRankings() {
		return $this->rankings;
	}
	
	public function getBouts() {
		return $this->bouts;
	}
	
	public function getTagCloud() {
		$consolidatedResults = array();
		
		foreach ( $this->getResults() as $result )
		{

		}
	}
	
	public function getLink()
	{
		return "<a href=\"" . $this->getProfileURL() . "\">" . $this->getName() . "</a>";
	}
	
	public function getPopularity()
	{
		return $this->popularity;
	}
	
	public function getClubsPrintableInline()
	{
		$clubs = array();
		foreach ( $this->getClubs() as $club_id )
		{
			$objClub = new Club($club_id);
			array_push($clubs, $objClub->getLink());
		}
		return implode(", ", $clubs);
	}
	
	public function getResultsFormatted()
	{
		$results = array();
		foreach ( $this->getResults() as $result )
		{
			array_push($results, array('position' => $result['position'], 'name' => $result['competition']->getLink(), 'date' => $result['competition']->getDate()));
		}
		return $results;
	}
	
	public function getRankingsFormatted()
	{
		$orderedRankings = array();
		foreach ( $this->getRankings() as $ranking )
		{
			array_push($orderedRankings, array('position' => $ranking['position'], 'name' => $ranking['series']->getLink(), 'date' => $ranking['series']->getDate()));
		}
		return $orderedRankings;
	}
	
	public function populateResults()
	{
		$db = new Database();
		// Populate results array
		$results = $db->query("SELECT * FROM `fencers_results.v` WHERE `fid`=" . $this->fid . " ORDER BY `date` DESC;");
		while ( $row = mysql_fetch_assoc($results) )
		{
			array_push($this->results, array('position' => $row['position'], 'competition' => new Competition($row['cid'])));
		}
		
	}
}

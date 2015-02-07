<?php
/*	FencingArchive.net API Client Class
 *
 *	Version 0.1b
 */

class Club {
	public $id = '';
	public $name = '';
	public $url = '';
	public $latitude = '';
	public $longitude = '';
	public $status = '';
	public $founding_date = '';
	public $absorbed_into = '';
	public $image_id = '';
	public $image_url = '';
	public $members = array();
	
	
	function __construct($id)
	{
		$this->Club($id);
	}
	
	function Club($id)
	{
		$db = new Database();
		$results = $db->query("SELECT * FROM `clubs` WHERE `id`='$id'");
		
		$row = mysql_fetch_assoc($results);
		$this->name = $row['name'];
		$this->id = $id;
		$this->url = $row['url'];
		$this->latitude = $row['latitude'];
		$this->longitude = $row['longitude'];
		if ( sizeof(explode("|", $row['status'])) > 1 ) {
			list($this->status, $this->founding_date, $this->absorbed_into) = explode("|", $row['status']);
		}
		$this->image_id = $row['image'];
		$this->image_url = "http://fencingarchive.net/image.php?image_id=" . $row['image'];
		
		$members = $db->query("SELECT fencer_id FROM `club_members` LEFT JOIN fencers ON fencer_id=fencers.id WHERE `club_id`='$id'");
		
		while ( $row = mysql_fetch_assoc($results) )
		{
			array_push( $this->members, new Fencer($row['fencer_id']) );
		}
	}
	
	public function getName()
	{
		return $this->club_name . " Fencing Club";
	}
	
	public function getLink()
	{
		return "<a href=\"" . BASE_URL . "/club/" . urlencode($this->club_id) . "\">" . $this->club_name . "</a>";
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getUrl()
	{
		return $this->url;
	}
	
	public function getLatitude()
	{
		return $this->latitude;
	}
	
	public function getLongitude()
	{
		return $this->longitude;
	}
	
	public function getStatus()
	{
		return $this->status;
	}
	
	public function getFoundingDate()
	{
		return $this->founding_date;
	}
	
	public function getAbsorbedInto()
	{
		return $this->absorbed_into;
	}
	
	public function getImageId()
	{
		return $this->image_id;
	}
	
	public function getImageUrl()
	{
		return $this->image_url;
	}
}

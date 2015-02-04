<?php
/*	FencingArchive.net API Client Class
 *
 *	Version 0.1b
 */

class AdHocQueries
{
	public function lastNCompetitionLinks($offset, $number)
	{
		$db = new Database();
		
		$results = $db->query("SELECT `id` FROM `competitions` ORDER BY `date` DESC LIMIT " . $offset . ", " . $number . ";");
		
		$competitions = array();
		while ( $row = mysql_fetch_assoc($results) )
		{
			$comp = new Competition($row['id']);
			array_push($competitions, $comp->getLink());
		}
		return $competitions;
	}
	
	public function lastNCompetitionIds($offset, $number)
	{
		$db = new Database();
		
		$results = $db->query("SELECT `id` FROM `competitions` ORDER BY `date` DESC LIMIT " . $offset . ", " . $number . ";");
		
		$competitions = array();
		while ( $row = mysql_fetch_assoc($results) )
		{
			array_push($competitions, $row['id']);
		}
		return $competitions;
	}
	
	public function closeFencers($offset, $inputFid = 4, $number = 3)
	{
		$db = new Database();
		
		$results = $db->query("SELECT * FROM ( SELECT fid, count(*) AS magnitude FROM `fencers_results.v` WHERE cid IN (SELECT cid FROM `results` WHERE fid = $inputFid) AND fid != $inputFid GROUP BY fid ORDER BY magnitude DESC LIMIT 0, $number ) as friends LEFT JOIN `fencers` ON fid = `fencers`.`id`;");
		
		$fencers = array();
		while ( $row = mysql_fetch_assoc($results) )
		{
			array_push($fencers, $row['fid']);
		}
		return $fencers;
	}
	
	public function recentSeries()
	{
		$db = new Database();
		
		$results = $db->query("SELECT * FROM series INNER JOIN (SELECT MAX(id) AS id FROM series GROUP BY name, weapon) ids ON series.id = ids.id ORDER BY name, weapon, date DESC;");
		
		$series = array();
		while ( $row = mysql_fetch_assoc($results) )
		{
			array_push($series, array("id" => $row['id'], "name" => $row['name'], "weapon" => $row['weapon'], "date" => $row['date'], "views" => $row['views']));
		}
		return $series;
	}
	
}
?>
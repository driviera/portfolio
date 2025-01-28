<?php

/** 
 *  SQL Statements of various sorts
 *  Create, Delete, Insert
 *  These are missing core code components to be functionality,
 *  but demonstrate an understanding of prepared SQL statements.
 */

// From a Lando development server
define('DBPW','lamp');
define('DBUSER','lamp');
define('DB','lamp');
define('DBSERVER','database');

// Establish the DB connection
$db = new mysqli(DBSERVER, DBUSER, DBPW, DB);
$db->set_charset("utf8mb4");

// Create a table
$createsql='CREATE TABLE IF NOT EXISTS `rurlkey` (
	`primaryid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`urlkey` varchar(36) NOT NULL,
	`urlurl` text NOT NULL,
	`urlhits` int(11) NOT NULL DEFAULT 0,
	`displayhits` int(11) NOT NULL DEFAULT 1,
	`datecreated` datetime NOT NULL,
	`datelastdisplay` datetime NOT NULL DEFAULT current_timestamp(),
	`datelastvisit` datetime NOT NULL,
	`ipaddress` varchar(46) NOT NULL,
	`urlblurb` text NOT NULL,
	PRIMARY KEY (`primaryid`),
	UNIQUE KEY `urlkey` (`urlkey`)
	) ENGINE=MyISAM DEFAULT CHARSET=binary;';

if($db->query($createsql)===true) print '<pre style="color: red;font-weigh:bold;">DB table created.</pre>';

// Delete a record
$sql = 'DELETE FROM `rurlkey` WHERE `primaryid` = ?';
$stmt = $db->prepare($sql);
$stmt->bind_param('i',$_POST["urlid"]);
$stmt->execute();

// Insert a record
$sql = 'INSERT INTO `rurlkey` (`urlblurb`,`urlkey`,`urlurl`,`datecreated`,`datelastvisit`,`ipaddress`,`host`) VALUES (?,?,?,?,?,?,?)';
$stmt = $db->prepare($sql);
$stmt->bind_param('sssssss',$urlblurbcleaned,$newurlkey,$newurl,$datecreated,$datelastvisit,$createdby,$host);
$stmt->execute();
$stmt->store_result();
if($stmt->insert_id>0) $newurlkeyid = $stmt->insert_id;

// Generate "RANDOM" GUID
function UUID() {
    $uuid = sprintf('R%03X%04X-A%02X-N%03X-D%03X-O%03X%03X-M%03X', mt_rand(0, 4095), mt_rand(0, 65535), mt_rand(0, 4095), mt_rand(0, 4095), mt_rand(0, 4095), mt_rand(0, 255), mt_rand(0, 65535), mt_rand(0, 65535));
    return $uuid;
}

/**
 * If "random" requested, use SQL to select a random entry,
 * otherwise, return the selected key.
 */
if($urlkey=="random") {
    $sql = "SELECT `host`,`datecreated`,`displayhits`,`urlhits`,`urlkey`,`urlurl`,`primaryid`,`urlblurb` , RAND( ) AS  `random` FROM `rurlkey` ORDER BY  `random` LIMIT 1";
    $stmt = $db->prepare($sql);
 } else {
    $sql = 'SELECT `host`,`datecreated`,`displayhits`,`urlhits`,`urlkey`,`urlurl`,`primaryid`,`urlblurb`, null as `random` FROM `rurlkey` WHERE `urlkey` = ?';
    $stmt = $db->prepare($sql);
    $stmt->bind_param('s',$urlkey);
 }

 // Update an entry when the key is visited
 $sql = 'UPDATE `rurlkey` SET `datelastdisplay` = ?, `displayhits` = `displayhits`+1 WHERE `primaryid` = ?';
 $stmt = $db->prepare($sql);
 $todayis = gmdate("Y-m-d H:i:s");
 $stmt->bind_param('si',$todayis,$urlid);
 $stmt->execute();
 $stmt->close();
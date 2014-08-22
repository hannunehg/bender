<?php

//$arr = array ('item1'=>"I love jquery4u",'item2'=>"You love jQuery4u",'item3'=>"We love jQuery4u");
//echo json_encode(array('item1'=>"I love jquery4u",'item2'=>"You love jQuery4u",'item3'=>"We love jQuery4u"));

require 'database_utilities.php';

if (isset($_GET['unitID'])) 
{		
   return createConfigurationFiles(
   $_GET['unitID'],
   $_GET['unitnumber'], 
   $_GET['rodsnumber'], 
   $_GET['rodsthickness']);
}
function createConfigurationFiles($unitID, $unitnumber, $rodsnumber, $rodsthickness) 
{
	$array = array();
	$array['status'] = "ERR";
	
	//Get all pieces
	$g_link = GetMyConnection();
	$result = mysql_query("SELECT * FROM  `pieces` where unit_id = '".$unitID."' ORDER BY seq_number ASC") or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
	
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		 $CREATE_DIR = "C:/Temp/";
	}   
	else {
		$CREATE_DIR = "/root/bender";
	}

	$pieces_file = fopen($CREATE_DIR."moves.txt", "w") or die("Unable to open file!");
	
	$num = mysql_num_rows($result);
	$index= 1;
	while ($row = mysql_fetch_array($result, MYSQL_BOTH)) {
		fwrite($pieces_file, $row['dimension']."\t".$row['angle']);
		if ($index != $num)
			fwrite($pieces_file, PHP_EOL);
		$index++;
	}
	fclose($pieces_file) or die("Unable to close file!");
	
	$parameters_file = fopen($CREATE_DIR."params.txt", "w") or die("Unable to open file!");
	fwrite($parameters_file, "number_of_rods = ".$rodsnumber.PHP_EOL);
	fwrite($parameters_file, "thickness = ".$rodsthickness.PHP_EOL);
	fwrite($parameters_file, "number_of_ordered_units = ".$unitnumber.PHP_EOL);
	fwrite($parameters_file, "number_of_completed_units = 0");
	fclose($parameters_file) or die("Unable to close file!");

	
	$array['status'] = "OK";
    $json = json_encode($array);
	echo $json;
}


if (isset($_GET['getAllUnits'])) 
{
   return getAllUnits();
}
function getAllUnits() 
{
	$g_link = GetMyConnection();
	
	$result = mysql_query("SELECT * FROM  `units` ORDER BY `units`.`id` DESC") or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
	
	$array = array();
	if (mysql_affected_rows($g_link) > 0) {
		while ( $row = mysql_fetch_array($result, MYSQL_BOTH) ) {
			$array[$row['id']] = $row['unit_name'];
		}
	}
	
    $json = json_encode($array);
	echo $json;
}

if (isset($_GET['getUnitInformation'])) 
{
   return getUnitInformation($_GET['getUnitInformation']);
}
function getUnitInformation($unitID) 
{
	$g_link = GetMyConnection();
	
	$result = mysql_query("SELECT * FROM  `units` where id = '".$unitID."'") or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
	$row = mysql_fetch_array($result, MYSQL_BOTH);
	
	$array = array();
	$array["unit_name"] = $row['unit_name'];
    $json = json_encode($array);
	echo $json;
}


if (isset($_GET['getAllPieces'])) 
{
   return getAllPieces($_GET['getAllPieces']);
}
function getAllPieces($unitID) 
{
	$g_link = GetMyConnection();
	
	$result = mysql_query("SELECT * FROM  `pieces` where unit_id = '".$unitID."' ORDER BY seq_number ASC") or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
	
	$double_array = array();
	while ( $row = mysql_fetch_array($result, MYSQL_BOTH) ) {
		$array = array($row['seq_number'], $row['angle'], $row['dimension']);
		array_push($double_array, $array);
	}
	
    $json = json_encode($double_array);
	echo $json;
}


if (isset($_GET['deleteUnit'])) 
{
   return deleteUnit($_GET['deleteUnit']);
}
function deleteUnit($unitIDToBeDelete) 
{
	$array = array();
	$array["status"] = "ERR";
	$g_link = GetMyConnection();
	$result = mysql_query("DELETE FROM `units` WHERE id = ".$unitIDToBeDelete) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
	$result = mysql_query("DELETE FROM `pieces` WHERE unit_id = ".$unitIDToBeDelete) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
	
	$array["status"] = "OK";
    $json = json_encode($array);
	echo $json;
}

if (isset($_GET['saveUnit'])) 
{
   return saveUnit($_GET['saveUnit']);
}
function saveUnit($unitNameToBeSaved) 
{
	$array = array();
	$array["status"] = "ERR";
	$array["newlySavedID"] = 0;
	$g_link = GetMyConnection();
	$result = mysql_query("INSERT INTO `units` VALUES (NULL,'".$unitNameToBeSaved."')") or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
	
	if ($result == false)
	{
		$json = json_encode($array);
		echo $json;
		return;
	}
	
	$array["newlySavedID"] = mysql_insert_id();
	$array["status"] = $unitNameToBeSaved;
    $json = json_encode($array);
	echo $json;
}

if (isset($_POST['savePiece'])) 
{   
   return savePiece($_POST['dimension'], $_POST['angle'], $_POST['seq_number'], $_POST['unitID']);
}
function savePiece($dimension, $angle, $seq_number, $unitID) 
{
	$array = array();
	$array["status"] = "ERR";

	$array["newlySavedID"] = 0;
	$g_link = GetMyConnection();
	$result = mysql_query("INSERT INTO `pieces` VALUES (NULL,'".$dimension."','".$angle."','".$seq_number."','".$unitID."')") or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
	
	if ($result == false)
	{
		$json = json_encode($array);
		echo $json;
		return;
	}
	
	$array["newlySavedID"] = mysql_insert_id();
	$array["status"] = "OK";
    $json = json_encode($array);
	echo $json;
}

?>


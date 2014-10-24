<?php

require 'common.php';
require 'database_utilities.php';

if (isset($_GET['operation_name'])) 
{
   if  ($_GET['operation_name'] === "readStatesFile")
   {
		return readStatesFile();
   }
   else if  ($_GET['operation_name'] === "readParamsFile")
   {
		return readParamsFile();
   }
   else if  ($_GET['operation_name'] === "createStatesFile")
   {
		return createStatesFile($_GET['stateMachine']);
   }
   else if  ($_GET['operation_name'] === "updateMachineAccordingToCurrentState")
   {
		return updateMachineAccordingToCurrentState($_GET['state']);
   }
}

function updateMachineAccordingToCurrentState($state)
{	
	$array = array();
	$array['status'] = "ERR";
	
	//TOOD: call looper.sh here?
	
	$array['status'] = "OK"; //TODO: check process output
	$array['currentState'] = $state;	
	$array['processOutput'] = "SUCCESS"; //TODO: fill from the output of the process
	$json = json_encode($array);
	echo $json;
}

function createStatesFile($stateMachine) 
{
	$array = array();
	$array['status'] = "ERR";
	
	$states_file = fopen(GetMachineStateFileFullPath(), "w") or die("Unable to open file!");
	//0 -> IDLE
	//1 -> RUNNING
	//2 -> PAUSED
	fwrite($states_file, $stateMachine.PHP_EOL) or die("Unable to close file!");
	fclose($states_file) or die("Unable to close file!");
	
	$array['status'] = "OK";
    $json = json_encode($array);
	echo $json;
}

function readStatesFile()
{
	$array = array();
	$array['status'] = "ERR";
	
	$isFileExist = file_exists(GetMachineStateFileFullPath());
	$array["fileExits"] = $isFileExist;
	if ($isFileExist == false)
	{
		$array['status'] = "OK";
		$json = json_encode($array);
		echo $json;
		return;
	}
	
	$handle = fopen(GetMachineStateFileFullPath(), "r");
	if ($handle) 
	{
		$array['status'] = "OK";
		if (($line = fgets($handle)) !== false) 
		{
			$line = str_replace(array("\n", "\r"), '', $line);
			$array["machineState"] = $line; 
		}
	}
	fclose($handle);
	
	$json = json_encode($array);
	echo $json;
}

function readParamsFile()
{
	$array = array();
	$array['status'] = "ERR";
	
	$handle = fopen(GetParamsFileFullPath(), "r");
	if ($handle) 
	{
		$array['status'] = "OK";
		while (($line = fgets($handle)) !== false) 
		{
			//tokenize line based on space
			//e.g. :
			//when $line = "number_of_rods = 2" then $tokens = {'number_of_rods', '=', '2'}
			//Remove new lines from line
			$line = str_replace(array("\n", "\r"), '', $line);

			$tokens = explode(" ", $line);
			$array[$tokens[0]] = $tokens[2]; 
		}
	}
	fclose($handle);
	
	$json = json_encode($array);
	echo $json;
}

if (isset($_GET['unitID'])) 
{		
   return createConfigurationFiles(
   $_GET['unitID'],
   $_GET['unitNumber'], 
   $_GET['rodsNumber'], 
   $_GET['rodsThickness'],
   $_GET['numberOfCompletedUnits']);
}
function createConfigurationFiles($unitID, $unitNumber, $rodsNumber, $rodsThickness, $numberOfCompletedUnits) 
{
	$array = array();
	$array['status'] = "ERR";
	
	$calibArray = ReadCalibrationFile();
	if ($calibArray === false)
	{
		$json = json_encode($array);
		echo $json;
	}
	$dimentionCorrectionOnServer = floatval($calibArray["dimentionCorrectionOnServer"]);
	$angleCorrectionOnServer = floatval($calibArray["angleCorrectionOnServer"]);
	
	//Writing to 1st file
	$g_link = GetMyConnection();
	//Get all pieces
	$result = mysql_query("SELECT * FROM  `pieces` where unit_id = '".$unitID."' ORDER BY seq_number ASC") or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
	$pieces_file = fopen(GetMovesFileFullPath(), "w") or die("Unable to open file!");
	$num = mysql_num_rows($result);
	$index= 1;
	$staticCorrectionValue = GetCorrectionConstantOnFirstAndLastPieces();
	while ($row = mysql_fetch_array($result, MYSQL_BOTH)) {
		$absoluteDim = floatval($row['dimension']) + $dimentionCorrectionOnServer;
		$absoluteAngle = floatval($row['angle']) + $angleCorrectionOnServer;
		
		//Adding the correction to the 1st and last piece by a predefined constant
		//This is because the nature of the machine
		if ($index == 1) // in case of the 1st piece, add the static correction
		{
			$absoluteDim += $staticCorrectionValue;
		}
		else if ($index == $num) // in case of the last piece, subtract the static correction
		{
			$absoluteDim -= $staticCorrectionValue;
		}
		
		fwrite($pieces_file,$absoluteDim."\t".$absoluteAngle);
		if ($index != $num)
			fwrite($pieces_file, PHP_EOL);
		$index++;
	}
	fclose($pieces_file) or die("Unable to close file!");
	
	//Writing to 2nd file
	$parameters_file = fopen(GetParamsFileFullPath(), "w") or die("Unable to open file!");
	fwrite($parameters_file, "number_of_rods = ".$rodsNumber.PHP_EOL);
	fwrite($parameters_file, "thickness = ".$rodsThickness.PHP_EOL);
	fwrite($parameters_file, "number_of_ordered_units = ".$unitNumber.PHP_EOL);
	fwrite($parameters_file, "number_of_completed_units = ".$numberOfCompletedUnits.PHP_EOL);
	fwrite($parameters_file, "unit_id = ".$unitID.PHP_EOL);
	fclose($parameters_file) or die("Unable to close file!");
	
	//Writing to 3rd file
	$states_file = fopen(GetMachineStateFileFullPath(), "w") or die("Unable to open file!");
	//0 -> IDLE
	//1 -> RUNNING
	//2 -> PAUSED
	fwrite($states_file, "RUNNING".PHP_EOL);
	fclose($states_file) or die("Unable to close file!");

	$array['status'] = "OK";
    $json = json_encode($array);
	echo $json;
}
if (isset($_GET['executeLooper']))
{
   return executeLooper();
}

function executeLooper() {

     $exec_array = execute_process("workspace/looper.sh");
     $json = json_encode($exec_array);
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


<?php

require 'common.php';

/////////////////////////////////////////////////////////////////////
//					moveMachineForword							   //
/////////////////////////////////////////////////////////////////////
if (isset($_POST['moveMachineForword'])) 
{		
   return moveMachineForword($_POST['moveMachineForword']);
}
function moveMachineForword($moveLength) 
{
	$calibArray = ReadCalibrationFile();
	if ($calibArray === false)
	{
		$json = json_encode($array);
		echo $json;
	}
	$dimentionCorrectionOnServer = intval($calibArray["dimentionCorrectionOnServer"]);
	$originalMoveLength = intval($moveLength);
	$absoluteDim = $originalMoveLength + $dimentionCorrectionOnServer;
    
	$exec_array = execute_process('workspace/controller.sh workspace/alba forward '.$absoluteDim);
	$exec_array['originalMoveLength'] = $originalMoveLength;
	$exec_array['dimentionCorrectionOnServer'] = $dimentionCorrectionOnServer;
	$exec_array['absoluteDim'] = $absoluteDim;
	
    $json = json_encode($exec_array);
	echo $json;
}

/////////////////////////////////////////////////////////////////////
//					moveMachineBackword							   //
/////////////////////////////////////////////////////////////////////
if (isset($_POST['moveMachineBackword'])) 
{		
   return moveMachineBackword($_POST['moveMachineBackword']);
}
function moveMachineBackword($moveLength) 
{	
	$calibArray = ReadCalibrationFile();
	if ($calibArray === false)
	{
		$json = json_encode($array);
		echo $json;
	}
	$dimentionCorrectionOnServer = intval($calibArray["dimentionCorrectionOnServer"]);
	$originalMoveLength = intval($moveLength);
	$absoluteDim = $originalMoveLength + $dimentionCorrectionOnServer;
	
	$exec_array = execute_process('workspace/controller.sh workspace/alba backward '.$absoluteDim);
	$exec_array['originalMoveLength'] = $originalMoveLength;
	$exec_array['dimentionCorrectionOnServer'] = $dimentionCorrectionOnServer;
	$exec_array['absoluteDim'] = $absoluteDim;
	
    $json = json_encode($exec_array);
	echo $json;
}

/////////////////////////////////////////////////////////////////////
//					cutRod										   //
/////////////////////////////////////////////////////////////////////
if (isset($_POST['cutRod'])) 
{		
   return cutRod();
}
function cutRod() 
{
  $json = json_encode(execute_process('workspace/controller.sh workspace/alba cut ')); 	
   echo $json;
}
/////////////////////////////////////////////////////////////////////
//					bendRod							   			   //
/////////////////////////////////////////////////////////////////////
if (isset($_POST['bendRod'])) 
{		
   return bendRod($_POST['bendRod']);
}
function bendRod($angle) 
{
	$calibArray = ReadCalibrationFile();
	if ($calibArray === false)
	{
		$json = json_encode($calibArray);
		echo $json;
	}
	$angleCorrectionOnServer = intval($calibArray["angleCorrectionOnServer"]);
	$origAngle = intval($angle);
	$absoluteAngle = $origAngle + $angleCorrectionOnServer;
	
	$exec_array = execute_process('workspace/controller.sh workspace/alba bend '.$absoluteAngle);
	$exec_array['origAngle'] = $origAngle;
	$exec_array['angleCorrectionOnServer'] = $angleCorrectionOnServer;
	$exec_array['absoluteAngle'] = $absoluteAngle;
	
	$json = json_encode($exec_array);
	echo $json;
}
?>


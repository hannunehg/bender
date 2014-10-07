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
	$array = array();
	$array['status'] = "ERR";
	
	$calibArray = ReadCalibrationFile();
	if ($calibArray === false)
	{
		$array['errorMessages'] = "حصل خطأ داخلي .. فشلت عملية قراءة قيم التعييرات الأساسية";
		$json = json_encode($array);
		echo $json;
		return;
	}
	$dimentionCorrectionOnServer = intval($calibArray["dimentionCorrectionOnServer"]);
	$originalMoveLength = intval($moveLength);
	$absoluteDim = $originalMoveLength + $dimentionCorrectionOnServer;
	
	if ($absoluteDim < 0)
	{
		$array['errorMessages'] = "حصل خطأ داخلي .. تأكد من نافذة تعيير";
		$json = json_encode($array);
		echo $json;
		return;
	}
    
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
	$array = array();
	$array['status'] = false;
	
	$calibArray = ReadCalibrationFile();
	if ($calibArray === false)
	{
		$json = json_encode($array);
		echo $json;
		return;
	}
	$dimentionCorrectionOnServer = intval($calibArray["dimentionCorrectionOnServer"]);
	$originalMoveLength = intval($moveLength);
	$absoluteDim = $originalMoveLength + $dimentionCorrectionOnServer;
	
	if ($absoluteDim < 0)
	{
		$json = json_encode($array);
		echo $json;
		return;
	}
	
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
//					reset										   //
/////////////////////////////////////////////////////////////////////
if (isset($_POST['resetMachine'])) 
{		
   return resetMachine();
}
function resetMachine() 
{
   $json = json_encode(execute_process('workspace/controller.sh workspace/alba reset ')); 	
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
	$array = array();
	$array['status'] = false;
	
	$calibArray = ReadCalibrationFile();
	if ($calibArray === false)
	{
		$json = json_encode($array);
		echo $json;
		return;
	}
	$angleCorrectionOnServer = intval($calibArray["angleCorrectionOnServer"]);
	$origAngle = intval($angle);
	$absoluteAngle = $origAngle + $angleCorrectionOnServer;
	
	if ($absoluteAngle < 0)
	{
		$json = json_encode($array);
		echo $json;
		return;
	}
	
	$exec_array = execute_process('workspace/controller.sh workspace/alba bend '.$absoluteAngle);
	$exec_array['origAngle'] = $origAngle;
	$exec_array['angleCorrectionOnServer'] = $angleCorrectionOnServer;
	$exec_array['absoluteAngle'] = $absoluteAngle;
	
	$json = json_encode($exec_array);
	echo $json;
}
?>


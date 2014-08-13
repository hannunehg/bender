﻿<?php

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
	$array['status'] = "OK";
	$array['movedLength'] = $moveLength;
	$array['operation'] = "MOVEFORWORD";
	
	//TODO: call C function here
	
    $json = json_encode($array);
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
	$array['status'] = "OK";
	$array['movedLength'] = $moveLength;
	$array['operation'] = "MOVEBACKWORD";
	
	//TODO: call C function here
	
    $json = json_encode($array);
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
	$array = array();
	$array['status'] = "OK";
	$array['operation'] = "CUT";
	
	//TODO: call C function here
	
    $json = json_encode($array);
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
	$array['status'] = "OK";
	$array['angle'] = $angle;
	$array['operation'] = "BEND";
	
	//TODO: call C function here
	
    $json = json_encode($array);
	echo $json;
}

?>


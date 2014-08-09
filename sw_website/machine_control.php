<?php

/////////////////////////////////////////////////////////////////////
//					moveMachineForword							   //
/////////////////////////////////////////////////////////////////////
if (isset($_GET['moveMachineForword'])) 
{		
   return moveMachineForword();
}
function moveMachineForword() 
{
	$array = array();
	$array['status'] = "OK";
    $json = json_encode($array);
	echo $json;
}

/////////////////////////////////////////////////////////////////////
//					moveMachineBackword							   //
/////////////////////////////////////////////////////////////////////
if (isset($_GET['moveMachineBackword'])) 
{		
   return moveMachineBackword();
}
function moveMachineBackword() 
{
	$array = array();
	$array['status'] = "OK";
    $json = json_encode($array);
	echo $json;
}

/////////////////////////////////////////////////////////////////////
//					cutRod										   //
/////////////////////////////////////////////////////////////////////
if (isset($_GET['cutRod'])) 
{		
   return cutRod();
}
function cutRod() 
{
	$array = array();
	$array['status'] = "OK";
    $json = json_encode($array);
	echo $json;
}

/////////////////////////////////////////////////////////////////////
//					bendRod							   			   //
/////////////////////////////////////////////////////////////////////
if (isset($_GET['bendRod'])) 
{		
   return bendRod($_GET['bendRod']);
}
function bendRod($rr) 
{
	$array = array();
	$array['status'] = "OK";
    $json = json_encode($array);
	echo $json;
}

?>


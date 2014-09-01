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
  $json = json_encode(execute_process('workspace/controller.sh workspace/alba forward '.$moveLength));
  echo $json;
}

/////////////////////////////////////////////////////////////////////
//					moveMachineBackword							   //
/////////////////////////////////////////////////////////////////////
if (isset($_POST['moveMachineBackword'])) 
{		
   return moveMachineBackword($_POST['moveMachineBackword']);
}
function execute_process($command)
{
  $array = array();
  $array['status'] = "OK";

  $descriptorspec = array(
     0 => array("pipe", "r"),  // stdin
     1 => array("pipe", "w"),  // stdout
     2 => array("pipe", "w"),  // stderr
  );
  $process = proc_open($command, $descriptorspec, $pipes, dirname(__FILE__), null);
  $array['stdout'] = stream_get_contents($pipes[1]);
  fclose($pipes[1]);
 
  $array['stderr'] = stream_get_contents($pipes[2]);
  fclose($pipes[2]); 
 
  $array['returnValue'] = proc_get_status($process)['exitcode'];

  return $array;
}

function moveMachineBackword($moveLength) 
{	
       $json = json_encode(execute_process('workspace/controller.sh workspace/alba backward '.$moveLength));
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
  $json = json_encode(execute_process('workspace/controller.sh workspace/alba bend '.$angle));
  echo $json;
}

?>


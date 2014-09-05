<?php

function GetDirName()
{
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		 $CREATE_DIR = "workspace/";
	}   
	else {
		$CREATE_DIR = "workspace/";
	}
	return $CREATE_DIR;
}
//params.txt
function GetParamsFileFullPath()
{
	return GetDirName()."params.txt";
}
//moves.txt
function GetMovesFileFullPath()
{
	return GetDirName()."moves.txt";
}
//states.txt
function GetMachineStateFileFullPath()
{
	return GetDirName()."states.txt";
}

//calibration.txt
function GetCalibrationFileFullPath()
{
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		 $FullPath = "calibrations/calibration.txt";
	}   
	else {
		$FullPath = "calibrations/calibration.txt";
	}
	return $FullPath;
}

function ReadCalibrationFile()
{
	$array = array();
	$array['status'] = false;
	$handle = fopen(GetCalibrationFileFullPath(), "r") or die("Unable to open file!");
	if ($handle) 
	{
		$index = 0;
		while (($line = fgets($handle)) !== false) 
		{
			$line = str_replace(array("\n", "\r"), '', $line);
			$parsedIntVal = intval($line);
			if ($index == 0)
				$array["dimentionCorrectionOnServer"] = $parsedIntVal;
			else if($index == 1)
				$array["angleCorrectionOnServer"] = $parsedIntVal;
				
			$index++;
		}
	}
	fclose($handle) or die("Unable to close file!");
	$array['status'] = true;
	return $array;
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

?>


<?php

require 'common.php';

if (isset($_POST['operation']) && $_POST['operation'] === "SetCalibration") 
{		
   return SetCalibration($_POST['angleCorrection'], $_POST['dimentionCorrection']);
}
function SetCalibration($angleCorrection, $dimentionCorrection) 
{
  $array = array();
  $array['status'] = false;
  $array['newDimentionCorrection'] = $dimentionCorrection;
  $array['newAngleCorrection'] = $angleCorrection;
  
  $states_file = fopen(GetCalibrationFileFullPath(), "w") or die("Unable to open file!");
  fwrite($states_file, $dimentionCorrection.PHP_EOL) or die("Unable to write dimension correction to file!");
  fwrite($states_file, $angleCorrection.PHP_EOL) or die("Unable to write angle correction to file!");
  fclose($states_file) or die("Unable to close file!");
  
  $array['status'] = true;
  $json = json_encode($array);
  echo $json;
}

if (isset($_POST['operation']) && $_POST['operation'] === "GetCalibration") 
{		
   return GetCalibration();
}
function GetCalibration() 
{
  $array = ReadCalibrationFile();
  $json = json_encode($array);
  echo $json;
}

?>


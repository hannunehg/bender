<?php

function func1() {
	  $array = array("a"=>"Caucho", "b"=>"Resin", "c"=>"Quercus");
    $json = json_encode($array);
	return $json;
}

if (isset($_POST['callFunc1'])) {
    echo func1();
}

?>


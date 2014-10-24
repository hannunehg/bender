#!/bin/bash

exec_cut="cut"
exec_bend="bend"
exec_forward="forward"
exec_backward="backward"
exec_sleep="sleep"
exec_reset="reset"

# Inputs
path_machine=$1
path_operation=$2

# Messages
msg_example="\ne.g. $0 alba cut"
msg_machine_fail="Please specify machine name in first argument!$msg_example"
msg_operation_fail="Please specify operation name in second argument!$msg_example"
msg_operation_support="Operation: ($path_operation) not supported"
msg_machine_support="Machine not Supported! Please update software or provide valid executables under folder: $path_machine"
msg_machine_busy="Machine is busy performing an operation"

# Return Values
ret_machine_fail=100
ret_operation_fail=110
ret_machine_support=101
ret_operation_support=111
ret_machine_busy=102

# The Standard we follow:  on the same folder this file is at, we have a folder for each supported machine containing the four basic above executables with names matching the variables

function quitJob() {
  echo -e $1
  exit $2
}

# Logic starts here
if [[ "$path_machine" == "" ]]
then
  quitJob "$msg_machine_fail" $ret_machine_fail
fi

if [[ ! -d "$path_machine" ]]
then 
  quitJob "$msg_machine_support" $ret_machine_support
fi

if [[ "$path_operation" != "reset" ]] 
then

	# Check if machine is performing another micro operation
	if pstree | grep "bend\|cut\|forward\|backward" > /dev/null
	then
  	  	quitJob "$msg_machine_busy" $ret_machine_busy
	fi
fi


case $path_operation in
  
  $exec_cut|$exec_forward|$exec_backward|$exec_bend|$exec_reset|$exec_sleep)

   #echo "$path_machine/$path_operation $3"
   sudo $path_machine/$path_operation $3;;
#   sleep 1;;
  
  "")
    quitJob "$msg_operation_fail" $ret_operation_fail;;

  *)
    quitJob "$msg_operation_support" $ret_operation_support;;
esac
   

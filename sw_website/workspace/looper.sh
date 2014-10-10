#! /bin/bash

# v 0.3
#
# 9/10/2014	motaz & rida	moved controller run cmd from pieceMaker.sh to here
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd $DIR

constFile=params.txt
pieceMaker="./pieceMaker.sh"

path_pieceCommands="piece.commands"
operatingMachine="alba"
path_runnerScript="./controller.sh"


# Read constans
rodsNum=`grep number_of_rods $constFile | awk '{print $3}'`
thickness=`grep thickness $constFile | awk '{print $3}'`

orderNum=`grep number_of_ordered_units $constFile | awk '{print $3}'`
completedNum=`grep number_of_completed_units $constFile | awk '{print $3}'`

# Call parser
$pieceMaker
res=$?
if [[ $res != 0  ]]
then
      exit $res;
fi

# Loop and call PARSER
for ((i=1; i<=$orderNum-$completedNum; i++))
do
        # Check kill signal
        if [[ `cat states.txt` != "RUNNING"  ]] 
        then
            exit 1;
        fi

        echo $i

	# run each line of the file
	while read line
	do
 
		sleep 0.25
   		#$path_runnerScript $operatingMachine $line
   	 	sudo nice -n -19 alba/$line
		res=$?
   		if [[ $res != 0  ]]
   	 	then
   	     		 exit $res;
   	 	fi

		#sleep 1
	done < $path_pieceCommands
	$path_runnerScript $operatingMachine "cut"

	# Update number of completed pieces
	    sed -i s/"`grep number_of_completed_units $constFile`"/"number_of_completed_units = `expr $completedNum + $i`"/ $constFile
	
done

cd - 1>/dev/null

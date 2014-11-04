#! /bin/bash

# v 0.7
#
# 9/10/2014	motaz & rida	moved controller run cmd from pieceMaker.sh to here
# 1/11/2014	All		faratna el while 
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd $DIR

constFile=params.txt
pieceMaker="./pieceMaker.sh"

path_pieceCommands="piece.commands"
operatingMachine="alba"
path_runnerScript="./controller.sh"

sudo chmod +x $path_pieceCommands
sudo chmod +x $pieceMaker

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
	#while read line
	#do
		#renice -n 15 -pid $$
   		#$path_runnerScript $operatingMachine $line
   	 	#if [[ $line == *forward* ]]
		#then
		#	sudo chrt -f 99 nice -n -20 alba/$line
		#else
		#	sudo nice -n -20 alba/$line
		#fi
		#alba/$line
		#res=$?
		#renice -n 0 -pid $$
   		#if [[ $res != 0  ]]
   	 	#then
   	     	#	 exit $res;
   	 	#fi
		#	
		#sleep 1
	#done < $path_pieceCommands
	
	# new run 1-11-2014
        ./$path_pieceCommands
	
	#$path_runnerScript $operatingMachine "cut"

	# Update number of completed pieces
	    sed -i s/"`grep number_of_completed_units $constFile`"/"number_of_completed_units = `expr $completedNum + $i`"/ $constFile
	
	sleep 1
done

cd - 1>/dev/null

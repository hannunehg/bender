#! /bin/bash

# v 0.2
#
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd $DIR

constFile=params.txt
pieceMaker="./pieceMaker.sh"

# Read constans
rodsNum=`grep number_of_rods $constFile | awk '{print $3}'`
thickness=`grep thickness $constFile | awk '{print $3}'`

orderNum=`grep number_of_ordered_units $constFile | awk '{print $3}'`
completedNum=`grep number_of_completed_units $constFile | awk '{print $3}'`

# Loop and call PARSER
for ((i=1; i<=$orderNum-$completedNum; i++))
do
        # Check kill signal
        if [[ `cat states.txt` != "RUNNING"  ]] 
        then
            exit 1;
        fi

        echo $i
	
	# Call parser
        $pieceMaker

        res=$?
    	if [[ $res != 0  ]]
    	then
        	exit $res;
    	else

	# Update number of completed pieces
	    sed -i s/"`grep number_of_completed_units $constFile`"/"number_of_completed_units = `expr $completedNum + $i`"/ $constFile
	fi
	
done

cd - 1>/dev/null

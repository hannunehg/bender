#! /bin/bash

# v 0.2
#


constFile=params

# Read constans
rodsNum=`grep number_of_rods $constFile | awk '{print $3}'`
thickness=`grep thickness $constFile | awk '{print $3}'`

orderNum=`grep number_of_pieces_ordered $constFile | awk '{print $3}'`
completedNum=`grep number_of_pieces_made $constFile | awk '{print $3}'`

# Loop and call PARSER
for ((i=1; i<=$orderNum-$completedNum; i++))
do
	echo "$i"
	
	# Call parser


	# Check kill signal	

	
	# Update number of completed pieces
	#sed -i s/"`grep number_of_pieces_made $constFile`"/"number_of_pieces_made = `expr $completedNum + $i`"/ $constFile

	
done




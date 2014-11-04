#!/bin/bash

#parse file: moves
#masafa  o zawya 0-180
path_pieceMoves="moves.txt"
path_pieceCommands="piece.commands"
#operatingMachine="alba"
#path_runnerScript="./controller.sh"

cat $path_pieceMoves | 
  awk 'BEGIN {
	print "#!/bin/bash";
      prefix="sudo chrt -f 99 nice -n -20 alba/";
      ifSt="res=$?\nif [[ $res != 0  ]]\nthen\nexit $res;\nfi\nsleep 0.1\n";
    }
    { 
      print prefix"forward "$1"\n"ifSt;
      print prefix"bend "$2"\n"ifSt;
    }END { print "\nsudo alba/cut\n" }' > $path_pieceCommands

# run each line of the file
#while read line           
#do 

#    $path_runnerScript $operatingMachine $line
#    res=$?
#    if [[ $res != 0  ]]
#    then
#        exit $res;
#    fi           
#done < $path_pieceCommands
#$path_runnerScript $operatingMachine "cut"

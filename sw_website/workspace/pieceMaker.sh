#!/bin/bash

#parse file: moves
#masafa  o zawya 0-180
path_pieceMoves="moves.txt"
path_pieceCommands="piece.commands"
operatingMachine="alba"
path_runnerScript="./controller.sh"

cat $path_pieceMoves | awk '{print "forward "$1"\nbend "$2}' > $path_pieceCommands

# run each line of the file
while read line           
do 

    $path_runnerScript $operatingMachine $line
    res=$?
    if [[ $res != 0  ]]
    then
        exit $res;
    fi           
done < $path_pieceCommands

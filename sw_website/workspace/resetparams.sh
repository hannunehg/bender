#!/bin/bash

. looper.sh

 sed -i s/"`grep number_of_completed_units $constFile`"/"number_of_completed_units = 0"/ $constFile  



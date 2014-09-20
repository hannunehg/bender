#include <wiringPi.h>
#include <stdio.h>
#include <stdlib.h>
#include "common.h"


int main (int argc, char ** argv)
{
	int backwardValue = 0;
        int setAllPinsExecResult = 0;
	if (argc != 2)
        {
		fprintf(stderr, "Arguments error\n");
		exit(1);
        }
	backwardValue = atoi(argv[1]);
	printf("backwardValue = %d\n", backwardValue);


	// HW Check
	if (system("grep 00000000440fb444  /proc/cpuinfo > /dev/null"))
	{
		fprintf(stderr, "HW ERROR #1\n");
		return 2;
	}


	setAllPinsExecResult = setAllPins(pin_OFF,pin_ON,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_ON, pin_ON,pin_ON);
        if (setAllPinsExecResult != 0)
        {
	   exit(3);
        }
	
	delay(500);
	
	resetPins();

        return 0;
}



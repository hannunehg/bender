#include <wiringPi.h>
#include <stdio.h>
#include <stdlib.h>
#include "common.h"


int main (int argc, char ** argv)
{
        int setAllPinsExecResult = 0;

	// HW Check
	if (system("grep 00000000440fb444  /proc/cpuinfo > /dev/null"))
	{
		fprintf(stderr, "HW ERROR #1\n");
		return 2;
	}
	// check it is on middle

	// push to cut
	setAllPinsExecResult = setAllPins(pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_ON,pin_OFF,pin_ON,pin_ON, pin_ON,pin_ON);
        if (setAllPinsExecResult != 0)
        {
	   exit(3);
        }
	delay(1000);

	initALBA();

        return 0;
}



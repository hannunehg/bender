#include <wiringPi.h>
#include <stdio.h>
#include <stdlib.h>
#include "common.h"


int main (int argc, char ** argv)
{
    	int execResult = 0;

	picoInit();

	// push to cut
	execResult = setAllPins(pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_ON,pin_OFF,pin_ON,pin_ON, pin_ON,pin_ON);
	if (execResult != 0)
	{
	   fprintf(stderr, "Setting all pins in cut.c failed\n");
	   resetPins();
	   exit(2);
	}
	
	delay(1000); //TODO: check with team
	
	execResult = initALBA();
	if (execResult != 0)
	{
	   fprintf(stderr, "initALBA() in cut.c failed = %d \n", execResult);
		   exit(3);
    	}
	
    	return 0;
}



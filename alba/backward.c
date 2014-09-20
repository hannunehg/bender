#include <wiringPi.h>
#include <stdio.h>
#include <stdlib.h>
#include "common.h"


int main (int argc, char ** argv)
{
	int backwardValue = 0;
    int execResult = 0;
	
	// HW Check
	if (system("grep 00000000440fb444  /proc/cpuinfo > /dev/null"))
	{
		fprintf(stderr, "HW ERROR #1\n");
		exit(1);
	}
	
	if (argc != 2)
    {
		fprintf(stderr, "Arguments error\n");
		exit(2);
    }
	backwardValue = atoi(argv[1]);
	printf("backwardValue = %d\n", backwardValue);

	execResult = setAllPins(pin_OFF,pin_ON,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_ON, pin_ON,pin_ON);
    if (execResult != 0)
    {
	   resetPins();
	   exit(3);
    }
	
	delay(500);
	
	execResult = resetPins();
	if (execResult != 0)
    {
	   exit(4);
    }
	
    return 0;
}



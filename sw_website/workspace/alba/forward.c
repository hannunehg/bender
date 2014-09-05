#include <wiringPi.h>
#include <stdio.h>
#include "common.h"


int main (void)
{

	// HW Check
	if (system("grep 00000000440fb444  /proc/cpuinfo > /dev/null"))
	{
		//printf("HW ERROR #1\n");
		return 1;
	}
	setAllPins(pin_ON,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF);

  return 0;
}



#include <wiringPi.h>
#include <stdio.h>
#include <stdlib.h>
#include "common.h"


int main (int argc, char ** argv)
{
    int setInitALBA = 0;

	// HW Check
	if (system("grep 00000000440fb444  /proc/cpuinfo > /dev/null"))
	{
		fprintf(stderr, "HW ERROR #1\n");
		exit(1);
	}


	setInitALBA = initALBA();
    if (setInitALBA != 0)
	{
		fprintf(stderr, "setInitALBA failed = %d\n", setInitALBA);
		exit(2);
	}

    return 0;
}



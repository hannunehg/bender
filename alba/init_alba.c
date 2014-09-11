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
		return 2;
	}


	setInitALBA = initALBA();
        if (setInitALBA != 0)
		exit(setInitALBA);

        return 0;
}



#include <wiringPi.h>
#include <stdio.h>
#include <stdlib.h>
#include "common.h"


int main (int argc, char ** argv)
{
    	int setInitALBA = 0;

	picoInit();

	setInitALBA = initALBA();
    	if (setInitALBA != 0)
	{
		fprintf(stderr, "setInitALBA failed = %d\n", setInitALBA);
		exit(2);
	}

    	return 0;
}



#include <wiringPi.h>
#include <stdio.h>
#include <stdlib.h>
#include <math.h>
#include "common.h"

int main (int argc, char ** argv)
{
	picoInit();
	int i = 0;
	int execResult = 0;
	//Getting arugments
	if (argc != 2)
        {
		fprintf(stderr, "Arguments error\n");
		return 2;
        }
	double fvalue = atof(argv[1]);
	execResult = moveRodInMachine(fvalue, pin_1_13_Forward);
	
	
	exit(execResult);
}

#include <wiringPi.h>
#include <stdio.h>
#include <stdlib.h>
#include <math.h>
#include "common.h"

int main (int argc, char ** argv)
{
	int i = 0;
	int execResult = 0;
	//Getting arugments
	if (argc != 2)
        {
		fprintf(stderr, "Arguments error\n");
		return 2;
        }

	double fvalue = atof(argv[1]);
	int rep = (int)(fvalue / 7.5);

	double mod = fmod(fvalue, 7.5);
	int remainder = (int)((mod / 7.5) * (double)80);
	
	printf("fvalue = %f, rep = %d, mod = %f, remainder = %d\n",fvalue ,rep , mod, remainder);
	
	for (i = 0; i < rep; i++)
	{
		execResult = moveRodInMachine(80, pin_2_14_Backward);
		delay(10);
	}
	if (remainder != 0)
	{
		execResult = moveRodInMachine(remainder, pin_2_14_Backward);
	}

	exit(execResult);
}
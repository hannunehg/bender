#include <wiringPi.h>
#include <stdio.h>
#include <stdlib.h>
#include "common.h"

int main (int argc, char ** argv)
{
	int execResult = resetPins();
	if (execResult != 0)
	{
	    printf(stderr, "ReSet all pins API failed in  forward.c = %d\n", execResult);
	    return 1;
	}
	return 1;
}
#include <wiringPi.h>
#include <stdio.h>
#include <stdlib.h>
#include "common.h"

int main (int argc, char ** argv)
{
	
	picoInit();

	int execResult = resetPins();
	if (execResult != 0)
	{
	    printf(stderr, "ReSet all pins API failed in  forward.c = %d\n", execResult);
	    return 1;
	}
	pinMode (pin_PIC_Enable	, OUTPUT);
	digitalWrite (pin_PIC_Enable, LOW);

	system("pkill forward");
        system("pkill backward");
        system("pkill bend");
        system("pkill cut");

	/*
	pinMode (pin_read_move, INPUT);
	pullUpDnControl(pin_read_move,PUD_OFF);
	pinMode(pin_1_13_Forward, OUTPUT);
	digitalWrite(pin_1_13_Forward, LOW);
	delay(1000);
	digitalWrite(pin_1_13_Forward, HIGH);
	*/


	return 0;
}

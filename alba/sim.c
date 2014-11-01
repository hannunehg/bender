#include <wiringPi.h>
#include <stdio.h>
#include <stdlib.h>


int main (int argc, char ** argv)
{
    //Setup pi
	if (wiringPiSetup() == -1) 
	{
		fprintf(stderr, "wiringPiSetup call failed\n");
		exit(1);
	}

	# define PIN 14
	pinMode (PIN, OUTPUT);
	
	int max = atoi(argv[1]);
	int index;
	for(index = 0;index < max; index++)
	{
		digitalWrite(PIN, HIGH);
		delay(10);
		digitalWrite(PIN, LOW);
	}
	
	return 0;
}

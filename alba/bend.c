#include <wiringPi.h>
#include <stdio.h>
#include <stdlib.h>
#include "common.h"

unsigned short CalcAngle(int angleVal)
{ 
	# define ZEROPOINT 872 	//calclauted val should be 761
	return (unsigned short)(angleVal*4 + ZEROPOINT + 8);
}

int dataAvailable = 0;
char readByte0 = 0, readByte1 = 0, readByte2 = 0, readByte3 = 0;
unsigned short sensorRead = 0;
unsigned short holdValue = 0;
# define NumberOfBytes 4
char arrOfNibbles[NumberOfBytes] = {0, 0, 0, 0};

unsigned short readAngleSensor(int fd)
{
	for (;;) //TODO: make it depends on timeout! not forever!
	{
		dataAvailable = serialDataAvail(fd);
		if(dataAvailable >= 32)
		{
			readByte0 = serialGetchar(fd);
			readByte1 = serialGetchar(fd);
			readByte2 = serialGetchar(fd);
			readByte3 = serialGetchar(fd);
			arrOfNibbles[(readByte0 & 0xF0) >> 4] = readByte0 & 0x0F;
			arrOfNibbles[(readByte1 & 0xF0) >> 4] = readByte1 & 0x0F;			
			arrOfNibbles[(readByte2 & 0xF0) >> 4] = readByte2 & 0x0F;
			arrOfNibbles[(readByte3 & 0xF0) >> 4] = readByte3 & 0x0F;
			
			//Construct a reading from the three nibbles
			//			  arrOfNibbles[0] will be zero - so we'll skip it
			sensorRead =  arrOfNibbles[1];
			sensorRead |= (arrOfNibbles[2] << 4);
			holdValue  =  arrOfNibbles[3];
			sensorRead |= (holdValue << 8);
			
			return sensorRead;
		}
		if(dataAvailable == -1)
		{	
			fprintf (stderr, "Unable to check data avail: %s\n", strerror (errno));
			sensorRead = -1;
			break;
		}
	}
}

int setAngleValue(int fd, int destinationAngle)
{  
	# define ERRORVALUP 280
	# define ERRORVALDown 280
	int apiRes = 0;
	unsigned short initialRead = readAngleSensor(fd);
	printf("initialRead(hex) = %x, initialRead(dec) = %d\n", initialRead, initialRead);
	
	unsigned short destination = CalcAngle(destinationAngle);
	
	//verify current is in range
	if (abs(destination - initialRead) <= 150)
	{
		printf("Abort because angle is already reset\n");
		return 0;
	}
	
	apiRes = setAllPins(pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_ON, pin_ON,pin_ON);
    if (apiRes != 0)
    {
		apiRes = 1;
	   	goto FINAIZE;
    }

	unsigned short currentRead = initialRead;
	unsigned short nextRead = 0;
	if (currentRead >= destination) //box is down -> move up
	{
		destination += ERRORVALUP;
		serialFlush(fd);
		digitalWrite (pin_4_16_BendUp, pin_ON);
		while (1)
		{
			currentRead = readAngleSensor(fd);
			nextRead = readAngleSensor(fd);
			if (currentRead <= destination && nextRead <= destination)
			{
				digitalWrite (pin_4_16_BendUp, pin_OFF);
				printf("stopRead(hex) = %x, stopRead(dec) = %d\n", currentRead, currentRead);
				break;
			}
		}
	}
	else // currentRead < destination //box is up -> move down
	{
		destination -=  ERRORVALDown;
		serialFlush(fd);
		digitalWrite (pin_3_15_BendDown, pin_ON);
		while (1)
		{
			currentRead = readAngleSensor(fd);
			nextRead = readAngleSensor(fd);
			if (currentRead >= destination && nextRead >= destination)
			{
				digitalWrite (pin_3_15_BendDown, pin_OFF);
				printf("stopRead(hex) = %x, stopRead(dec) = %d\n", currentRead, currentRead);
				break;
			}
		}
	}

FINAIZE:
	apiRes = resetPins();
	if (apiRes != 0)
    {
		apiRes = 2;
    }

	//Woooow!
	return apiRes;
}

int resetBender(int fd) 
{
	# define RESET_ANGLE -65
	return setAngleValue(fd, RESET_ANGLE);
}

int main (int argc, char ** argv)
{
	int angleValue = 0;
	int fd;
    	int setAllPinsExecResult = 0;
	int procRes = 0;
	
	picoInit();

	//Check args
	if (argc != 2)
	{
		fprintf(stderr, "Arguments error\n");
		exit(2);
    	}
	angleValue = atoi(argv[1]);
	printf("angleValue = %d\n", angleValue);
	
	

	// set mode (no serial communication started)
	procRes = setSensor(Sensor_Bend_Angle0);
	if (procRes != 0)
	{
		fprintf(stderr, "setSensor API failed in bend.c");
		procRes = 4;
		goto FINISH_WITHOUT_CLOSING_SERIAL;
	}
	
	// start serial here
	if ((fd = serialOpen ("/dev/ttyAMA0", 9600)) < 0)
	{
	    	fprintf(stderr, "Unable to open serial device: %s\n", strerror (errno)) ;
	    	procRes = 5;
	    	goto FINISH;
	}

	procRes = resetBender(fd);
	if (procRes != 0)
	{
		fprintf(stderr, "1st resetBender(fd) call failed = %d\n", procRes) ;
		procRes = 6;
		goto FINISH;
	}
	
	procRes = setAngleValue(fd, angleValue);
	if (procRes != 0)
	{
		fprintf(stderr, "setAngleValue to bend call failed\n") ;
		procRes = 7;
		goto FINISH;
	}

	procRes = resetBender(fd);
	if (procRes != 0)
	{
		fprintf(stderr, "2nd resetBender(fd) call failed\n") ;
		procRes = 8;
		goto FINISH;
	}
	
	unsigned short endRead = readAngleSensor(fd);
	printf("endRead(hex) = %x, endRead(dec) = %d\n", endRead, endRead);

FINISH:
	serialClose(fd);
FINISH_WITHOUT_CLOSING_SERIAL:
	//disable PIC
	digitalWrite (pin_PIC_Enable, LOW);


delay(150);
    exit(procRes); 
}



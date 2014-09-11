#include <wiringPi.h>
#include <stdio.h>
#include <stdlib.h>
#include "common.h"

unsigned short readAngleSensor(int fd)
{
	int dataAvailable = 0;
	char char0 = 0;
	char char1 = 0;
	char char2 = 0;
	char char3 = 0;
	unsigned short sensorRead = 0;
	unsigned short temp = 0;
	
	for (;;)
	{

		dataAvailable = serialDataAvail(fd);

		if(dataAvailable >= 24)
		{
			//printf("data avail = %d\n",dataAvailable );
			char0 = serialGetchar (fd);
			char1 = serialGetchar (fd);
			char2 = serialGetchar (fd);
			char3 = serialGetchar (fd);
			//printf("char0 = %x\t\tchar1 = %x\t\tchar2 = %x\tchar3 = %x\n", char0, char1, char2, char3);
			if ((char1 & 0x10) == 0x10)
			{
				
				char1 &= 0x0F;
				sensorRead |= char1;
			}
			if ((char2 & 0x20) == 0x20)
			{
				char2 &= 0x0F;
				sensorRead |= (char2 << 4);
			}
			if ((char3 & 0x30) == 0x30)
			{
				char3 &= 0x0F;
				temp = char3;
				sensorRead |= (temp << 8);
			}
			//printf("sensorRead(hex) = %x, sensorRead(dec) = %d\n", sensorRead, sensorRead);
			break;
		}
		if(dataAvailable == -1)
		{	
			fprintf (stderr, "Unable to check data avail: %s\n", strerror (errno));
			break;
		}
		
	}
	
	return sensorRead;
}

int setAngleValue(int fd, unsigned short destination)
{  
	int setAllPinsExecResult = 0;
	// read current
	unsigned short initialRead = readAngleSensor(fd);
	printf("initialRead(hex) = %x, initialRead(dec) = %d\n", initialRead, initialRead);

	//TODO: verify current is in range

	unsigned short currentRead = initialRead;
	if (currentRead >= destination) //box is down -> move up
	{
		setAllPinsExecResult = setAllPins(pin_OFF,pin_OFF,pin_OFF,pin_ON,pin_OFF,pin_OFF,pin_OFF,pin_ON, pin_OFF,pin_OFF);
        	if (setAllPinsExecResult != 0)
        	{
	   		goto FINAIZE;
        	}
		while (1)
		{
			currentRead = readAngleSensor(fd);
			if (currentRead <= destination)
				break;
		}
	}
	else // currentRead < destination //box is up -> move down
	{
		setAllPinsExecResult = setAllPins(pin_OFF,pin_OFF,pin_ON,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_ON, pin_OFF,pin_OFF);
        	if (setAllPinsExecResult != 0)
        	{
	   		goto FINAIZE;
        	}
		while (1)
		{
			currentRead = readAngleSensor(fd);
			if (currentRead >= destination)
				break;
		}
	}

FINAIZE:
	setAllPinsExecResult = resetPins();
        if (setAllPinsExecResult != 0)
        {
	  return 3;
        }
	
	//Woooow!
	return 0;
}

int resetBender(int fd ) {
	setAngleValue(fd, 919);
}
int main (int argc, char ** argv)
{
	int angleValue = 0;
	int fd;
	int i;
        int setAllPinsExecResult = 0;
	if (argc != 2)
        {
		fprintf(stderr, "Arguments error\n");
		exit(1);
        }
	angleValue = atoi(argv[1]);
	printf("angleValue = %d\n", angleValue);

	// HW Check
	if (system("grep 00000000440fb444  /proc/cpuinfo > /dev/null"))
	{
		fprintf(stderr, "HW ERROR #1\n");
		return 2;
	}
	
	if (wiringPiSetup() == -1) return 2;

	// set mode (no serial communication started)
	int execSetSensor = setSensor(Sensor_Bend_Angle0);
	if (execSetSensor != 0)
	{
		perror("setSensor API failed inside readAngleSensor API");
		exit(1);
	}

	// start serial here
	if ((fd = serialOpen ("/dev/ttyAMA0", 4800)) < 0)
	{
	    fprintf (stderr, "Unable to open serial device: %s\n", strerror (errno)) ;
	    return 1 ;
	}
	
        //Reset position to bend before the bend
	// set angle(initial position)	
	//for(i = 0; i < 100; i++)
	//{
		//readAngleSensor(fd);
		//delay(1000);
	
	//printf("serial flushed\n" );
	//readAngleSensor(fd);
	//}

	resetBender(fd);

	//Bend
	//Bend position to bend after the bend
	/*setAllPinsExecResult = setAllPins(pin_OFF,pin_OFF,pin_ON,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_ON, pin_OFF,pin_OFF);
        if (setAllPinsExecResult != 0)
        {
	   exit(3);
        }*/	

	/*setAllPinsExecResult = resetPins();
        if (setAllPinsExecResult != 0)
        {
	   exit(3);
        }*/

	/*setAllPinsExecResult = resetAngle();
        if (setAllPinsExecResult != 0)
        {
	   exit(3);
        }*/

	serialClose(fd);
	//disable PIC
	digitalWrite (pin_PIC_Enable, LOW);
        return 0;
}



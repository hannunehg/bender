#include <wiringPi.h>
#include <stdio.h>
#include <errno.h>
#include <wiringSerial.h>

#define pin_ON LOW
#define pin_OFF HIGH

#define Sensor_Pin_ON HIGH
#define Sensor_Pin_OFF LOW

#define pin_1_13_Forward  14
#define pin_2_14_Backward  13
#define pin_3_15_BendDown  1
#define pin_4_16_BendUp  4
#define pin_5_17_CutPushUp  5
#define pin_6_18_CutPullDown  6
#define pin_7_20_LiftNearRollers  10
#define pin_8_22_EnableMotors  11
#define pin_9_34_HoldNearRollers  2
#define pin_10_35_HoldFarRollers  3

#define pin_PIC_Enable  8
//Sensors Pins
#define pin_11_Sensor_Selector1 7
#define pin_12_Sensor_Selector2 9

#define Sensor_ADC_Cut 1
#define Sensor_Bend_Angle0 2
#define Sensor_Bend_Angle1 3
#define Sensor_Move_Length 4


//FORWARD - BACKWARD

# define UNITPERCMForward 13
# define UNITPERCMBackward 16

# define additiveForwardCorrection -0.5
# define additiveBackwardCorrection -0.5
# define pin_read_move 12

int moveRodInMachine(int value, int pinID)
{
	//int value = 0;
        int execResult = 0;
	int count = 0;	
	int state;
	int current_state;
	int index = 0;
        int wasValueReached = 0;
		
	pinMode (pin_read_move, INPUT);

	// HW Check
	if (system("grep 00000000440fb444  /proc/cpuinfo > /dev/null"))
	{
		fprintf(stderr, "HW ERROR #1\n");
		return 1;
	}
	
	/*
	float dim = atof(arg);
	if (pinID == pin_1_13_Forward)
	{
		printf("additiveForwardCorrection\n");
		dim = dim + additiveForwardCorrection;
		value = (int)(dim * UNITPERCMForward); //convert CM to sesnor readings
	}
	else
	{
		printf("additiveBackwardCorrection\n");
		dim = dim + additiveBackwardCorrection;
		value = (int)(dim * UNITPERCMBackward); //convert CM to sesnor readings
	}
	printf("dim = %f\n", dim);
	*/
	
	
	printf("value = %d\n", value);
	
	//enable motors
	execResult = setAllPins(pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_ON, pin_ON,pin_ON);
        if (execResult != 0)
        { 
	   printf(stderr, "Set all pins API failed in  forward.c = %d\n", execResult);
	   resetPins();
	   return 3;
        }
	
	//start moving forward
	digitalWrite (pinID, pin_ON);
	
	//wait till the sensor reads this value
	state = digitalRead (pin_read_move);
	while(count != 50000000)
	{	
		current_state = digitalRead (pin_read_move);
		if (current_state != state){
			state = current_state;
			index++;
			//printf("index = %d\n", index);
			if (index >= value)
			{
				digitalWrite (pinID, pin_OFF);
				wasValueReached = 1;
				break;
			}
		}
		count++;
	}
	
	printf("reached index = %d\n", index);	

	execResult = resetPins();
	if (execResult != 0)
	{
	    printf(stderr, "ReSet all pins API failed in  forward.c = %d\n", execResult);
	    return 4;
	}

	if (wasValueReached != 1)
	{
		printf(stderr, "Value/Index was not reached /n");
		return 5;
	}
	
	return 0;
}

int waitOnSensor(int sensorID, int value)
{
	int exec = setSensor(sensorID);
	if  (!exec)
	{
		exit(exec);
	}
	delay(2000);
	return 0;
}

int setSensor(int sensorID)
{
	int exec = 0;
	switch(sensorID)
	{
		case Sensor_ADC_Cut:
			exec = setSensorPins(Sensor_Pin_OFF, Sensor_Pin_OFF);
			break;
		case Sensor_Move_Length:
			exec = setSensorPins(Sensor_Pin_ON, Sensor_Pin_ON);
			break;
		case Sensor_Bend_Angle0:
			exec = setSensorPins(Sensor_Pin_ON, Sensor_Pin_OFF);
			break;
	}
	return exec;
}

int setSensorPins(int p1, int p2) 
{
	pinMode (pin_11_Sensor_Selector1		, OUTPUT);
	pinMode (pin_12_Sensor_Selector2		, OUTPUT);
	pinMode (pin_PIC_Enable					, OUTPUT);

	digitalWrite (pin_11_Sensor_Selector1	, p1);  
	digitalWrite (pin_12_Sensor_Selector2	, p2);     
    digitalWrite (pin_PIC_Enable			, HIGH);
    
	delay(150);
	return 0;  
}

int setAllPins(int p1, int p2, int p3, int p4,int p5, int p6,int p7, int p8,int p9, int p10) {

	if (wiringPiSetup() == -1) return 2;

	pinMode (pin_1_13_Forward		, OUTPUT);
	pinMode (pin_2_14_Backward		, OUTPUT);
	pinMode (pin_3_15_BendDown		, OUTPUT);
	pinMode (pin_4_16_BendUp		, OUTPUT);
	pinMode (pin_5_17_CutPushUp		, OUTPUT);
	pinMode (pin_6_18_CutPullDown		, OUTPUT);
	pinMode (pin_7_20_LiftNearRollers	, OUTPUT);
	pinMode (pin_8_22_EnableMotors		, OUTPUT);
	pinMode (pin_9_34_HoldNearRollers	, OUTPUT);
	pinMode (pin_10_35_HoldFarRollers	, OUTPUT);

	digitalWrite (pin_1_13_Forward			, p1);  
	digitalWrite (pin_2_14_Backward		, p2);  
	digitalWrite (pin_3_15_BendDown		, p3);  
	digitalWrite (pin_4_16_BendUp			, p4);  
	digitalWrite (pin_5_17_CutPushUp		, p5);  
	digitalWrite (pin_6_18_CutPullDown		, p6);  
	digitalWrite (pin_7_20_LiftNearRollers	, p7);  
	  
	digitalWrite (pin_9_34_HoldNearRollers	, p9);  
	digitalWrite (pin_10_35_HoldFarRollers	, p10); 
	
	// leave last
	digitalWrite (pin_8_22_EnableMotors	, p8);
	return 0;  
}

int initALBA()
{
	int execResult = 0;
	//pull down cut
	execResult = setAllPins(pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_ON,pin_ON,pin_ON, pin_ON,pin_ON);
    if (execResult != 0)
    {
	   return 1;
    }
    
	delay(1000);
	
	//push up
	execResult = setAllPins(pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_ON,pin_OFF,pin_ON,pin_ON, pin_ON,pin_ON);
    if (execResult != 0)
    {
	   return 2;
    }
    
	delay(338);
	
	execResult = resetPins();
	if (execResult != 0)
    {
	   return 3;
    }

	return 0;
}

int resetPins()
{
	int setAllPinsExecResult = 0;
	setAllPinsExecResult = setAllPins(pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF, pin_OFF,pin_OFF);
    if (setAllPinsExecResult != 0)
    {
	  return 1;
    }
    return 0;
}

int resetAngle()
{
  int fd;
  int i;
  char read;
  char read2;
  char high_char;
  char low_char;
  unsigned short initialRead = 0;
  unsigned short desiredRead = 919;
  unsigned short currentRead = 0;
  int resetPinsResult = 0;

  return 0;
}
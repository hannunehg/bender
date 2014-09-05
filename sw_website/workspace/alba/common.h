#include <wiringPi.h>
#include <stdio.h>


#define pin_1_13_Forward  14
#define pin_2_14_Backward  13
#define pin_3_15_BendDown  1
#define pin_4_16_BendUp  4
#define pin_5_17_CutPushUp  5
#define pin_6_18_CutPullDown  6
#define pin_7_20_LiftNearRollers  10
#define pin_8_22_EnableMotors  11
#define pin_9_34_HoldNearRollers  12
#define pin_10_35_HoldFarRollers  3

#define pin_ON LOW
#define pin_OFF HIGH

void printHello(){

printf("Hello World");
}


int setAllPins(int p1, int p2, int p3, int p4,int p5, int p6,int p7, int p8,int p9, int p10) {

	if (wiringPiSetup() == -1) return 2;

	pinMode (pin_1_13_Forward			, OUTPUT);
	pinMode (pin_2_14_Backward		, OUTPUT);
	pinMode (pin_3_15_BendDown		, OUTPUT);
	pinMode (pin_4_16_BendUp			, OUTPUT);
	pinMode (pin_5_17_CutPushUp		, OUTPUT);
	pinMode (pin_6_18_CutPullDown		, OUTPUT);
	pinMode (pin_7_20_LiftNearRollers	, OUTPUT);
	pinMode (pin_8_22_EnableMotors	, OUTPUT);
	pinMode (pin_9_34_HoldNearRollers	, OUTPUT);
	pinMode (pin_10_35_HoldFarRollers	, OUTPUT);


	digitalWrite (pin_1_13_Forward			, p1);  
	digitalWrite (pin_2_14_Backward		, p2);  
	digitalWrite (pin_3_15_BendDown		, p3);  
	digitalWrite (pin_4_16_BendUp			, p4);  
	digitalWrite (pin_5_17_CutPushUp		, p5);  
	digitalWrite (pin_6_18_CutPullDown		, p6);  
	digitalWrite (pin_7_20_LiftNearRollers	, p7);  
	digitalWrite (pin_8_22_EnableMotors	, p8);  
	digitalWrite (pin_9_34_HoldNearRollers	, p9);  
	digitalWrite (pin_10_35_HoldFarRollers	, p10);   
 
}

int resetPins(){
	setAllPins(pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF);
}


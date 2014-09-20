#include <wiringPi.h>
#include <stdio.h>
#include <stdlib.h>
#include "common.h"


int main (int argc, char ** argv)
{
	int forwardValue = 0;
        int setAllPinsExecResult = 0;
	if (argc != 2)
        {
		fprintf(stderr, "Arguments error\n");
		exit(1);
        }
	forwardValue = atoi(argv[1]);
	printf("forwardValue = %d\n", forwardValue);


	// HW Check
	if (system("grep 00000000440fb444  /proc/cpuinfo > /dev/null"))
	{
		fprintf(stderr, "HW ERROR #1\n");
		return 2;
	}


	setAllPinsExecResult = setAllPins(pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_OFF,pin_ON, pin_ON,pin_ON);
        if (setAllPinsExecResult != 0)
        {
	   exit(3);
        }
	
	
	
	int count = 0;	
	int count_ones = 0;
	int count_zeros = 0;
	
	int state;
	int current_state;
	int state_tra = 0;

	#define array_size 500

	int arr[array_size] = {0}; 
	int arr_zero[array_size] = {0};
	int index = 0;


	#define pin_read_move 2
	pinMode (pin_read_move, INPUT);
	
	//digitalWrite (pin_1_13_Forward, pin_ON);


	state = digitalRead (pin_read_move);
	
	
	while(count != 500)
	{	
		current_state = digitalRead (pin_read_move);

		if (current_state != state){
			state = current_state;
			index++;
			state_tra++;	
		}

		if (current_state == HIGH)
		{
			arr[index]++;
			count_ones++;
		} else {
			arr_zero[index]++;
			count_zeros++;
		}
		

		count++;
		delay(10);
		
	}
	//digitalWrite (pin_1_13_Forward, pin_OFF);

	
	
	int k = 0;

	for(k=0; k< array_size; k++){
		printf("arr_one: %d, arr_zero: %d, k=%d\n",arr[k], arr_zero[k], k);
	}
	printf("count_zeros = %d, count_ones = %d\n", count_zeros, count_ones);
	printf("reached index = %d\n", index);	

	//delay(300);
	//digitalWrite (pin_1_13_Forward, pin_OFF);

	resetPins();

        return 0;
}



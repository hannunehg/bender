rm forward
rm backward
rm cut
rm bend
rm init_alba
rm reset
gcc -lwiringPi forward.c -o forward -lm
gcc -lwiringPi backward.c -o backward -lm
gcc -lwiringPi cut.c -o cut
gcc -lwiringPi bend.c -o bend
gcc -lwiringPi init_alba.c -o init_alba
gcc -lwiringPi reset.c -o reset
#gcc testAngleSensor.c  -o testAngleSensor -lwiringPi

cp forward /var/www/workspace/alba/ -vf
cp backward /var/www/workspace/alba/ -vf
cp cut /var/www/workspace/alba/ -vf
cp bend /var/www/workspace/alba/ -vf
cp init_alba /var/www/workspace/alba/ -vf
cp reset     /var/www/workspace/alba/ -vf

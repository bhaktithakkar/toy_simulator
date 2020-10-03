# toy_simulator

1) Download the zip from the repository.

2) Unzip the folder in any php version installed and navigate to it.

3) Run the file StartGame.php from console which will ask for input commands

4) Pass commands as below examples and "Exit" to end 

### Example a

    PLACE 0,0,NORTH
    MOVE
    REPORT

Expected output:

    0,1,NORTH

### Example b

    PLACE 0,0,NORTH
    LEFT
    REPORT

Expected output:

    0,0,WEST

### Example c

    PLACE 1,2,EAST
    MOVE
    MOVE
    LEFT
    MOVE
    REPORT

Expected output

    3,3,NORTH

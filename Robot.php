<?php
include 'Table.php';
include 'InputCommand.php';

class Robot{

  private $table;
  private $isExit;
  
  /**
   * @var integer Robot horizontal position on the table
   */
  protected $x;
  /**
   * @var integer Robot vertical position on the table
   */
  protected $y;
  /**
   * @var string direction to move on the table
   */
  protected $direction;
  /**
   * @var array Direction map
   */
  protected $directionMap = [
    'NORTH' => 'EAST',
    'EAST' => 'SOUTH',
    'SOUTH' => 'WEST',
    'WEST' => 'NORTH'
    ];
    
    public function __construct(){
        $this->table = new Table(5,5);
    }
  
    /** 
     * Parse and execute
     */
    public function execute(){
        while($this->isExit !== true){
			//Read input
			$input = new InputCommand();
			$command = $input->readCommand();
		
			//Parse and extract the command
			extract($this->parseCommand($command));
						
			//Execute method with arguments
			switch($method){
				case 'EXIT':
					$this->isExit = true;
					break;
				case 'PLACE':
					$this->place($x,$y,$direction);
					break;
				case 'MOVE':
					$this->move();
					break;
				case 'LEFT':
				case 'RIGHT':
					$this->rotate($method);
					break;
				case 'REPORT':
					echo $this->report();
					break;
			}	
		}
    }
    
  
  
  
}

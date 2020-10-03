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
    
	/*
	 * Parse command, extracting method, x, y and direction where applicable
	 * @param string command
	 * @return array
	 */
	protected function parseCommand($command){
		//Extract method and arguments from command
		preg_match(
            '/^' .
            '(?P<method>' . $this->getMethods('|') . ')' .
            '(\s' .
                '(?P<x>\d+)\s?,' .
                '(?P<y>\d+)\s?,' .
                '(?P<direction>' . $this->getDirections('|') . ')' .
            ')?' .
            '$/',
            strtoupper($command),
            $args
        );
        // Extract captured arguments with fallback defaults
        $method = $args['method'] ?? null;
        $x = $args['x'] ?? 0;
        $y = $args['y'] ?? 0;
        $direction = $args['direction'] ?? 'NORTH';

        return compact('method', 'x', 'y', 'direction');
	}
	
	/*
	 * Place robot on the table
	 * @params $x, $y and $direction
	 * @return void
	 */
	public function place($x,$y,$direction){
		//Check if the positions are within table boundaries
		if(!$this->table->withinBounds($x,$y)){
			return sprintf('Coordinates (%d,%d) are outside table boundaries.', $x, $y).PHP_EOL; 
		}
		
		//Check if the direction is permissible
		if(!$this->isPermissibleDirection($direction)){
			return sprintf('Direction (%s) is not recognised.',$direction).PHP_EOL;
		}
		
		//Set the robot position and direction
		$this->x = $x;
		$this->y = $y;
		$this->direction = $direction;
	}
	
	/*
	 * Move the robot one unit in the current direction
	 */
	public function move(){
		//Check if the place command is executed earlier		 
		if(!$this->isPlaced()) return;
		 
		//Get current position
		$x = $this->x;
		$y = $this->y;
		 
		//Find new position based on the current position
		switch($this->direction){
			case 'NORTH':
				$y +=1;
				break;
			case 'EAST':
				$x +=1;
				break;
			case 'SOUTH':
				$y -=1;
				break;
			case 'WEST':
				$x -=1;
				break;
		}
		
		//Check if new position is within table boundaries
		if(!$this->table->withinBounds($x,$y)) return;
		
		//Set new position 
		$this->x = $x;
		$this->y = $y;
	}
	
	/*
	 * Rotate robot
	 */
	public function rotate($rotationSide){
		//Check if place command is executed earlier
		if(!$this->isPlaced()) return;
		
		$this->direction = $this->findDirectionFromRotation($rotationSide);
	}
	
	/*
	 * Show robot report status
	 */
	public function report(){
		//Check if place command is executed earlier
		if(!$this->isPlaced()) return;
		
		return sprintf('%d,%d,%s', $this->x, $this->y, $this->direction).PHP_EOL;
	}
	
	/*
	 * Check whether robot has been placed on the table
	 * @return boolean
	 */
	public function isPlaced(){
		return (! is_null($this->x) && ! is_null($this->y));
	}
	
	/*
	 * Get permissible methods as array or string
	 * @param string|null $separator
	 * @return array|string
	 */
	protected function getMethods($separator = null){
		$methods = [
			'PLACE','MOVE','LEFT','RIGHT','REPORT','EXIT'
		];
		
		return is_null($separator) ? $methods : implode($separator, $methods);		
	}
	
	/*
	 * Get permissible direction as array or string
	 * @param string|null $separator
	 * @return array|string
	 */
	protected function getDirections($separator = null){
		$directions = [
			'NORTH','EAST','SOUTH','WEST'
		];
		
		return is_null($separator) ? $directions : implode($separator, $directions);
	}
	
	/*
	 * Get permissible rotation as array or string
	 * @param string|null $separator
	 * @return array|string
	 */
	protected function getRotations($separator = null){
		$rotations = [
			'LEFT','RIGHT'
		];
		
		return is_null($separator) ? $rotations : implode($separator, $rotations);
	}
	
	/*
	 * Find robot direction from given rotationSide
	 * @param string rotationSide
	 * @return string
	 */
	protected function findDirectionFromRotation($rotationSide){
		
		if(!$this->isPermissibleRotation($rotationSide)){
			return sprintf('Rotation (%s) is not recognised',$rotationSide).PHP_EOL;
		}
		
		//Determine direction of rotation - clockwise/anti-clockwise
		$clockwise = ($rotationSide === 'RIGHT');
		$mappings = $clockwise ? $this->directionMap : array_flip($this->directionMap);
		
		return $mappings[$this->direction];		
	}
	
	/* Check whether give direction is a permissible direction
	 * @param string $direction
	 * @return boolean
	 */
	protected function isPermissibleDirection($direction){
		return in_array($direction, $this->getDirections());
	}
  
	/* Check whether give rotation is a permissible rotation
	 * @param string $rotation
	 * @return boolean
	 */
	protected function isPermissibleRotation($rotation){
		return in_array($rotation, $this->getRotations());
	}
}

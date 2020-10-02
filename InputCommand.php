<?php

class InputCommand {
    
    /**
     * This function reads input using STDIN
     * @return string
     */
     public function readCommand() : string {
        $input = readline("Enter a command : ");
        return $input;
     }     
}

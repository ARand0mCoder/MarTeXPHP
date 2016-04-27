<?php
namespace MarTeX;

interface IMarTeXmodule {
    // Handle should handle commands added by register
    public function handleCommand($command, $argument);
    public function registerCommands();
    public function handleEnvironment($environment, $options, $text);
    public function registerEnvironments();
    public function reset();
}

abstract class MarTeXmodule {
    // Parent variable, so you can access the envvars
    public $MarTeX;

    public function valisaniArgument($argument, $number, $valisani) {
        if($number == 1 && is_array($argument)) {
            $this->MarTeX->parseError("(MarTeX) Warning: Too many arguments supplied to command.");
            return $argument[0];
        }
        if (($number > 1 && !is_array($argument) ) || ( $number > 1 && count($argument) < $number)) {
            $this->MarTeX->parseError("(MarTeX) Error: Not enough arguments supplied to command.");
            return array_fill(0, $number, ""); 
        }
        if($number > 1 && count($argument) > $number) {
            $this->MarTeX->parseError("(MarTeX) Warning: Too many arguments supplied to command.");
            return $argument;
        }
        return $argument; 
    }
    
    public static function str_replace_all($from, $to, $subject)
    {
        $from = '/'.preg_quote($from, '/').'/';

        return preg_replace($from, $to, $subject);
    }
    
    public static function str_replace_first($from, $to, $subject)
    {
        $from = '/'.preg_quote($from, '/').'/';

        return preg_replace($from, $to, $subject, 1);
    }
    
    public function reset() {
        return true;
    }
    
    public function registerCommands() {
        return array();
    }
    
    public function handleCommand($command, $argument) {
        return "";
    }
    
    public function registerEnvironments() {
        return array();
    }
    
    public function handleEnvironment($env, $opt, $txt) {
        return "";
    }
    
}

class ModuleTools {
    public static function setVar($var, $value) {
        return "$".$var."%".$value."$";
    }
    
    public static function getVars($text) {
        $vars = array();
        $ts = explode("$",$text);
        
        for($i = 0; $i < count($ts); $i+=1) {
            $kv = explode("%", $ts[$i]);
            if (count($kv) == 2) {
                $vars[$kv[0]] = $kv[1];
            }
        }
        return $vars;
    }
    
    public static function getText($text) {
        $txt = "";
        $ts = explode("$", $text);
        for($i = 0; $i < count($ts); $i+=1) {
            $kv = explode("%", $ts[$i]);
            if ($kv[0] == $ts[$i]) {
                $txt .= $kv[0];
            }
        }
        return $txt;
    }
}
?>

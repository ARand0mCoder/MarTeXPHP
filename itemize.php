<?php
namespace MarTeX;

require_once (__DIR__."/module.php");

class Itemize extends MarTeXmodule{
    
    public function registerCommands() {
        return array("itemize/item", "enumerate/item", "itemize/setmarker", "enumerate/setmarker");
    }
    
    public function registerEnvironments() {
        return array("itemize", "enumerate");
    }
    
    public function handleCommand($command, $argument) {
        $argument = $this->valisaniArgument($argument, 1, "String");
        switch($command) {
            case "itemize/item":
            case "enumerate/item":
                return "<li>".$argument."</li>\n";
            case "itemize/setmarker":
            case "enumerate/setmarker":
                return ModuleTools::setVar("marker", $argument);
        }
    }
    
    public function handleEnvironment($env, $option, $text) {
        $texts = ModuleTools::getText($text);
        $vars = ModuleTools::getVars($text);
        $text = $texts;
        
        if (!array_key_exists("marker", $vars)) {
            if ($env == "enumerate") {
                return "<ol>\n".$text."</ol>";
            }
            else {
                return "<ul>\n".$text."</ul>";
            }
        }
        else {
            switch($vars["marker"]) {
                case "square":
                    return "<ul style='list-style-type:square'>\n".$text."</ul>";
                case "bullet":
                    return "<ul style='list-style-type:bullet'>\n".$text."</ul>";
                case "circle":
                    return "<ul style='list-style-type:circle'>\n".$text."</ul>";
                case "none":
                    return "<ul style='list-style-type:none'>\n".$text."</ul>";
                case "numbers":
                    return "<ol type='1'>\n".$text."</ol>";
                case "letters":
                    return "<ol type='a'>\n".$text."</ol>";
                case "LETTERS":
                    return "<ol type='A'>\n".$text."</ol>";
                case "roman":
                    return "<ol type='i'>\n".$text."</ol>";
                case "ROMAN":
                    return "<ol type='I'>\n".$text."</ol>";
                default:
                    $this->raiseError($vars["marker"]." is not a valid itemize marker.");
                    if ($env == "enumerate") {
                        return "<ol>\n".$text."</ol>";
                    }
                    else {
                        return "<ul>\n".$text."</ul>";
                    }
            }
        }
    }
}

?>

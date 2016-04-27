<?php
namespace MarTeX;

require_once (__DIR__."/module.php");

class Document extends MarTeXmodule implements IMarTeXmodule {
    public function registerCommands() {
        return array("section", "subsection", "subsubsection", "textbf", "textit", "hline", "ref", "refpass", "define", "newline", "paragraph", "title"); 
    }
    
    public function handleCommand($command, $argument) {

        switch($command) {
            case "section":
                $argument = $this->valisaniArgument($argument, 1, "String/nowhitespace");
                return "<h2>".$argument."</h2>";
            case "subsection":
                $argument = $this->valisaniArgument($argument, 1, "String/nowhitespace");
                return "<h3>".$argument."</h3>";
            case "subsubsection":
                $argument = $this->valisaniArgument($argument, 1, "String/nowhitespace");
                return "<h4>".$argument."</h4>";
            case "textbf":
                $argument = $this->valisaniArgument($argument, 1, "String/nowhitespace");
                return "<b>".$argument."</b>";
            case "textit":
                $argument = $this->valisaniArgument($argument, 1, "String/nowhitespace");
                return "<i>".$argument."</i>";
            case "paragraph":
                $argument = $this->valisaniArgument($argument, 1, "String/nowhitespace");
                return "<p>".$argument."</p>";
            case "title":
                $argument = $this->valisaniArgument($argument, 1, "String/nowhitespace");
                return "<h1>".$argument."</h1>";
            case "hline":
                $argument = $this->valisaniArgument($argument, 0, "");
                return "<hr>";           
            case "ref":
                $argument = $this->valisaniArgument($argument, 1, "String/nowhitespace");    
                $reference = $this->MarTeX->getGlobalVar("label:".$argument);
                if ($reference === false) {
                    // This label is not defined, but may be defined below this call.
                    // Therefore, change the functioncall to something that is impossible to call as user
                    // So we can try again on the next iteration
                    return "\\refpass{".$argument."}";
                }
                return $reference;
            case "refpass":
                $argument = $this->valisaniArgument($argument, 1, "String/nowhitespace");    
                $reference = $this->MarTeX->getGlobalVar("label:".$argument);
                if ($reference === false) {
                    // This label is not defined on second pass, throw warning.
                    // Note, this means backward declaration of dynamic labels is not allowed, but forwards is!
                    $this->MarTeX->parseError("(MarTeX/document) Error: reference label '".$argument."' was not declared.");
                    return "?";
                }
                return $reference;
            case "define":
                $argument = $this->valisaniArgument($argument, 2, array("String", "String"));
                $this->MarTeX->setGlobalVar($argument[0], $argument[1]);
                return "";
            case "newline": 
                return "<br>";               
        }
    }
    
    public function registerEnvironments() {
        return array("document", "paragraph","code");
    }
    
    public function handleEnvironment($env, $options, $text) {
        switch($env) {
            case "document":
                return $text;
            case "paragraph":
                return "<p>".$text."</p>";
            case "code":
                return "<pre>".$text."</pre>";
        }
    }
    
    public function reset() {
        return true;
    }
}

?>

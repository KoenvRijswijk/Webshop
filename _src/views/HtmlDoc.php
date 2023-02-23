<?php
require_once SRC.'interfaces/iHtmlView.php';
abstract class HtmlDoc implements iHtmlView
{
//==============================================================================
// PUBLIC
//==============================================================================
    protected string $author;
    protected string $title;
    protected array  $cssfiles;
    protected array  $jsfiles;
    protected bool   $js_in_head;

//==============================================================================
    public function __construct(string $author, string $title)
    {
        $this->author = $author;
        $this->title = $title;
        $this->cssfiles = [];
        $this->jsfiles = [];
        $this->js_in_head = true;
    }        
//==============================================================================
// PROPERTY SETTERS    
//==============================================================================
    public function addCssFile(string $cssfile)
    {
        if (!in_array($cssfile, $this->cssfiles))
        {        
            $this->cssfiles[] = $cssfile;
        }        
    }    
//==============================================================================
    public function addJsFile(string $jsfile)
    {
        if (!in_array($jsfile, $this->jsfiles))
        {        
            $this->jsfiles[] = $jsfile;
        }        
    }    
//==============================================================================
// Bind JavaScript Files in HEAD  section or at the end of the BODY section     
//==============================================================================
    public function setJsInHead(bool $js_in_head)
    {
        $this->js_in_head = $js_in_head;
    }
//==============================================================================
// Implementation of iHtmlView interface
//==============================================================================
    final public function show()
    {
        $this->beginDoc();
        echo '  <head>'.PHP_EOL; 
        $this->headerContent();
        if ($this->js_in_head)
        {
            $this->showJsFiles(); 
        }    
        echo '  </head>'.PHP_EOL; 
        echo '  <body>'.PHP_EOL; 
        $this->bodyContent();
        if (!$this->js_in_head)
        {
            $this->showJsFiles(); 
        }    
        echo '  </body>'.PHP_EOL
            .'</html>'    ; 
    }	
//==============================================================================
// PROTECTED METHODS
//==============================================================================
    protected function beginDoc() 
    { 
        echo '<!DOCTYPE html>'
            .PHP_EOL
            .'<html>'; 
    }
//==============================================================================
    protected function headerContent() 
    { 
        echo '  <title>'.$this->title.'</title>'.PHP_EOL
            .'  <meta name="author" content="'.$this->author.'" />'.PHP_EOL; 
        $this->showCssFiles(); 
    }
//==============================================================================
//  Not clear yet what's in the BODY, so ABSTRACT  
//==============================================================================
    abstract protected function bodyContent(); 
//==============================================================================
// PRIVATE METHODS
//==============================================================================
    private function showJsFiles() 
    {
        foreach ($this->jsfiles as $js)
        {
            echo '  <script src="'.$js.'"></script>'.PHP_EOL; 

        }    
    }        
//==============================================================================
    private function showCssFiles() 
    {
        foreach ($this->cssfiles as $stylesheet)
        {
            echo '  <link rel="stylesheet" href="'.$stylesheet.'" />'.PHP_EOL;    

        }    
    }    
//==============================================================================
}

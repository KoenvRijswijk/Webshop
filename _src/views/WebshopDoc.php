<?php
require_once "HtmlDoc.php"; 
class WebshopDoc extends HtmlDoc
{
   protected array $response;
   protected const lvl1 = 1;  //00000001 cart
   protected const lvl2 = 2;  //00000010 home, about, contact
   protected const lvl3 = 4;  //00000100 login, register
   protected const lvl4 = 64; //00111110 logout, (change password)
   protected const lvl5 = 250;//11111010 home, about, contact, logout, edit/admin panel

//==============================================================================
    public function __construct(array $response)
    {
        parent::__construct(AUTHOR,TITLE);
        $this->response = $response;
            if(empty($_SESSION[USERROLE]))
            {
                $_SESSION[USERROLE] = 6;
            }
    }        
//==============================================================================
// Implement abstract method, defining steps to fill BODY section    
//==============================================================================
    final protected function bodyContent()
    {
        echo '      <header>'.PHP_EOL; 
        $this->showHeader(); 
        echo '       </header>'.PHP_EOL
            .'       <nav>'.PHP_EOL;
        $this->showMenu();
        echo '       </nav>'.PHP_EOL
            .'       <div id="message">'.PHP_EOL;
        $this->showMessage();
        echo '       </div>'.PHP_EOL
            .'       <main>'.PHP_EOL;
        $this->showMainContent();
        echo '       </main>'.PHP_EOL
            .'       <footer>'.PHP_EOL;
        $this->showFooter();
        echo '       </footer>'.PHP_EOL;
    }
//==============================================================================
    protected function showHeader()
    {
        echo 'Bio Rozen<i>-Goed voor de natuur</i>'.PHP_EOL;
    }        
//==============================================================================
    protected function showMenu() 
    {
        $items = Tools::getValueFromArray('menuitems', $this->response,'');
        $menuitems= array();
        foreach ($items as $item)
        {
            
            if($item['role'] & $_SESSION[USERROLE])
              {  
                $menuitems[$item['item']] =  $item['label'];
                                 
              }            
        }  
        echo '<ul class="menu py-2">'.PHP_EOL;
    	foreach ($menuitems as $page => $title)
    	{
            $this->showMenuItem($page,$title,$page===$this->response['page']);
    	}
	   echo '</ul>'.PHP_EOL;
    }
//==============================================================================
    protected function showMenuItem(string $page, string $title, bool $active)
    {
	echo '<li>'
            .(
                $active
                ?'<span >'.$title.'</span>'
                :'<a id="'.$page.'" href="'.LINKBASE.$page.'">'.$title.'</a>'
            )    
            . '</li>'.PHP_EOL;
    }
//==============================================================================
    protected function showMessage()
    {
        foreach ([SYSERR,SYSMSG] as $key)
        {  
            $msg = Tools::getValueFromArray($key, $this->response,'');
            //if ($msg)
            //{
                echo '<div class="'.$key.'">'.PHP_EOL
                    .$msg.PHP_EOL
                    .'</div>'.PHP_EOL;
            //}    
        }    
    }

//==============================================================================
    protected function showMainContent() 
    {
        $bodytext = Tools::getValueFromArray('bodytext', $this->response,'');
        if ($bodytext)
        {
            echo $bodytext.PHP_EOL;
        }
    }        
//==============================================================================
    protected function showFooter()
    {
        echo '<span>&copy;&nbsp;'.date("Y").'&nbsp;'.$this->author.'</span>'.PHP_EOL;
    }        
//==============================================================================
}

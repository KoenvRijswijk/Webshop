<?php
require_once SRC.'interfaces/iHtmlView.php';
class WebshopItem implements iHtmlView
{
    protected array $item;
    protected bool $detail;
    protected bool $loggeduser;
//==============================================================================
    public function __construct(array $itemdata, bool $detail, bool $loggeduser)
    {
        $this->item = $itemdata;
        $this->detail = $detail;
        $this->loggeduser = $loggeduser;
    }        
//==============================================================================
// Implementation of iHtmlView interface
//==============================================================================
    public function show()
    {
        echo '<div class="webshop-item'.($this->detail?'':' small-item').'">'.PHP_EOL
            .'  <h3>'.$this->item['productname'].'</h3>'.PHP_EOL
            //add rated stars if rated before......
            
            .'  <img src="'.SHOPIMG_FOLDER.$this->item['image'].'" alt="img" />'.PHP_EOL    
            .($this->detail?'<p>'.$this->item['description'].'</p>':'').PHP_EOL
            .'  <h4>'.Tools::nicePrice($this->item['price']).'</h4>'.PHP_EOL
            . ' <p>'.PHP_EOL;
        if (!$this->detail && $_SESSION[USERROLE] < 60)
        {    
            echo '<a class="button" href="'.LINKBASE.'detail&item='.$this->item['id'].'">Meer info</a>&nbsp;'.PHP_EOL;
        }    
        if ($this->loggeduser && $_SESSION[USERROLE] < 60)
        {
            $extra = $this->detail?'&caller=detail':'';

            echo '<input type="number" id="amount-'.$this->item['id'].'" value="1"><a id="addToCart" class="button" data-kvr-item-id='.$this->item['id'].'>Bestellen!</a>'.PHP_EOL;
        }
        if($_SESSION[USERROLE] > 60)
        {
            echo '<a class="button" href="'.LINKBASE.'edit&item=-'.$this->item['id'].'">(de)activeren</a>&nbsp;'.PHP_EOL;
        }     

        if($_SESSION[USERROLE] > 60)
        {
            $extra = $this->detail?'&caller=detail':'';
            echo '<a class="button" href="'.LINKBASE.'edit&item='.$this->item['id'].$extra.'">Aanpassen</a>'.PHP_EOL;
        }   
        
        echo '  </p>'.PHP_EOL
            .'</div>'.PHP_EOL;
        if($this->detail && !empty($_SESSION[USERNAME]))
            {
                echo '  <div id="rating"><p>Beoordeel dit product</p>
                        <div class="rate">
                        <input type="radio" id="star5" name="rate" value="5" data-kvr-item-value="';echo $this->item['id']; echo '"/>
                        <label for="star5" title="text">5 stars</label>
                        <input type="radio" id="star4" name="rate" value="4" data-kvr-item-value="';echo $this->item['id']; echo '"/>
                        <label for="star4" title="text">4 stars</label>
                        <input type="radio" id="star3" name="rate" value="3" data-kvr-item-value="';echo $this->item['id']; echo '"/>
                        <label for="star3" title="text">3 stars</label>
                        <input type="radio" id="star2" name="rate" value="2" data-kvr-item-value="';echo $this->item['id']; echo '"/>
                        <label for="star2" title="text">2 stars</label>
                        <input type="radio" id="star1" name="rate" value="1" data-kvr-item-value="';echo $this->item['id']; echo '"/>
                        <label for="star1" title="text">1 star</label>
                        <div id="NrRating">klantbeoordeling(#';echo $this->item['nr_votes']; echo'): ';echo $this->item['avg_rate']; echo '</div>
                        </div><BR><BR><BR>
                        <div id="rateMSG"></div>
                        <div id="rateNaam"></div>
                        <div id="rateItem"></div>
                        <div id="rateValue"></div>
                        
                        ';
}
    }
    
}

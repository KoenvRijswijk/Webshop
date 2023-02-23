<?php
require_once SRC.'/dal/ShopDAO.php';
require_once 'BaseModel.php';

class WebshopModel extends BaseModel
{
    protected ShopDAO $shopdao;
//==============================================================================
    public function __construct()
    {
        parent::__construct();
        $this->shopdao = new ShopDAO();
        $this->ajaxdao = new AjaxDAO();
    }
//==============================================================================
    public function handleItemDetail(&$response) : HtmlDoc
    {
        $id = Tools::getRequestVar('item',false,0,true);
        if ($id === 0)
        {
            $response[SYSERR] = 'Ongeldig item';
            $item = false;
        }
        else
        {    
            $item = $this->shopdao->getWebshopItemByID($id);
        }    
        if ($item === false)
        {
            $response[SYSERR] = 'Item niet gevonden.';
            $response['page'] = 'webshop';
            return $this->handleWebshopItems($response);
        }   
        else
        {
            $response['item'] = $item;
            return $this->createWebshopDetailDoc($response);
        }
    }        
//==============================================================================
    public function handleWebshopItems(&$response)
    {
    // TO DO paginering
         $pageno = Tools::getRequestVar('pageno',false,1,true);
        $no_of_records_per_page = 10;
        $offset = ($pageno-1) * $no_of_records_per_page; 
        
        if($_SESSION[USERROLE] > 60 ? $itemsCount = $this->shopdao->getAllWebshopItemsCount() : $itemsCount = $this->shopdao->getWebshopItemsCount());
        $itemsCount = $itemsCount[0]['COUNT(*)'];
        $total_pages = ceil($itemsCount/$no_of_records_per_page);
        
        if($_SESSION[USERROLE] > 60 ? $items = $this->shopdao->getAllWebshopItems($offset, $no_of_records_per_page) : $items = $this->shopdao->getWebshopItems($offset, $no_of_records_per_page));

        if ($items === false)
        {
            $response[SYSERR] = 'Geen items niet gevonden.';
        }   
        else
        {
            $response['items'] = $items;
        }
        $response['total_pages'] = $total_pages;
        $response['pageno'] = $pageno;
        return $this->createWebshopItemsDoc($response);
    }  
//==============================================================================
    protected function createWebshopItemsDoc(&$response) : HtmlDoc
    {
        $this->updateResponse($response);
        require_once SRC.'views/WebShopItemsDoc.php';
        return new WebShopItemsDoc($response);
    }        
//==============================================================================
    protected function createWebshopDetailDoc(&$response) : HtmlDoc
    {
        $this->updateResponse($response);
        require_once SRC.'views/WebShopDetailDoc.php';
        return new WebShopDetailDoc($response);
    }    
//==============================================================================
    public function createWebShopEditFormDoc(&$response) : HtmlDoc
    {
        require_once SRC.'views/WebshopFormDbDoc.php';
        $this->updateResponse($response);
        $response['id'] = Tools::getRequestVar('item',false,0,true);
        
        //if geldig id 
        if($response['id'] < 0)
        {
            $item =  $this->shopdao->getWebshopItemByID($response['id'] *-1);
        } 
        elseif($response['id'] > 0)
        {
            $item =  $this->shopdao->getWebshopItemByID($response['id']);
        }
        else 
        {
            $response['id'] = 0;
            $response['item'] = array();
            $response['forminfo'] = $this->sitedao->getFormInfoByPage($response['page']);
            $response['fieldinfo'] = $this->sitedao->getFieldInfoByPage($response['page']);
            return new WebshopDbFormDoc($response);
        }

        if($item !== false && $response['id'] > 0 )
        {
            $response['item'] = $this->shopdao->getWebshopItemByID($response['id']);
            $response['forminfo'] = $this->sitedao->getFormInfoByPage($response['page']);
            $response['fieldinfo'] = $this->sitedao->getFieldInfoByPage($response['page']);
            return new WebshopDbFormDoc($response);
        }
        elseif($item !== false && $response['id'] < 0)
        {
            require_once SRC.'views/WebShopItemsDoc.php';
            if($item['active'] == 1?$item['active'] = 0: $item['active'] = 1);
            if($this->shopdao->editItem($item) == false)
            {
                $response[SYSERR] = 'De insert is niet gelukt.';
            }
            else 
            {
                $response[SYSMSG] = 'Item is aangepast.';
            }
            $response['page'] = 'webshop';
            return $this->handleWebshopItems($response);
        } else
        {
            $response[SYSERR] = 'Je hebt een ongeldig item opgegeven.';
            $response['id'] = 0;
            $response['item'] = array();
            $response['forminfo'] = $this->sitedao->getFormInfoByPage($response['page']);
            $response['fieldinfo'] = $this->sitedao->getFieldInfoByPage($response['page']);
            return new WebshopDbFormDoc($response);
        }
        
    }
//===============================================================================   
    public function handleEditItem(&$response) :HtmlDoc
    {
    $response['postresult']['id'] = Tools::getRequestVar('item',false,0,true);
    if(empty($response['postresult']['id'])) $response['postresult']['id'] = $_POST['id'];

    if($response['postresult']['id'] === 0 || empty($response['postresult']['id']) || !isset($response['postresult']['id']))
    {
        echo " insert new product";
        if($this->shopdao->insertItem($response['postresult']) === false )
        {
            $response[SYSERR] = 'het nieuwe item is niet opgeslagen..';
        }
        else 
        {
            $response[SYSMSG] = 'Item is toegevoegd.';
        }
    }
    else
    {   
        if($this->shopdao->editItem($response['postresult']) === false )
        {
            $response[SYSERR] = 'De update is niet gelukt.';
        }
        else 
        {
            $response[SYSMSG] = 'Item is weer up to date.';
        }
    }
    $response['page'] = 'webshop';
    $this->updateResponse($response);
    require_once SRC.'views/WebShopItemsDoc.php';
    
    return $this->handleWebshopItems($response);
    }

//==============================================================================
 public function handleWebshopOrders(&$response)
    {
    // TO DO paginering
    // $start = getRequestVar('start',false,0,true);
        $orders = $this->shopdao->getWebshopOrders();
        $new=[];
        if ($orders === false)
        {
            $response[SYSERR] = 'Geen items niet gevonden.';
        }   
        else
        {
            //calculate for each order the total items and total price
            foreach($orders as $order)
            {
                $order['total_amount'] = $order['price'] * $order['amount'];
                $new[]= $order;
            }
            
            $temp_array = [];
            foreach ($new as $init) {
              // Initially, group them on the id key as sub-arrays
              $temp_array[$init['order_id']][] = $init;
            }
            
            $result = [];
            foreach ($temp_array as $id => $arr) {
              // Loop once for each id ($temp_array has 2 elements from your sample)
              // And add an element to $result
                //Tools::dump($temp_array);
             $result[] = [
                'order_id' => $id,
                // Sum the value subkeys
                // array_column() returns just the key 'value' as an array
                // array_sum() adds them
                'amount' => array_sum(array_column($arr, 'total_amount')),
                'quantity' => array_sum(array_column($arr, 'amount')),
                'status' => array_column($arr, 'status'),
                'date' => array_column($arr, 'order_date'),
                // implode the name subkeys into a string
                'items' => implode(',', array_column($arr, 'productname'))
              ];
            }
            //Tools::dump($result);
            $response['orders'] = $result;
            
        }
        $response['order_lines'] = $orders;
        return $this->createWebshopOrdersDoc($response);
    }  

//==============================================================================
    protected function createWebshopOrdersDoc(&$response) : HtmlDoc
    {
        $this->updateResponse($response);
        require_once SRC.'views/WebShopOrdersDoc.php';
        return new WebShopOrdersDoc($response);
    }        
//==============================================================================

    public function handleEditOrder(&$response) 
    {
        //get requested order ID -> negative, adjust status, positive show details. 
       
        $response['id'] = Tools::getRequestVar('order',false,0,true);
        if($response['id'] < 0) 
        {
           $id = $response['id'] *-1 ;
        }
        else
        {
           $id = $response['id'];  
        }  
        if($this->shopdao->getWebshopOrderByID($id) !== false )
        {
            $response['order'] = $this->shopdao->getWebshopOrderByID($id);
        } 
        else 
        {
            $response[SYSERR] = 'ongeldig ordernummer';
            $response['page'] = 'order';
            $this->updateResponse($response);
            return $this->handleWebshopOrders($response);
        }
        if($response['id'] < 0)
        {
            $response['order'] = $this->shopdao->getWebshopOrderByID(-$response['id']);
            if($response['order']['status'] == 'in process')
            {
                $response['order']['status'] = 'completed';
            }
            else
            {
                $response['order']['status'] = 'in process';
            }
            
            if($this->shopdao->editOrderStatus($response['order']) === false)
            {
                $response[SYSERR] = 'De status update is niet gelukt.';
            }
            else
            {
                $response[SYSMSG] = "De status is aangepast";
                $response['page'] = 'order';
                $this->updateResponse($response);
                return $this->handleWebshopOrders($response);
            }
        }
        else 
        {
        $response['order'] = $this->shopdao->getWebshopOrderLinesByID($id);
        $this->updateResponse($response);
        require_once SRC.'views/WebShopOrderDetailDoc.php';
        return new WebShopOrderDetailDoc($response);
        }         
    }
}
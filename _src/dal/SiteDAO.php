<?php
require_once "BaseDAO.php";
class SiteDAO extends BaseDAO
{

//==============================================================================
// TO DO SELECT ITEMS FROM DATABASE
//==============================================================================
    public function getMenuItems(bool $loggeduser, bool $hascart) : array
    {
        //start function to get menu items from database
        $sql = "SELECT * FROM MENU ORDER BY orderby";
        return $this->_crud->selectMore("SELECT * FROM MENU ORDER BY orderby");
    }    
//==============================================================================
// TO DO SELECT TEXT FROM DATABASE
//==============================================================================
    public function getTextByPage(string $page) : string
    {
        switch ($page)
        {
            case 'about':
                return '<h1>Over mij</h1><p>Ineens weet je het, je wordt Web Developer!</p>'
                . '<img class="img-small" src="'.WEBIMG_FOLDER.'me.jpg" />'    
                . '<p>'
                . ' '
                . ' '
                . ' '
                . ' '
                . '</p>';
            case 'cart': 
                return '<h1>Bijna klaar!</h1><p>Druk op [Plaats bestelling] om de rozen definitief te bestellen.</p>';
            case 'contact': 
                return '<h1>Contact</h1><p>Heb je vragen of opmerkingen over een roos, vul dan onderstaand formulier in en we nemen '
                . 'zsm contact met je op.</p>';
            case 'detail': 
                return '<h1>Meer informatie</h1><p>Druk op [Bestellen!] om de roos te bestellen.</p>';
            case 'home':
                return '<h1>Welkom!</h1><p>Welkom op de website van de enige biologische rozenkwekerij in Nederland, '
                . 'met een uniek en zeer gezond aanbod.</p>'
                . '<p>Wilt u uw tuin opvrolijken? dan is deze site wellicht iets voor jou!</p>'
                . 'In de webshop vind je ons unieke assortiment, neem een kijkje.</p>'
                . '<img src="'.WEBIMG_FOLDER.'home.jpg" />';    
            case 'login': 
                return '<h1>Inloggen</h1><p>Heb je al een account en wil je een roos kopen? '
                . ' Vul dan je email-adres en wachtwoord in en druk op [Inloggen]</p>';
            case 'thanks': 
                return '<h1>Dank!</h1><p>We nemen zsm contact met je op.</p>';
            default:
                return '';
        }
    }    
//==============================================================================
// TO DO SELECT FIELDINFO FROM DATABASE
//==============================================================================
    public function getFormInfoByPage(string $page) : array
    {
        $submittxt = [
            'contact' => 'Versturen',  
            'login' => 'Inloggen',  
            'register' => 'Registreren',
            'edit'  => 'Aanpassen of toevoegen'
        ];
        return [
            'page' => $page,
            'action' => '',
            'method' => 'POST',
            'submit' => Tools::getValueFromArray($page,$submittxt,'Bewaar')
        ];
    }    
//==============================================================================
// TO DO SELECT FIELDINFO FROM DATABASE
//==============================================================================
    public function getFieldInfoByPage(string $page) : array
    {
        switch ($page)
        {
            case 'contact' :
                return [
                    'name' => [
                        'type' => 'text', 		
                        'label'=> 'Je naam',
                        'placeholder' => 'Vul hier je naam in',
                        'default' => Tools::getSesVar(USERNAME)
                    ],		
                    'email' => [
                        'type' => 'email',
                        'label'=> 'Je email-adres',
                        'placeholder' => 'Vul hier je email-adres in',
                        'check_func' => 'validEmail',
                        'default' => Tools::getSesVar(USEREMAIL)
                    ],	
                    'phone' =>  [
                        'type' => 'tel',
                        'label'=> 'Je telefoonnummer',
                        'placeholder' => 'Vul hier je telefoonnummer in',
                    ],	
                    'message' => [
                        'type' => 'textarea',
                        'label'=> 'Je bericht',
                        'placeholder' => 'Vul hier je bericht in'
                    ],		
                    'contact' =>  [
                        'type' => 'select',
                        'label'=> 'Neem contact met op via',
                        'options' => [
                            'Email' => 'contact_email',
                            'Telefoon' => 'contact_phone',
                            'Postduif'  => 'contact_pidgeon'
                        ]
                    ]
                ];		
            case 'login' :
                return [
                    'email' => [
                        'type' => 'email',
                        'label'=> 'Je email-adres',
                        'placeholder' => 'Vul hier je email-adres in',
                        'check_func' => 'validEmail',
                        'default' => Tools::getSesVar(USEREMAIL)
                    ],	
                    'password' => [
                        'type' => 'password', 		
                        'label'=> 'Je wachtwoord',
                        'placeholder' => 'Vul hier je wachtwoord in'
                    ]
                ];    
            case 'register' :
                return [
                    'name' => [
                        'type' => 'text', 		
                        'label'=> 'Je naam',
                        'placeholder' => 'Vul hier je naam in',
                    ],		
                    'email' => [
                        'type' => 'email',
                        'label'=> 'Je email-adres',
                        'placeholder' => 'Vul hier je email-adres in',
                        'check_func' => 'validEmail'
                    ],	
                    'password' => [
                        'type' => 'password', 		
                        'label'=> 'Je wachtwoord',
                        'placeholder' => 'Vul hier je wachtwoord in'
                    ],
                    'rep_password' => [
                        'type' => 'password', 		
                        'label'=> 'Herhaal je wachtwoord',
                        'placeholder' => 'Vul hier je wachtwoord in'
                    ]
                ];
            case 'edit':
            //case 'nieuw_item':
                return [
/*                    'id' => [
                        'type' => 'hidden',       
                        'label'=> '',
                        'placeholder' => 'het product id (niet aanpassen)',
                    ],      
*/
                    'productname' => [
                        'type' => 'text',       
                        'label'=> 'productnaam',
                        'placeholder' => 'Vul hier het product naam in',
                    ],      
                    'description' => [
                        'type' => 'textarea',
                        'label'=> 'product omschrijving',
                        'placeholder' => 'Vul hier de productomschrijving in',
                    ],  
                    'image' => [
                        'type' => 'text',       
                        'label'=> 'image',
                        'placeholder' => 'vul hier de fotonaam in',
                    ],
                    'image upload' => [
                        'type' => 'file',   
                        'label'=> 'image',
                        'placeholder' => 'upload image here',
                    ],
                    'price' => [
                        'type' => 'number',       
                        'label'=> 'prijs',
                        'placeholder' => 'vul hier de prijs in',
                    ],  
                    'stock' => [
                        'type' => 'number',       
                        'label'=> 'voorraad',
                        'placeholder' => 'vul hier de voorraad in',
                    ],
                     'active' => [
                        'type' => 'select',
                        'label'=> 'tonen in webwinkel',
                        'options' => [
                            'Yes' => '1',
                            'No' => 'false',
                            ]
                        ]                       
                ];   
            default :
                // Show Error!!!
                return [];
        }        
    }
}

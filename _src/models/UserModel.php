<?php
require_once SRC.'/dal/UserDAO.php';
require_once 'BaseModel.php';
class UserModel extends BaseModel
{
    protected UserDAO $userdao;
//==============================================================================
    public function __construct()
    {
        parent::__construct();
        $this->userdao = new UserDAO();
    }
//==============================================================================
    public function handleContact(array &$response)  : HtmlDoc
    {
        $contactvorm = [
            'contact_email' => 'via mail naar <b>'.$response['postresult']['email'].'.</b>',
            'contact_phone' => 'per telefoon op nr <b>'.$response['postresult']['phone'].'.</b>',
            'contact_pidgeon'=>'middels een postduif die u wel weet te vinden.'
        ];
        $response[SYSMSG] = 'Beste '.$response['postresult']['name'].',<br/><br/>'
                          . 'dank voor uw contact-aanvraag.<br/><br/>'
                          . 'We nemen zsm contact met u op <br/> '  
                          . $contactvorm[$response['postresult']['contact']];
        return $this->createWebShopFormDoc($response);
    }
//==============================================================================
    public function handleLogin(array &$response)  : HtmlDoc
    {
        $user = $this->userdao->getUserByEmail($response['postresult']['email']);
        if ($user)
        {
            if (Tools::garble(Tools::hex2str($user['password']), MYKEY)===$response['postresult']['password'])
            {
                $_SESSION[USERID] = $user['id'];
                $_SESSION[USERNAME] = $user['name'];
                $_SESSION[USEREMAIL]= $user['email'];
                $_SESSION[USERROLE] = $user['role']; // 1 is admin, 0 is customer
                $response['page']= 'home';
                return $this->createWebShopDoc($response);
            }    
            else
            {
                $response[SYSERR] = 'Ongeldig wachtwoord';
            }
        }   
        else
        {
            $response[SYSERR] = 'Onbekenden gebruiker';
        }
        if ($this->maxAttempt())
        {    
            header("Location: https://www.gratislerentypen.nl/");
            die();
        }    
        else
        {
            return $this->createWebShopFormDoc($response);
        }
    }                    
//==============================================================================
    public function handleRegistration(array &$response) : HtmlDoc
    {
        $ok = true;
        if ($response['postresult']['password'] !==$response['postresult']['rep_password'])
        {
            $response[SYSERR] = 'Wachtwoord en herhaling niet gelijk.';
            $ok = false;
        }  
        if ($ok && $this->userdao->getUserByEmail($response['postresult']['email'])!==false)
        {
            $response[SYSERR] = 'Gebruiker bestaat al.';
            $ok = false;
        }    
        if ($ok)
        {    
            $id = $this->userdao->insertUser(
                $response['postresult']['name'],    
                $response['postresult']['email'],
                Tools::str2hex(Tools::garble($response['postresult']['password'], MYKEY))
            );
            if ($id === false)
            {
                $ok = false;
                $this->addErrorMsg($response, $this->userdao->getLastError());
            }    
        }    
        if ($ok)
        {
            $response[SYSMSG] = 'Registratie gelukt.';
            $response['page'] = 'home';
// Aanroeper zou er nu voor kunnen kiezen deze user meteen als ingelogd te zetten           
            $response['user_id'] = $id;
            return $this->createWebShopDoc($response);
        }
        else
        {
            $this->addErrorMsg($response,'Registratie mislukt.');
            return $this->createWebShopFormDoc($response);
        }
    }   
//==============================================================================
    public function handleLogout(array &$response) : HtmlDoc
    {
        unset($_SESSION[USERID]);
        unset($_SESSION[USERNAME]);
        unset($_SESSION[USEREMAIL]);
        unset($_SESSION[CART]);
        unset($_SESSION[USERROLE]);
        $response['page']= 'home';
        return $this->createWebShopDoc($response);
    }        
//==============================================================================
    private function maxAttempt() : bool
    {
        $attempt = Tools::getSesVar('attempt',0)+1;
        if ($attempt < 3)
        {
            Tools::getSesVar('attempt', $attempt);
        }    
        return ($attempt >= 3);
    }    

}

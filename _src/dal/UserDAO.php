<?php
require_once "BaseDAO.php";
class UserDAO extends BaseDAO
{
//==============================================================================
    public function getUserByEmail(string $email) : array|false
    {
        return $this->_crud->selectOne(
            "SELECT * FROM users WHERE email=:email",
            [
                'email' => [$email, false]
            ]    
        );
    }
//==============================================================================
    public function insertUser(string $name, string $email, string $password) : int|false
    {
        return $this->_crud->doInsert(
            "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)",
            [
                'name'      => [$name, false], 
                'email'     => [$email, false], 
                'password'  => [$password, false]
            ]    
        );
    }
//==============================================================================
}    

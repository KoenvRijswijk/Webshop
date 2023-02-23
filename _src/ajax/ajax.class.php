<?php
################################################################################
# Author    : Kvr.Developments 
# Date      : 13/12/2022
# Project   : Educom Learnings
# Class     : AJAX/API Handler Base Class
################################################################################
abstract class AjaxHandler
{
    protected $_crud;
    protected $langcode;
    protected $logmodel;
//==============================================================================
    public function __construct()
    {
        $this->_crud = Crud::getInstance();
        //$this->logmodel = CRACOMODELMANAGER::getLogModel();
        //$this->langcode = $langcode;
    }
//==============================================================================
    abstract public function execute();
//==============================================================================
    protected function _getHexPostVar($name, $default="")
    {
	$r = Tools::getPostVar($name, false, $default);
        return Tools::hexstr2str($r);
    }
//==============================================================================
    protected function _getPostVar($name, $default="")
    {
        return Tools::getPostVar($name, false, $default);
    }
//==============================================================================
    protected function _getPostVarAsInt($name)
    {
        return Tools::getPostVar($name, true, 0);
    }
//==============================================================================
    protected function _getTxt($id)
    {
        return $this->_crud->getWBTxt($id, $this->langcode,$id); //,true);
    }        
//==============================================================================
    protected function _getMemoTxt($id)
    {
        return $this->_crud->getMemoTxt($id, $this->langcode,$id); //,true);
    }        
//==============================================================================
}
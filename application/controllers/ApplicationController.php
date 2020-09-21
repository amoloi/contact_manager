<?php

class ApplicationController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
	    $ajaxContext->addActionContext('testajax', 'html')
	                ->addActionContext('modify', 'html')
	                ->initContext();
    }

    public function indexAction()
    {
        // action body
    }

    public function testajaxAction()
    {
        // action body
        $number = $this->getRequest()->getPost('name_number');
        $this->view->number = $number;
    }


}




<?php

class ClientsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */

    }

    public function indexAction()
    {
        // action body
        $this->view->title = 'Client`s List';
        $sqlFilter = '';
        //Get the connection from Zend registry
        $connection = Zend_Registry::get('connection');
        
        if($this->getRequest()->isPost()){
            $CLIENT_NAME = filter_input(INPUT_POST , 'CLIENT_NAME' , FILTER_SANITIZE_STRING);
			if ($CLIENT_NAME != '' && $CLIENT_NAME != 'Client Name') {
			     $sqlFilter .= ' AND CLIENT_NAME LIKE \'%'.$CLIENT_NAME.'%\' ';
			}
            
            $CLIENT_CD = filter_input(INPUT_POST , 'CLIENT_CD' , FILTER_SANITIZE_STRING);
			if ($CLIENT_CD != '' && $CLIENT_CD != 'Client Code') {
			     $sqlFilter .= " AND CLIENT_CD  = '{$CLIENT_CD}' ";
			}
        }

        $sql = 'SELECT a.CLIENT_CD , 
                         CLIENT_NAME ,
                         CLIENT_ACTIVE
                FROM clients a
                
                WHERE CLIENT_ACTIVE = 1 ' . $sqlFilter;
                
        try{
            //$psql = $connection->prepare($sql);
            //$psql->execute();
            //$clients = $psql->fetchAll();
        
                //pagination
                /* Get the page number , default 1
                */
                $page = $this->_getParam('page',1);
                /*
                * Object of Zend_Paginator
                */
                $paginator = Zend_Paginator::factory($clients);
                /*
                * Set the number of counts in a page
                */
                $paginator->setItemCountPerPage(10);
                /*
                * Set the current page number
                */
                $paginator->setCurrentPageNumber($page);
                /*
                * Assign to view
                */
                $this->view->paginator = $paginator;

        }catch(PDOExceprion $pe){
            echo $pe->getMessage();
        
    }
    
}
    public function newAction()
    {
        // action body
        $this->view->title = 'Adding New Client';
    }

    public function editAction()
    {
        // action body
//Get the database connection here
        try{
		    $connection = new PDO(DB_DNS , DB_USERNAME , DB_PASSWORD);
		}catch(PDOException $pde){
		    echo $pde->getMessage();
		}
        // action body
        $CLIENT_CD = $this->getRequest()->getParam('ccd');
        $this->view->title = "Editing Client ({$CLIENT_CD})";
        
        if($this->getRequest()->isPost()){
            
        }else{
            $CLIENT_CD = $this->getRequest()->getParam('ccd');
            try{
                $sql = 'SELECT
                              CLIENT_NAME ,
                              CLIENT_ACTIVE
                        FROM clients
                        WHERE CLIENT_CD = :CLIENT_CD';
                        
                $psql = $connection->prepare($sql);
                $psql->bindValue(':CLIENT_CD',$CLIENT_CD);
                $psql->execute();
                $this->view->clients = $psql->fetch();
                $psql->closeCursor();
                
                
            }catch(PDOException $pe){
                $error_message = $pe->getMessage();
            }
        
    }
}
    public function activeAction()
    {
        // action body
    }

    public function deleteAction()
    {
        // action body
    }

    public function saveAction()
    {
        // action body
            $connection = Zend_Registry::get('connection');
        //$this->view->title = 'Customer`s Edit';
        if(in_array('CONTACT_FIRSTNAME' , $_POST)){
            $INPUT_COUNT = count($_POST['CONTACT_FIRSTNAME']);
        }
   
        if($this->getRequest()->isPost()){
            $CLIENT_NAME = filter_input(INPUT_POST , 'CLIENT_NAME' , FILTER_SANITIZE_STRING);
            require_once('../library/CMFunctions/func.client_code_generator.php');
            try{
                $sql = 'INSERT INTO clients (CLIENT_NAME  , CLIENT_CDATE )
                                      VALUES (:CLIENT_NAME , NOW())'; 
                $psql = $connection->prepare($sql);
                $psql->bindValue(':CLIENT_NAME' , $CLIENT_NAME);
                $psql->execute();
                $psql->closeCursor();
                $CLIENT_ID = $connection->lastInsertId();
                echo $CLIENT_ID;
               echo $CLIENT_CD =  client_code_genarator($CLIENT_NAME , $CLIENT_ID);
               //UPDATING CLIENT CODE
               $sql = 'UPDATE clients SET CLIENT_CD = :CLIENT_CD WHERE CLIENT_ID  = :CLIENT_ID';
               $psql = $connection->prepare($sql);
               $psql->bindValue(':CLIENT_CD' , $CLIENT_CD);
               $psql->bindValue(':CLIENT_ID' , $CLIENT_ID);
               $psql->execute();
               $psql->closeCursor(); 
  
            }catch(PDOException $pe){
               echo  $msg = $pe->getMessage();
            }
            
            
        }
                //return $this->_helper->redirector('index','clients');
        
    }


}












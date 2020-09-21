<?php

class ContactsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        //Initializing ajax contents
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
	    $ajaxContext->addActionContext('ajaxcontact', 'html')
                    ->addActionContext('ajaxclientcontact', 'html')
	                ->addActionContext('contacts', 'json')
                    ->addActionContext('clients','html')
	                ->initContext();
        
    }

    public function indexAction()
    {
        // action body
        $this->view->title = 'Clients`s Contact List';
        $sqlFilter = '';
                //Get the database connection here
                $OPTIONS = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
        try{
		    $connection = new PDO(DB_DNS , DB_USERNAME , DB_PASSWORD , $OPTIONS);
		}catch(PDOException $pde){
		    echo $pde->getMessage();
		}
        
        //Get data for search functionalities
        if($this->getRequest()->isPost()){
            $CONTACT_FIRSTNAME = filter_input(INPUT_POST , 'CONTACT_FIRSTNAME' , FILTER_SANITIZE_STRING);
			if ($CONTACT_FIRSTNAME != '' && $CONTACT_FIRSTNAME != 'Firstname') {
			     $sqlFilter .= ' AND CONTACT_FIRSTNAME LIKE \'%' . $CONTACT_FIRSTNAME . '%\' ';
			}
            
            $CONTACT_LASTNAME = filter_input(INPUT_POST , 'CONTACT_LASTNAME' , FILTER_SANITIZE_STRING);
			if ($CONTACT_LASTNAME != '' && $CONTACT_LASTNAME != 'Lastname') {
			     $sqlFilter .= ' AND CONTACT_LASTNAME LIKE \'%' . $CONTACT_LASTNAME . '%\' ';
			}
            
            $CONTACT_EMAIL = filter_input(INPUT_POST , 'CONTACT_EMAIL' , FILTER_VALIDATE_EMAIL);
			if ($CONTACT_EMAIL != '' && $CONTACT_EMAIL != 'E-mail') {
			     $sqlFilter .= ' AND CONTACT_EMAIL LIKE \'%' . $CONTACT_EMAIL . '%\' ';
			}
        }
        
            $sql = 'SELECT
                          CONTACT_FIRSTNAME ,
                          CONTACT_LASTNAME  ,
                          CONTACT_EMAIL     ,
                          CONTACT_CD        ,
                          CONTACT_ACTIVE    ,
                          cl.CLIENT_CD,
                          cl.CLIENT_NAME
                          
                    FROM contacts co
                    
                    JOIN clients cl
                    
                    ON co.CLIENT_CD = cl.CLIENT_CD
                    
                    WHERE CLIENT_ACTIVE = 1' . $sqlFilter; 
        try{
            $psql = $connection->prepare($sql);
            $psql->execute();
            //$contacts = 
            $contacts = $psql->fetchAll();
            //print_r($this->view->contacts);
                            //pagination
                /* Get the page number , default 1
                */
                $page = $this->_getParam('page',1);
                /*
                * Object of Zend_Paginator
                */
                $paginator = Zend_Paginator::factory($contacts);
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
            
        }
        
    }

    public function newAction()
    {
        // action body
        $this->view->title = 'New Customer';
    }

    public function editAction()
    {
        //Get the database connection here
        try{
            $OPTIONS = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
		    $connection = new PDO(DB_DNS , DB_USERNAME , DB_PASSWORD,$OPTIONS);
		}catch(PDOException $pde){
		    echo $pde->getMessage();
		}

        // action body
        $this->view->title = 'Contacts Edit';
        
        if($this->getRequest()->isPost()){
            $CONTACT_CD = $this->getRequest()->getPost('CONTACT_CD');
            $CONTACT_FIRSTNAME = filter_input(INPUT_POST, 'CONTACT_FIRSTNAME', FILTER_SANITIZE_STRING);
			$CONTACT_LASTNAME = filter_input(INPUT_POST, 'CONTACT_LASTNAME', FILTER_SANITIZE_STRING);
			$CONTACT_EMAIL = filter_input(INPUT_POST, 'CONTACT_EMAIL', FILTER_VALIDATE_EMAIL);
			$CLIENT_CD = filter_input(INPUT_POST, 'CLIENT_CD', FILTER_SANITIZE_STRING);
            try{
                $sql = 'UPDATE contacts SET CONTACT_FIRSTNAME = :CONTACT_FIRSTNAME  ,
                                                 CONTACT_LASTNAME  = :CONTACT_LASTNAME   , 
                                                 CONTACT_EMAIL     = :CONTACT_EMAIL      , 
                                                 CLIENT_CD         = :CLIENT_CD          , 
                                                 CONTACT_MDATE     = NOW()
                        WHERE CONTACT_CD = :CONTACT_CD';         
			$psql = $connection->prepare($sql);
            
		    $psql->bindValue(':CONTACT_FIRSTNAME',$CONTACT_FIRSTNAME);
			$psql->bindValue(':CONTACT_LASTNAME',$CONTACT_LASTNAME);
			$psql->bindValue(':CONTACT_EMAIL',$CONTACT_EMAIL);
			$psql->bindValue(':CLIENT_CD',$CLIENT_CD);
            $psql->bindValue(':CONTACT_CD',$CONTACT_CD);
            
		    $success = $psql->execute();
            
            if($success){
                $psql->closeCursor();
                return $this->_helper->redirector('index');
            }
            
            }catch(PDOException $pe){
                echo $pe->getMessage();
            }
        }else{
            $CONTACT_CD = filter_var($this->getRequest()->getParam('ccd'),
                                     FILTER_VALIDATE_INT,array(
                                                                'options' => array(
                                                                                   'min_range' => 1)));
            try{
                $sql = 'SELECT
                              CONTACT_FIRSTNAME ,
                              CONTACT_LASTNAME  ,
                              CONTACT_EMAIL     ,
                              CONTACT_CD        ,
                              CONTACT_ACTIVE    ,
                              cl.CLIENT_CD      ,
                              cl.CLIENT_NAME
                              
                        FROM contacts co
                        
                        JOIN clients cl
                        
                        ON co.CLIENT_CD = cl.CLIENT_CD
                        
                        WHERE CONTACT_CD = :CONTACT_CD';
                        
                $psql = $connection->prepare($sql);
                $psql->bindValue(':CONTACT_CD',$CONTACT_CD);
                $psql->execute();
                $this->view->contact = $psql->fetch();
                $psql->closeCursor();
                  
            }catch(PDOException $pe){
                $error_message = $pe->getMessage();
            }
        }
        //echo $TEACHER_CD;
    }

    public function addAction()
    {
		//instantiate the connection
		try{
		    $connection = new PDO(DB_DNS , DB_USERNAME , DB_PASSWORD);
		}catch(PDOException $pde){
		    echo $pde->getMessage();
		}
        // action body
            $this->view->title = 'Client`s Contact Details';
            
			$EMPLYEENUMBER = filter_input(INPUT_POST, 'EMPLYEENUMBER', FILTER_SANITIZE_NUMBER_INT);
			$SURNAME = filter_input(INPUT_POST, 'SURNAME', FILTER_SANITIZE_STRING);
			$INITIALS = filter_input(INPUT_POST, 'INITIALS', FILTER_SANITIZE_STRING);
			$TITLE = filter_input(INPUT_POST, 'TITLE', FILTER_SANITIZE_STRING);
			$SCHOOLNAME = filter_input(INPUT_POST, 'SCHOOLNAME', FILTER_SANITIZE_STRING);
			$ROLE = filter_input(INPUT_POST, 'ROLE', FILTER_SANITIZE_STRING);
        
        
        
            $CLIENT_CD = $this->getRequest()->getParam('ccd');
            try{
                
                $sql = 'SELECT
                              CLIENT_CD,
                              CLIENT_NAME ,
                              CLIENT_ACTIVE
                        FROM clients
                        WHERE CLIENT_CD = :CLIENT_CD
                        
                        AND CLIENT_ACTIVE = 1';
                        
                $psql = $connection->prepare($sql);
                $psql->bindValue(':CLIENT_CD',$CLIENT_CD);
                $psql->execute();
                $this->view->client = $psql->fetch();
                $psql->closeCursor();
                   
            }catch(PDOException $pe){
                
                $error_message = $pe->getMessage();
            }

    }

    public function saveAction()
    {
        // action body
		//instantiate the connection
		try{
            $OPTIONS = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
		    $connection = new PDO(DB_DNS , DB_USERNAME , DB_PASSWORD,$OPTIONS);
		}catch(PDOException $pde){
		    echo $pde->getMessage();
		}
        // action body
        //$this->view->title = 'Adding New Customer';
        if($this->getRequest()->isPost()){
            $CLIENT_CD = filter_input(INPUT_POST, 'CLIENT_CD', FILTER_SANITIZE_STRING);
            $CONTACT_LASTNAME = $this->getRequest()->getPost('CONTACT_LASTNAME');
            
            $INPUT_COUNT = count($_POST['CONTACT_FIRSTNAME']);
            
                for($x = 0 ; $x < $INPUT_COUNT ; $x++ ){
                    $sql = 'INSERT INTO contacts (CONTACT_FIRSTNAME  , CONTACT_LASTNAME  , CONTACT_EMAIL  , CLIENT_CD  , CONTACT_CDATE)
                                          VALUES (:CONTACT_FIRSTNAME , :CONTACT_LASTNAME , :CONTACT_EMAIL , :CLIENT_CD , NOW())'; 
                    
                    //echo filter_var($_POST['CONTACT_LASTNAME'][$x] , FILTER_SANITIZE_STRING) . '<br />';
                    
                    		try{
                    			$psql = $connection->prepare($sql);
                    		    $psql->bindValue(':CONTACT_FIRSTNAME',filter_var($_POST['CONTACT_FIRSTNAME'][$x] , FILTER_SANITIZE_STRING));
                    			$psql->bindValue(':CONTACT_LASTNAME',filter_var($_POST['CONTACT_LASTNAME'][$x] , FILTER_SANITIZE_STRING));
                    			$psql->bindValue(':CONTACT_EMAIL',filter_var($_POST['CONTACT_EMAIL'][$x] , FILTER_SANITIZE_EMAIL));
                    			$psql->bindValue(':CLIENT_CD',filter_var($_POST['CLIENT_CD'] , FILTER_SANITIZE_STRING));
                    		    $success = $psql->execute();
                                if($success){
                                    $psql->closeCursor();   
                                }
                                
                    		}catch(PDOException $pe){
                    			$error_message = $pe->getMessage();
                    			echo $error_message;
                    		} 
                }
                return $this->_helper->redirector('index','clients');
        
    
    }else{
    
    }
}
    public function detailsAction()
    {
        // action body
                // action body
        $OPTIONS = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
                //Get the database connection here
        if($this->getRequest()->isPost()){
            $CLIENT_CD = $this->getRequest()->getPost('CLIENT_CD');
        }else{
            $CLIENT_CD = $this->getRequest()->getParam('ccd');
        }
        try{
		    $connection = new PDO(DB_DNS , DB_USERNAME , DB_PASSWORD , $OPTIONS);
		}catch(PDOException $pde){
		    echo $pde->getMessage();
		}
        
        $sql = "SELECT
                      CONTACT_FIRSTNAME,
                      CONTACT_LASTNAME,
                      CONCAT_WS(' ',CONTACT_FIRSTNAME , CONTACT_LASTNAME) FULLNAME  , 
                      CONTACT_EMAIL     , 
                      CONTACT_CD        ,
                      CLIENT_NAME       ,
                      CONTACT_ACTIVE    ,
                      cl.CLIENT_CD
                      
                FROM contacts co
                
                INNER JOIN clients cl
                
                ON co.CLIENT_CD = cl.CLIENT_CD
                
                WHERE co.CLIENT_CD = :CLIENT_CD
                
                AND CONTACT_ACTIVE = 1
                
                ORDER BY CONTACT_CDATE DESC";  
        try{
            $psql = $connection->prepare($sql);
            $psql->bindValue(':CLIENT_CD', filter_var($CLIENT_CD , FILTER_SANITIZE_STRING));
            $psql->execute();
            //$contacts = 
            $this->view->contacts = $psql->fetchAll();
            $this->view->code = filter_var($CLIENT_CD , FILTER_SANITIZE_STRING);
            $DATA_COUNT = (int)count($this->view->contacts);
            if(1 > $DATA_COUNT){
                    $sql = 'SELECT a.CLIENT_CD   , 
                                   a.CLIENT_NAME ,
                                   a.CLIENT_ACTIVE
                        
                        FROM clients a
                        
                        WHERE CLIENT_CD = :CLIENT_CD';
                
                    try{
                        $psql = $connection->prepare($sql);
                        $psql->bindValue(':CLIENT_CD', filter_var($CLIENT_CD , FILTER_SANITIZE_STRING));
                        $psql->execute();
                        $this->view->client = $psql->fetch();
                        $this->view->title = $this->view->client['CLIENT_NAME'] . ' Contact Details';
                        $this->view->code = $this->view->client['CLIENT_CD'];
                    }catch(PDOExceprion $pe){
                        
                    }
            }else{
                //echo 'The count is = ' . $DATA_COUNT;
                $this->view->title = $this->view->contacts[2]['CLIENT_NAME'] . ' Contact Details';
            }

        }catch(PDOExceprion $pe){
            $msg = $pe->getMessage();
    
    
    }
}
    public function statusAction()
    {
               //Get the database connection here
        try{
            $OPTIONS = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
		    $connection = new PDO(DB_DNS , DB_USERNAME , DB_PASSWORD , $OPTIONS);
		}catch(PDOException $pde){
		    echo $pde->getMessage();
		}

        // action body
        //$this->view->title = 'Customer`s Edit';
        
        if($this->getRequest()->isPost()){
            $CONTACT_CD = $this->getRequest()->getPost('CONTACT_CD');
            }else{
            $CONTACT_CD = filter_var($this->getRequest()->getParam('ccd'),
                                     FILTER_VALIDATE_INT,array(
                                                                'options' => array(
                                                                                   'min_range' => 1)));
        }
        echo $CONTACT_CD;
        
            try{
                $sql = 'UPDATE contacts SET CONTACT_ACTIVE = 1 - CONTACT_ACTIVE
                        WHERE CONTACT_CD = :CONTACT_CD';
                        
			$psql = $connection->prepare($sql);
            $psql->bindValue(':CONTACT_CD',$CONTACT_CD);
		    $success = $psql->execute();
            
            if($success){
                $psql->closeCursor();
                return $this->_helper->redirector('index');
            }
            
            }catch(PDOException $pe){
                echo $pe->getMessage();
            }
        
    }

    public function updateAction()
    {
        // action body
    }

    public function deleteAction()
    {
        // action body
        try{
            $OPTIONS = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
		    $connection = new PDO(DB_DNS , DB_USERNAME , DB_PASSWORD , $OPTIONS);
		}catch(PDOException $pde){
		    echo $pde->getMessage();
		}

        // action body
        //$this->view->title = 'Customer`s Edit';
        
        if($this->getRequest()->isPost()){
            $CONTACT_CD = $this->getRequest()->getPost('CONTACT_CD');
            }else{
            $CONTACT_CD = filter_var($this->getRequest()->getParam('ccd'),
                                     FILTER_VALIDATE_INT,array(
                                                                'options' => array(
                                                                                   'min_range' => 1)));
        }
        
            try{
                $sql = 'DELETE FROM contacts WHERE CONTACT_CD = :CONTACT_CD';
                       
			$psql = $connection->prepare($sql);
            $psql->bindValue(':CONTACT_CD',$CONTACT_CD);
            
		    $success = $psql->execute();
            $this->view->success = $success;
            if($success){
                
                $psql->closeCursor();
                return $this->_helper->redirector('index');
            }
            
            }catch(PDOException $pe){
                echo $pe->getMessage();
            
    
    
    }
}
    public function ajaxcontactAction()
    {
        // action body
        $number = filter_var($this->getRequest()->getPost('name_number'),FILTER_VALIDATE_INT);
        
        $this->view->number = $number + 1;
    }

    public function ajaxclientcontactAction()
    {
        // action body
        $number = $this->getRequest()->getPost('name_number');
        $this->view->number = $number + 1;
    }

    public function clientsAction()
    {
        //Block views for this action
        $this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);
        // action body
         try{
		    $connection = new PDO(DB_DNS , DB_USERNAME , DB_PASSWORD);
		}catch(PDOException $pde){
		    echo $pde->getMessage();
		}
        
        $sqlFilter = '';
        $dataList = array();
        
        $id = @filter_input(INPUT_POST , 'id' , FILTER_SANITIZE_STRING);
        $data = @filter_input(INPUT_POST , 'data' , FILTER_SANITIZE_STRING);
        //$id = 'names';
        //$data = 'innocent';
                if ($id && $data)
                {
                                  
                            $sqlFilter .= ' AND CLIENT_NAME LIKE \'%'.$data.'%\' ';
                            $sql = 'SELECT a.CLIENT_CD , 
                                           a.CLIENT_NAME
                                       
                                    FROM clients a
                                    
                                    WHERE CLIENT_ACTIVE = 1'.$sqlFilter.'
                                            
                                    ORDER BY CLIENT_NAME';
                                    
                            try{
                                $psql = $connection->prepare($sql);
                                $psql->execute();
                                $clients = $psql->fetchAll();
                                
                                //$clientsJson = Zend_Json::encode($clients);
                                //$this->view->clients = $clientsJson;
                                foreach($clients as $client){
                                    $toReturn   = $client['CLIENT_NAME'];
                                    $dataList[] = '<li id="' .$client['CLIENT_CD'] . '"><a href="#">' . $toReturn . '</a></li>';
                                }
                                
                            }catch(PDOExceprion $pe){
                                
                            
                        }
                 
                        if (count($dataList)>=1)
                        {
                            $dataOutput = join("\r\n", $dataList);
                            echo $dataOutput;
                        }
                        else
                        {
                            echo '<li><a href="#">No Results</a></li>';
                        }
                    
                }    

        
    }


}
























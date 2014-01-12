<?php

class IndexController extends Zend_Controller_Action{

    /**
     * Holds the instance of redirector helper to send redirects.
     *
     * @var Zend_Controller_Action_Helper_Redirector
     */
    protected $_redirector = null;
    
    /**
     * Initializing actions for current controller.
     * 
     * @param void
     * @return void
     */
    public function init(){
        $this->_redirector = $this->_helper->getHelper( "Redirector" );
    }

    /**
     * Index action logic.
     * 
     * @param void
     * @return void
     */
    public function indexAction(){
        $request = $this->getRequest();
        //Checking if user data has been already successfully submitted
        if( $request->getCookie( "user_email", "" ) !== "" && $request->getCookie( "user_data", "" ) !== "" ){
            $this->_redirector->setGotoSimple( "result", "index" );
        }
        
        $form = new Application_Form_FileUploader();
        //Validating post request and uploading file.
        if( $request->isPost() && $form->isValid( $request->getPost() ) && $form->file->receive() ){
            $file = new SplFileObject( $form->file->getFileName() );
            $file->setFlags( SplFileObject::READ_CSV );
            $header = $results = array();
            $ordering_column_number = $identifier = 0;
            //Reading uploaded file
            foreach ( $file as $row ){
                if( empty( $header ) ){
                    $header = $row;
                    //Getting the number of column by which sort should be executed
                    $ordering_column_number = array_search( "Firstname", $header );
                    continue;
                }
                
                //Creating unique key by firstname from file and the number of iteration
                //to simplify sort process
                $unique_row_key = implode( "_", array( $row[ $ordering_column_number ], $identifier ) );
                $results[ $unique_row_key ] = array_combine( $header, $row );
                $identifier++;
            }
            
            ksort( $results );
            //Setting user submitted data into cookies with expiration in an hour
            setcookie( "user_email", $request->email, time() + 3600, "/", 
                $request->getServer( "SERVER_NAME" ), false, true );
            setcookie( "user_data", serialize( $results ), time() + 3600, "/", 
                $request->getServer( "SERVER_NAME" ), false, true );
            
            $this->_redirector->setGotoSimple( "result", "index" );
        }
        
        $this->view->form = $form;
    }
    
    /**
     * Result action logic.
     * 
     * @param void
     * @return void
     */
    public function resultAction(){
        $request = $this->getRequest();
        if( $request->getCookie( "user_email", "" ) === "" || $request->getCookie( "user_data", "" ) === "" ){
            $this->_redirector->setGotoSimple( "index", "index" );
        }
        
        $this->view->email = $request->getCookie( "user_email" );
        $this->view->data = unserialize( $request->getCookie( "user_data" ) );
    }
}


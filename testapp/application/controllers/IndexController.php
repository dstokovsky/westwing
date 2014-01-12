<?php

require_once APPLICATION_PATH . "/../library/Sort/IAlgorithm.class.php";
require_once APPLICATION_PATH . "/../library/Sort/AbstractSort.class.php";
require_once APPLICATION_PATH . "/../library/Sort/QuickSort.class.php";
require_once APPLICATION_PATH . "/../library/Sort/SortFactory.class.php";

class IndexController extends Zend_Controller_Action{

    /**
     * Redirector - defined for code completion
     *
     * @var Zend_Controller_Action_Helper_Redirector
     */
    protected $_redirector = null;
    
    public function init(){
        $this->_redirector = $this->_helper->getHelper('Redirector');
    }

    public function indexAction(){
        if( isset( $_COOKIE[ "user_email" ] ) && isset( $_COOKIE[ "user_data" ] ) ){
            $this->_redirector->setGotoSimple( "result", "index" );
        }
        
        $request = $this->getRequest();
        $form = new Application_Form_FileUploader();
        
        if( $request->isPost() && $form->isValid( $request->getPost() ) && $form->file->receive() ){
            $file = new SplFileObject( $form->file->getFileName() );
            $file->setFlags( SplFileObject::READ_CSV );
            $header = $results = array();
            foreach ( $file as $row ){
                if( empty( $header ) ){
                    $header = $row;
                    continue;
                }
                
                $row_values = array();
                foreach ( $header as $order_number => $key ){
                    $row_values[ $key ] = $row[ $order_number ];
                }
                $results[] = $row_values;
            }
            
            $sort_command = SortFactory::create( "quick", $results, "Firstname" );
            $sort_command->execute();
            
            setcookie( "user_email", $request->email, time() + 3600, "/", $_SERVER[ "SERVER_NAME" ], false, true );
            setcookie( "user_data", serialize( $sort_command->getList() ), time() + 3600, "/", $_SERVER[ "SERVER_NAME" ], false, true );
            $this->_redirector->setGotoSimple( "index", "index" );
        }
        
        $this->view->form = $form;
    }
    
    public function resultAction(){
        if( !isset( $_COOKIE[ "user_email" ] ) || !isset( $_COOKIE[ "user_data" ] ) ){
            $this->_redirector->setGotoSimple( "index", "index" );
        }
        
        $this->view->email = $_COOKIE[ "user_email" ];
        $this->view->data = unserialize( $_COOKIE[ "user_data" ] );
    }
}


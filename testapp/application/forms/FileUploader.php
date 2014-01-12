<?php

class Application_Form_FileUploader extends Zend_Form
{

    public function init(){
        $this->setMethod( "post" );
        $this->setEnctype( "multipart/form-data" );
        
        $email_element = new Zend_Form_Element_Text( "email" );
        $email_element->setLabel( "Your email address:" );
        $email_element->setRequired();
        $email_element->setAllowEmpty( false );
        $email_element->setFilters( array( "StringTrim" ) );
        $email_element->addValidator( "EmailAddress" );
        
        $this->addElement( $email_element, "email" );
        
        $file_element = new Zend_Form_Element_File( "file" );
        $file_element->setLabel( "Your file:" );
        $file_element->setDestination( sys_get_temp_dir() );
        $file_element->setRequired();
        $file_element->setAllowEmpty( false );
        $file_element->addValidator( "Count", false, 1 );
        $file_element->addValidator( "Size", false, 100000 );
        $file_element->addValidator( "Extension", false, "csv,txt" );
        
        $this->addElement( $file_element, "file" );
        
        $csrf_element = new Zend_Form_Element_Hash( "csrf" );
        $csrf_element->setRequired();
        $csrf_element->setIgnore( "true" );
        
        $this->addElement( $csrf_element, "csrf" );
        
        $submit_element = new Zend_Form_Element_Submit( "submit" );
        $submit_element->setIgnore( true );
        $submit_element->setLabel( "Add" );
        
        $this->addElement( $submit_element );
    }


}


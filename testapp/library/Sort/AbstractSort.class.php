<?php

abstract class AbstractSort implements IAlgorithm {
    
    private $list = array();
    
    private $field = "";
    
    private $order = "asc";
    
    private $low = 0;
    
    private $high = 0;

    public function __construct( $list = array(), $field = "", $order = "asc" ) {
        $this->setSortingList( $list );
        $this->setSortingField( $field );
        $this->setSortingOrder( $order );
        $this->setLowRange( 0 );
        $this->setHighRange( count( $list ) - 1 );
    }
    
    public function setSortingList( $list ){
        if( !empty( $list ) && is_array( $list ) ){
            $this->list = $list;
        }
    }
    
    public function setSortingField( $field ){
        if( !empty( $field ) ){
            $this->field = $field;
        }
    }
    
    public function setSortingOrder( $order = "asc" ){
        if( in_array( $order, array( "asc", "desc" ) ) ){
            $this->order = $order;
        }
    }
    
    public function setLowRange( $low ){
        if( $low >= 0 ){
            $this->low = ( int ) $low;
        }
    }
    
    public function setHighRange( $high ){
        if( $high >= 0 ){
            $this->high = ( int ) $high;
        }
    }
    
    public function getField(){
        return $this->field;
    }
    
    public function getList(){
        return $this->list;
    }
    
    public function getLowRange(){
        return $this->low;
    }
    
    public function getHighRange(){
        return $this->high;
    }
    
    public function getOrder(){
        return $this->order;
    }
    
    abstract public function execute();
}
?>

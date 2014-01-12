<?php

class QuickSort extends AbstractSort{

    public function execute() {
        $array = $this->getList();
        $field = $this->getField();
        $order = $this->getOrder();
        $low = $this->getLowRange();
        $high = $this->getHighRange();
        
        if( count( $array ) === 0 ){
            return array();
        }
        
        $i = $low;
        $j = $high;
        $x = $array[ ( $low + ( $high - $low ) / 2 ) ][ $field ];
        
        do {
            $current_i_value = $array[ $i ][ $field ];
            $current_j_value = $array[ $j ][ $field ];
            switch ( $order ){
                case "asc":
                    while ( $current_i_value < $x ){
                        $i++;
                        $current_i_value = $array[ $i ][ $field ];
                    }

                    while ( $x < $current_j_value ){
                        $j--;
                        $current_j_value = $array[ $j ][ $field ];
                    }
                    break;
                case "desc":
                    while ( $current_i_value > $x ){
                        $i++;
                        $current_i_value = $array[ $i ][ $field ];
                    }

                    while ( $x > $current_j_value ){
                        $j--;
                        $current_j_value = $array[ $j ][ $field ];
                    }
                    break;
            }

            if ( $i <= $j ) {
              $tmp = $array[ $i ];
              $array[ $i ] = $array[ $j ];
              $array[ $j ] = $tmp;
              $i++;
              $j--;
            }
        } while ( $i <= $j );
        
        $this->setSortingList( $array );
        if ( $low < $j ){
            $this->setLowRange( $low );
            $this->setHighRange( $j );
            $this->execute();
        }
        
        if ( $i < $high ){
            $this->setLowRange( $i );
            $this->setHighRange( $high );
            $this->execute();
        }
    }
}
?>

<?php

class SortFactory {

    public static function create( $type, $list = array(), $field = "", $order = "asc" ) {
        switch ( $type ) {
            case "quick":
            default:
                return new QuickSort( $list, $field, $order );
        }
    }

}
?>

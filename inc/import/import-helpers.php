<?php

trait MG_Import_Helpers {

    public function getChunk( array $data, $chunk = 1 ) {

        $offset = 0;
        $length = 500;

        if ( $chunk > 1 ) {
            $offset = $length * ( $chunk - 1 ) ;
        }

        return array_slice( $data, $offset, $length );
    }

}
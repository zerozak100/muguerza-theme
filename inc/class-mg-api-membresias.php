<?php

class MG_API_Membresias {

    public function __construct() {
        // update_option( 'api_membresias_token', '' );
    }

    public function consultarMembresia( $email ) {
        $url        = "https://servicios-ords-dev.christus.mx/Membresias/ConsultaMembresia?p_email_m=$email";

        $token = $this->getToken();

        if ( ! $token ) {
            return false; // no se pudo obtener el token de autorización
        }

        $config = array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $token,
            ),
        );

        $response = wp_remote_get( $url, $config );

        $apiResponse = $this->handleResponse( $response );

        if ( $apiResponse->code === 401 ) {
            update_option( 'api_membresias_token', '' );
            return $this->consultarMembresia( $email ); // retry
        }

        return $apiResponse;
        // if ( is_wp_error( $response ) ) {
        // } else {
        //     $body = json_decode( wp_remote_retrieve_body( $response ), true );
        //     $code = wp_remote_retrieve_response_code( $response );
        //     $mesg = wp_remote_retrieve_response_message( $response );

        //     if ( $code === 401 ) {
        //         update_option( 'api_membresias_token', '' );
        //         $this->consultarMembresia( $email ); // retry
        //     }

        //     if ( $code >= 200 && $code < 300 ) {
        //         return $body;
        //     }
        // }
    }

    private function getToken() {
        $token = get_option( 'api_membresias_token', false );

        if ( $token ) {
            return $token;
        }

        $url        = 'https://idcs-8332050b9ca94ab48f84d174e8db9675.identity.oraclecloud.com/oauth2/v1/token';
        $username   = "ee582dd231684bb0801b2576b975e322";
        $password   = "174126f8-0d27-47eb-856a-a9fb7d903c29";
        $scope      = "christusmuguerza.com.mx/acsyt";
        $grant_type = "client_credentials";

        $config = array(
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode( "$username:$password" ),
            ),
            "body" => array(
                'scope'      => $scope,
                'grant_type' => $grant_type,
            ),
        );

        $response = wp_remote_post( $url, $config );

        $apiResponse = $this->handleResponse( $response );

        if ( $apiResponse->ok ) {
            $token = $apiResponse->data['access_token'];
            update_option( 'api_membresias_token', $token );
            error_log( "====== getToken response ok =======" );
        }

        // error_log( "getToken response false" );

        return $token;
        // if ( is_wp_error( $response ) ) {
        //     // $error_message = $response->get_error_message();
        //     // echo "Something went wrong: $error_message";
        //     // return false;
        // } else {
        //     $body = json_decode( wp_remote_retrieve_body( $response ) );
        //     $code = wp_remote_retrieve_response_code( $response );
        //     $mesg = wp_remote_retrieve_response_message( $response );
        //     if ( $code === 200 ) {
        //         update_option( 'api_membresias_token', $body->access_token );
        //         return $token;
        //     }
        // }
        // return false;
    }

    private function handleResponse( $response )
    {
        $responseWrapper = new stdClass();

        if ( is_wp_error( $response ) ) {
			throw new Exception( $response->get_error_message() );
		} else {
			$data = json_decode( wp_remote_retrieve_body( $response ), true );
            $code = wp_remote_retrieve_response_code( $response );

			// error_log("MUGUERZA API CODE: $code");
			// error_log("MUGUERZA API ENDPOINT: {$this->endpoint}");
            // error_log("MUGUERZA API RESPONSE: " . json_encode($data));

            $responseWrapper->code = $code;
            $responseWrapper->data = $data;

			if ($code >= 200 && $code < 400) {
                $responseWrapper->ok = true;
                // $responseWrapper->ok = isset( $data['successfulOperation'] ) ? $data['successfulOperation'] : true;
            } else if ($code === 404) {
                $responseWrapper->ok = false;
                $responseWrapper->message = isset( $data['message'] ) ? $data['message'] : 'No se encontró el recurso';
            } else {
                $responseWrapper->ok = false;
                $responseWrapper->message = isset( $data['message'] ) ? $data['message'] : 'Error desconocido';
            }
		}

        return $responseWrapper;
    }
}

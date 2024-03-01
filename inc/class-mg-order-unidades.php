<?php 

class MG_Order_Unidades {

    /**
     * @var MG_Unidad[] $unidades
     */
    protected $unidades;

    /**
     * @var MG_Coords $user_coords
     */
    protected $user_coords;

    /**
     * @var MG_Unidad[] $result
     */
    protected $result = array();

    public function __construct( array $unidades, MG_Coords $user_coords ) {
        $this->unidades = $unidades;
        $this->user_coords = $user_coords;
    }

    public function order() {
        /** @var MG_Unidad[] */
        $unidades_by_id = array();

        foreach ( $this->unidades as $unidad ) {
            $unidades_by_id[ $unidad->get_id() ] = $unidad;
        }

        $distances = array_map( array( $this, 'get_unidad_distance' ), $unidades_by_id );

        asort( $distances );

        foreach ( $distances as $unidad_id => $distance ) {
            $this->result[] = $unidades_by_id[ $unidad_id ];
        }
    }

    public function get_result() {
        return $this->result;
    }

    protected function get_unidad_distance( MG_Unidad $unidad ) {
        $location = $unidad->get_location();

        return mg_distance( $location->get_coords(), $this->user_coords );
    }
}
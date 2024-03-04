<?php

$especialidades = MG_Medico_Archive::get_especialidades();
$ubicaciones    = MG_Medico_Archive::get_ubicaciones();

$current_especialidad = get_query_var( 'mg_especialidad' );
$current_ubicacion    = get_query_var( 'mg_ubicacion' );

get_header();

wc_setup_loop(
    array(
        'total'        => $GLOBALS['wp_query']->found_posts,
        'total_pages'  => $GLOBALS['wp_query']->max_num_pages,
        'per_page'     => $GLOBALS['wp_query']->get( 'posts_per_page' ),
        'current_page' => max( 1, $GLOBALS['wp_query']->get( 'paged', 1 ) ),
    ),
);

?>

<div id="banner">
    <div class="container">
        <h1 class="titulo-banner">Directorio médico</h1>
        <form>
            <select name="mg_ubicacion" id="unidad-telefono">
                <option value="">Ubicacion</option>
                <?php foreach ( $ubicaciones as $ubicacion ) : ?>
                    <option <?php echo $current_ubicacion == $ubicacion->term_id ? 'selected' : '' ?> value="<?php echo $ubicacion->term_id; ?>"><?php echo $ubicacion->name; ?></option>
                <?php endforeach; ?>
            </select>
            <select name="mg_especialidad" id="unidad-telefono2">
                <option value="">Especialidad</option>
                <?php foreach ( $especialidades as $especialidad ) : ?>
                    <option <?php echo $current_especialidad == $especialidad->term_id ? 'selected' : '' ?> value="<?php echo $especialidad->term_id; ?>"><?php echo $especialidad->name; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Buscar</button>
        </form>
    </div>
</div>
<div class="content-medicos">
    <div class="container">
        <?php if ( have_posts() ) : ?>
            <table descr class="default directorio-medicos">
                <caption>Directorio médico resultados</caption>
                <tr class="tableRow">
                    <th class="tableCell">Médico</th>
                    <th class="tableCell">Especialidad</th>
                    <th class="tableCell">Ubicación</th>
                </tr>
                <?php while ( have_posts() ) : the_post(); ?>
                    <tr>
                        <td class="tableCell"><a class="tableRow" href="<?php the_permalink() ?>"><?php the_title(); ?></a></td>
                        <td class="tableCell"><?php echo MG_Medico_Archive::get_medico_especialidades_formatted( $post ); ?></td>
                        <td class="tableCell"><?php echo MG_Medico_Archive::get_medico_ubicacion_name( $post ); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
            <div class="woocommerce">
                <?php woocommerce_pagination(); ?>
            </div>
        <?php else : ?>
            <p>No se encontraron médicos</p>
        <?php endif; ?>
    </div>
</div>
<?php
get_footer();

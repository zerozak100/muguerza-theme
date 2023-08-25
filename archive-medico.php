<?php 

$especialidades = MG_Medico_Archive::get_especialidades();
$ubicaciones    = MG_Medico_Archive::get_ubicaciones();

$current_especialidad = get_query_var( 'mg_especialidad' );
$current_ubicacion    = get_query_var( 'mg_ubicacion' );

get_header();

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
        <div class="table">
            <div class="tableRow">
                <div class="tableCell">Medico</div>
                <div class="tableCell">Especialidad</div>
                <div class="tableCell">Ubicacion</div>
            </div>
            <?php if ( have_posts() ) : ?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <a class="tableRow" href="<?php the_permalink() ?>">
                        <div class="tableCell"><?php the_title(); ?></div>
                        <div class="tableCell"><?php echo MG_Medico_Archive::get_medico_especialidades_formatted( $post ); ?></div>
                        <div class="tableCell"><?php echo MG_Medico_Archive::get_medico_ubicacion_name( $post ); ?></div>
                    </a>
                <?php endwhile; the_posts_pagination(); ?>
            <?php else : ?>
                <p>No se encontraron médicos</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php
get_footer();

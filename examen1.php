<?php

/*
 * Plugin Name: Examen 1.1
 * Description: Plugin que añade enlaces a las redes sociales en el contenido y el título de las páginas
 * Version: 1.0
 * Author: Angel Castineira
 * License: GPL2
 */

// Definir variables globales
$redesNombres = array("instagram", "facebook", "twitter");
$redesEnlaces = array("https://www.instagram.com", "https://www.facebook.com", "https://www.twitter.com");

/**
 * Crea la tabla en la base de datos
 * @return void
 */
function createTable()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'redesSociales';
    //$sql = "CREATE TABLE $table_name (
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        redes varchar(255) NOT NULL,
        enlaces varchar(255) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

/**
 * Funcion gancho que en enlaza la activación del plugin con el método que crea la tabla en la base de datos
 */
add_action('plugins_loaded', 'createTable');

/**
 * Inserta los datos en la tabla de la base de datos
 * @return void
 */
/*
function insertRow()
{
    global $wpdb, $redesEnlaces, $redesNombres;
    $table_name = $wpdb->prefix . 'redesSociales';
    $flag = $wpdb->get_results("SELECT * FROM $table_name");
        if (count($flag) == 0) {
            for ($i = 0; $i < count($redesEnlaces); $i++) {
                $wpdb->insert(
                    $table_name,
                    array(
                        'redes' => $redesEnlaces[$i],
                        'enlaces' => $redesNombres[$i]
                    )
                );
            }
        }
}
*/

function insertRow()
{
    global $wpdb, $redesEnlaces, $redesNombres;
    $table_name = $wpdb->prefix . 'redesSociales';
    $flag = $wpdb->get_results("SELECT * FROM $table_name");
    if ($flag !== null && is_array($flag)) {
        if (count($flag) == 0) {
            for ($i = 0; $i < count($redesEnlaces); $i++) {
                $wpdb->insert(
                    $table_name,
                    array(
                        'redes' => $redesEnlaces[$i],
                        'enlaces' => $redesNombres[$i]
                    )
                );
            }
        }
    }
}

/**
 * Funcion gancho que en enlaza la activación del plugin con el método que inserta los datos en la tabla de la base de datos
 */
add_action('plugins_loaded', 'insertRow');

/**
 * Consulta los datos de la tabla de la base de datos
 * @return array Devuelve un array con los datos de la tabla de la base de datos
 */
function selectData()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'redesSociales';
    $results = $wpdb->get_results("SELECT * FROM $table_name");
    return $results;
}

/**
 * Concatena los enlaces de la red social con un enlace a la red social
 * @param $contenido
 * @return array modificado de los enlaces, añadiendo un enlace a la red social
 */
function concatenar_enlaces($contenido)
{
    $datos = selectData();
    foreach ($datos as $result) {
        if (strpos($contenido, $result->enlaces) !== false) {
            $enlace = "<a href='$result->redes'>$result->enlaces</a>";
            $contenido = str_replace($result->enlaces, $result->enlaces . ' - ' . $enlace, $contenido);
        }
    }
    return $contenido;

}

/**
 * Filtro que enlaza la función concatenar_enlaces con the_content de tal forma que se ejecute en el contenido de la página
 */
add_filter('the_content', 'concatenar_enlaces');

/**
 * Filtro que enlaza la función concatenar_enlaces con the_title de tal forma que se ejecute en el título de la página
 */
add_filter('the_title', 'concatenar_enlaces');

/**
 * Elimina la tabla de la base de datos cuando
 * @return void
 */
function dropTable()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'redesSociales';
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}
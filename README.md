# EXAMEN SXE - PLUGIN WORDPRESS
## Descripción
Este plugin tiene el objetivo de concatenar la url de la pagina de
la red social cuando aparezca el nombre de esa red social, tanto en
el contenido como en el titulo de la pagina.

## Primeros pasos
Me dirigo a la ubicación de los plugins de Wordpress y creo una carpeta
llamada `examen1.1`. Dentro de esta carpeta creo un archivo llamado
exactamente igual que el nombre de la carpeta, `examen1.1.php`. Este archivo es el
archivo principal del plugin y es el que contiene la información
necesaria para que Wordpress lo reconozca como un plugin.

## Explicación del código

### Primeros pasos

En primer lugar creo la cabecera del plugin donde, excepto
el "Plugin Name", los datos son opcionales aunque
recomendables.

Defino las arrays que voy a utilizar para almacenar
en la base de datos.

### Implemención base de datos

#### 1. Creo la tabla en la base de datos.

Creo la función que se encargará de crear la tabla
con las columnas que almacenarán el nombre de la red social
y 
su enlace correspondiente.

En vez de hacerlo directamente con una consulta SQL, 
usaremos la funcion `dbDelta()` la cual podemos 
encontrar en `wp-admin/includes/upgrade.php`.
Esta función ejecuta un conjunto de instrucciones SQL.

* `global $wpdb` es un objeto de WordPress para 
manejar bases de datos.
* `$table_name = $wpdb->prefix . 'redesSociales'` asigno 
el nombre de la tabla de la base de datos, que lo concateno el prefijo
que Wordpress le asigna a las demás tablas.
* `$charset_collate = $wpdb->get_charset_collate()` en esta linea
configuramos el conjunto de caracteres predeterminado y 
la recopilacion para la tabla. 
* `$sql = /*TODO*/` en esta linea creamos la consulta SQL.
* `add_action('plugins_loaded', 'createTable')` enlaza el **hook**
  (`plugins_loaded`) con la función **callback** (`createTable`).

#### 2. Inserto los datos en la tabla.

Creo la función que se encargará de insertar los datos en la tabla.

Convierto las arrays en globales para poder acceder a ellas desde
la función.

Aplicando a la linea `$flag = $wpdb->get_results("SELECT * FROM $table_name");`
la funcion `count()` compruebo si la tabla está vacía o no. Si está vacía, inserto los datos.

Por último añado la acción para que cada vez que se carge el plugin
se ejecute la función.

### Funcion principal

Este método llamado `concatenar_enlaces()` se encarga de modificar el contenido recibido como parámetro, buscando enlaces de redes sociales y agregando enlaces adicionales a cada uno de ellos.

1. **Obtención de datos**: Primero, llamo a la función `selectData()` para obtener los datos de las redes sociales desde la base de datos.

2. **Bucle de datos**: Itero a través de cada resultado obtenido. Para cada resultado:
    - Compruebo si el contenido tiene el enlace de esa red social (`$result->enlaces`) usando `strpos()`.
    - Si encuentro el enlace en el contenido:
        - Creo un nuevo enlace HTML (`<a>`) que redirige a la dirección de la red social (`$result->redes`) con el texto del enlace.
        - Reemplazo el enlace original en el contenido con el enlace original más el nuevo enlace generado.
    - Continúo buscando más coincidencias hasta recorrer todos los datos.

3. **Retorno del contenido modificado**: Finalmente, devuelvo el contenido modificado con los enlaces adicionales.

Este método busca enlaces específicos dentro del contenido proporcionado y agrega nuevos enlaces a esas redes sociales. Luego retorna el contenido actualizado con los enlaces adicionales.




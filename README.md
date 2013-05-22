# Pay with a Tweet

Plugin para WordPress 3.5.1 que permite insertar botones para poder utilizar en tu sitio web el sistema de pago con un tweet a la hora de descargar archivos. 

## Características

### Configuración

Para poder utilizar el plugin es necesario crear una aplicación en Twitter e incorporar el `consumer key` y el `consumer secret`.

A la hora de crear la aplicación es necesario establecer correctamente la `callback URL`. Esta dirección se encuentra en la [pantalla de configuración](http://www.example.com/wp-admin/admin.php?page=pay-with-tweet/admin/pwt-config.php) del plugin.

### Gestor de descargas

- [x] Los ficheros destinados a ser utilizados en "Pay with a Tweet" se suben desde una pantalla de la administración.
- [x] Para la subida de archivos se utiliza el sistema de subida de archivos de WordPress.
- [x] Los archivos subidos no forman parte del gestor de medios de WordPress.
- [x] Los archivos no son accesibles vía web.
- [x] Cuando se elimina un archivo se eliminan todos los botones que la utilizan.
- [x] Acceso a la creación de un botón desde la subida de archivos.

### Gestor de botones

- [x] Cada botón creado se vincula a un archivo. Pueden existir varios botones vinculados al mismo archivo.
- [x] En cada botón se puede establecer un título, la imagen a utilizar como botón y el texto a publicar como tweet.
- [x] Los botones se pueden eliminar o eliminar permanentemente. Esta segunda opción eliminará también la imagen y el archivo del botón.

### Soporte a varios idiomas

El plugin incorpora el idioma inglés y español. Además, en el directorio [languages](https://github.com/rolando-caldas/pay-with-a-tweet-wp-plugin/tree/master/pay-with-tweet/languages) se puede obtener el fichero .po para realizar la traducción en otros idiomas

### Shortcodes

El plugin incorpora un shortcode estilo `[pwt id='']` para poder incorporar el botón en cualquier lugar de una entrada o página.

### Widgets

El plugin incorpora un widget para colocar el botón en los sidebars. El widget permite introducir un título, un texto a mostrar antes del botón y un desplegable con los botones disponibles para seleccionar el deseado.

### Proceso de descarga

Cuando un usuario clique en el botón/enlace para descargar el fichero, tendrá que aceptar dar acceso al sitio web para publicar el tweet asociado al botón, una vez publicado correctamente, la descarga se lanza automáticamente.

    El plugin no almacena el access_token del usuario por lo que el plugin no podrá publicar tweets en la cuenta del usuario en ningún momento salvo cuando el usuario, expresamente, clica en descargar fichero.

### Estadísticas

- [x] Cuando se descarga un fichero a través de un botón de pay with a tweet, se guarda la referencia de esta descarga con fines estadísticos.
- [ ] Mostrar las estadísticas de descargas por botón. 

## Documentación

### Documentación de código

El código fuente del plugin está documentado siguiendo el estándar [phpDoc](http://www.phpdoc.org/) En el directorio [docs](https://github.com/rolando-caldas/pay-with-a-tweet-wp-plugin/tree/master/pay-with-tweet/docs) del plugin se encuentra la página de documentación generada.

### Video Manuales

Existe en Youtube una [lista de reproducción dedicada al plugin](http://www.youtube.com/playlist?list=PLLY32y927ZzQ1bjrdL5REAwVUvFnVnkE5). En ella se encuentran videos destinados a mostrar el funcionamiento y configuración del plugin.

## Instalación

Copiar la carpeta [pay-with-tweet](https://github.com/rolando-caldas/pay-with-a-tweet-wp-plugin/tree/master/pay-with-tweet) dentro del directorio `wp-content/plugins/` de tu instalación de WordPress.

Una vez copiado, activar desde [Plugins => Plugins instalados](http://www.example.com/wp-admin/plugins.php)

Tras activarlo, acceder a la [pantalla de configuración](http://www.example.com/wp-admin/admin.php?page=pay-with-tweet/admin/pwt-config.php) para dejar el plugin listo para su uso.

## Contribuir

Aún no está establecido un sistema para poder enviar contribuciones al respositorio sin que altere la rama estable. Sin embargo, mientras no se establece puedes contactar con el autor. 

Se busca, especialmente, contribuciones destinadas a aumentar el listado de idiomas soportados.

### Testing

Aunque el plugin ha sido probado, es habitual que se nos escapen cosas. Si encuentras algún error o se te ocurre alguna mejora siéntete libre de contactar con el autor, cualquier ayuda o idea siempre es bienvenida.


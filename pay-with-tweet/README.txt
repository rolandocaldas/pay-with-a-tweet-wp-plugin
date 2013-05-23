=== Pay with a tweet ===
Contributors: rolando.caldas
Donate link: 
Tags: social, viral, social marketing, viral marketing, twitter, download, pay with a tweet, tweet to download, tweet and get it, plugin, plugins, shortcode, shortcodes, widget, widgets
Requires at least: 3.3
Tested up to: 3.5.1
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin does not need services of others, unless of course Twitter.

== Description ==

Create buttons so that your visitors can download files after posting on his twitter the message you choose. You can display different buttons using shortcodes or the widget included.


== Installation ==

1. Upload `pay-with-tweet` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to pay-with-tweet configuration page and fill the fields. Video tutorial in http://www.youtube.com/watch?v=AO1_-vrLPZ0&list=PLLY32y927ZzQ1bjrdL5REAwVUvFnVnkE5&index=1

== Frequently asked questions ==

= A question that someone might have =

An answer to that question.

== Screenshots ==

1. Configuration screen
2. Manage files
3. Upload files
4. Upload files 2
5. Manage buttons
6. New button
7. Widget

== Changelog ==

= 1.0 =
Initial version

== Upgrade notice ==



== Features ==

= Configuración =

Para poder utilizar el plugin es necesario crear una aplicación en Twitter e incorporar el `consumer key` y el `consumer secret`.

A la hora de crear la aplicación es necesario establecer correctamente la `callback URL`. Esta dirección se encuentra en la [pantalla de configuración](http://www.example.com/wp-admin/admin.php?page=pay-with-tweet/admin/pwt-config.php) del plugin.

= Gestor de descargas =

* Los ficheros destinados a ser utilizados en "Pay with a Tweet" se suben desde una pantalla de la administración.
* Para la subida de archivos se utiliza el sistema de subida de archivos de WordPress.
* Los archivos subidos no forman parte del gestor de medios de WordPress.
* Los archivos no son accesibles vía web.
* Cuando se elimina un archivo se eliminan todos los botones que la utilizan.
* Acceso a la creación de un botón desde la subida de archivos.

= Gestor de botones =

* Cada botón creado se vincula a un archivo. Pueden existir varios botones vinculados al mismo archivo.
* En cada botón se puede establecer un título, la imagen a utilizar como botón y el texto a publicar como tweet.
* Los botones se pueden eliminar o eliminar permanentemente. Esta segunda opción eliminará también la imagen y el archivo del botón.

= Soporte a varios idiomas =

El plugin incorpora el idioma inglés y español. Además, en el directorio pay-with-a-tweet-wp-plugin/tree/master/pay-with-tweet/languages se puede obtener el fichero .po para realizar la traducción en otros idiomas

= Shortcodes =

El plugin incorpora un shortcode estilo `[pwt id='']` para poder incorporar el botón en cualquier lugar de una entrada o página.

= Shortcodes =

El plugin incorpora un widget para colocar el botón en los sidebars. El widget permite introducir un título, un texto a mostrar antes del botón y un desplegable con los botones disponibles para seleccionar el deseado.

= Proceso de descarga =

Cuando un usuario clique en el botón/enlace para descargar el fichero, tendrá que aceptar dar acceso al sitio web para publicar el tweet asociado al botón, una vez publicado correctamente, la descarga se lanza automáticamente.

`El plugin no almacena el access_token del usuario por lo que el plugin no podrá publicar tweets en la cuenta del usuario en ningún momento salvo cuando el usuario, expresamente, clica en descargar fichero.`

= Estadísticas =

* Cuando se descarga un fichero a través de un botón de pay with a tweet, se guarda la referencia de esta descarga con fines estadísticos. 
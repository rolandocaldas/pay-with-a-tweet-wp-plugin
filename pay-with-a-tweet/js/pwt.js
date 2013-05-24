/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery( function($) {
    var wpasTitleCounter    = $( '#pwt-message-counter' ),
    wpasTitle = $('#twitter_message').keyup( function() {
        var length = wpasTitle.val().length;
        wpasTitleCounter.text( length );
        if ( length > 140 ) {
            wpasTitleCounter.addClass( 'pwt-message-length-limit' );
        } else {
            wpasTitleCounter.removeClass( 'pwt-message-length-limit' );
        }
    } );
});
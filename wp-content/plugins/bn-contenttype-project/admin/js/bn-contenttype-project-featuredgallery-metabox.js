/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function() {
    if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
        jQuery(document).on('click', '#set-post-featuredgallery', function(e) {
            var frame;
            if (frame) {
                frame.open();
                return;
            }
            frame = wp.media({
                frame: 'post',
                state: 'insert',
                multiple: true
            });
            frame.on( 'open', function() {
                var tabs = jQuery('.media-menu a.media-menu-item');
                var separator = jQuery('.media-menu .separator');
                tabs.each(function(index,value){
                    var jqvalue = jQuery(value);
                    if (!tabstoshow.includes(jqvalue.text())){
                        jqvalue.hide();
                    }
                });
                separator.hide();

                var default_tab = jQuery('.media-menu a.media-menu-item:contains("'+ tabdefault +'")');
                default_tab.click();
            });
            frame.on( 'update', function() {
                var controller = frame.states.get('gallery-edit');
                var library = controller.get('library');
                var shortcode = wp.media.gallery.shortcode(library).string();
                var input = jQuery("#featuredgallery");
                var output = jQuery("#featuredgallery_mb div.inside");
                var deleteAttachment = '<a href="#" id="remove-post-featuredgallery">'+ messages.removeattachment +'</a>'
                jQuery.ajax({
                    url: ajaxurl + "?XDEBUG_SESSION_START=netbeans-xdebug",
                    type: "post",
                    data: {
                        shortcode: shortcode,
                        action: "getfeaturedgalleryview",
                    },
                    success: function(data) {
                        if (data.success){
                            jQuery("a#set-post-featuredgallery").remove();
                            output.append(data.html);
                            output.append(deleteAttachment);
                            input.val(shortcode);
                        }
                    }
                });
            });
            frame.open();
        });
        jQuery(document).on('click', '#remove-post-featuredgallery', function(e) {
            var button = jQuery(this);
            var output = jQuery("#featuredgallery_mb div.inside");
            var input = jQuery("#featuredgallery");
            var gallery = button.parent().find("div.gallery");
            var setAttachment = '<a href="#" id="set-post-featuredgallery">'+ messages.setattachment +'</a>';
            input.val("");
            output.append(setAttachment);
            gallery.remove();
            button.remove();
        });
    }
});
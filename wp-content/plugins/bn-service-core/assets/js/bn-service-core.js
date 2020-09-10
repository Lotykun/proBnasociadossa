/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function() {
    jQuery("body").on('click', 'input#element', function(){ /*EXAMPLE CLICK EVENT TO AJAX ACTION*/
        var element = "elemento";
        jQuery.ajax({
            url: ajaxurl + "?XDEBUG_SESSION_START=netbeans-xdebug", /*AJAXURL IS THE LOCALIZE VARIABLE...XDEBUG IS OPTIONAL IN ORDER TO DEBUG THE PHP ASYNC ACTION*/
            type: "post",
            data: {
                element: element, /* PRE VARIABLES FOR THE ACTION*/
                action: "asyncfunction", /* NAME OF THE AJAX ACTION.. RESPECT THE SAME NAME IN EVERY DEFINITION*/
            },
            success: function(data) {
                /*RETURN OF ASYNC CALL*/
                /*IF DATA.SUCCESS DO SOMEHTING*/
                /*ELSE DO SOMEHTING*/
            }
        });
        
    });
})



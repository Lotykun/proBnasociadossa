/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function() {
    jQuery("form").submit(function() {
        var input = jQuery("input[type=submit][clicked=true]").attr("id");
        if (input === "doaction"){
            var action = jQuery("#bulk-action-selector-top option:selected").val();
            if (action === "addnewlibrary") {
                render_new_library_row();
                return false;
            }
        } else if (input === "init") {
            jQuery('<input>').attr({type: 'hidden',name: 'action',value: 'init'}).appendTo('form');
        }
    });
    jQuery("form input[type=submit]").click(function() {
        jQuery("input[type=submit]", jQuery(this).parents("form")).removeAttr("clicked");
        jQuery(this).attr("clicked", "true");
    });
    jQuery("body").on('change', 'td.title input[type=text]', function(){
        var value = jQuery(this).val();
        var id = generate_field_id(value);
        
        var inputs = jQuery(this).closest("tr").find(":input");
        inputs.each(function(){
            var input_name = jQuery(this).attr("name");
            if (input_name.indexOf("_") === -1){
                var new_input_name = "library_" + id + "_" + input_name;
                jQuery(this).attr("name", new_input_name);
            }
        });
    });
    jQuery("body").on('click', '.action_field_delete', function(){
        var target = jQuery(this);
        target.closest("tr").remove();
    });
});
function render_new_library_row(){
    var html = '\n\
                <tr>\n\
                    <th scope="row" class="check-column">\n\
                        <input type="checkbox" name="cb" value="0">\n\
                    </th>\n\
                    <td class="title column-title has-row-actions column-primary" data-colname="Title">\n\
                        <input type="text" name="title" placeholder="'+ configureLiterals.placeholder +'" value="Kaltura"> \n\
                        <input type="hidden" name="required" value="0"> \n\
                        <div class="row-actions">\n\
                            <span class="delete">\n\
                                <a href="#" class="action_field_delete">'+ configureLiterals.actions.delete +'</a>\n\
                            </span>\n\
                        </div>\n\
                    </td>\n\
                    <td class="composer column-composer" data-colname="Library Composer">\n\
                        <input type="text" name="composer" placeholder="'+ configureLiterals.placeholder +'" value="kaltura/api-client-library"> \n\
                    </td>\n\
                    <td class="version column-version" data-colname="Library Version">\n\
                        <input type="text" name="version" placeholder="'+ configureLiterals.placeholder +'" value="v13.17.0"> \n\
                    </td>\n\
                </tr>';
    jQuery('table.wp-list-table tbody tr:last').after(html);
}
function generate_field_id(value){
    var response = value; 
    if (/\s/g.test(value)){
        var parts = value.split(" ");
        var i;
        for (i = 0; i < parts.length; i++) {
            response += parts[i].substring(0, 3);
        }
    }
    response = response.toLowerCase();
    return response;
}
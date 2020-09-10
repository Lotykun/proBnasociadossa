/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function() {
    jQuery("form").submit(function() {
        var input = jQuery("input[type=submit][clicked=true]").attr("id");
        if (input === "doaction"){
            var action = jQuery( "#bulk-action-selector-top option:selected" ).val();
            if (action === "addnewfield"){
                render_new_field_row();
                return false;
            } else if (action === "delete"){
                if (confirm(configureLiterals.question) == true) {
                    return true;
                } else {
                    return false;
                }
            }
        } else if (input === "savedata") {
            var fieldcount = jQuery('table.wp-list-table tbody tr').length;
            jQuery('<input>').attr({type: 'hidden',name: 'action',value: 'savedata'}).appendTo('form');
            jQuery('<input>').attr({type: 'hidden',name: 'fieldcount',value: fieldcount}).appendTo('form');
            //validation
        }
    });
    jQuery("form input[type=submit]").click(function() {
        jQuery("input[type=submit]", jQuery(this).parents("form")).removeAttr("clicked");
        jQuery(this).attr("clicked", "true");
    });
    jQuery("body").on('change', 'td.fieldtitle input[type=text]', function(){
        var value = jQuery(this).val();
        var id = generate_field_id(value);

        var inputs = jQuery(this).closest("tr").find(":input");
        inputs.each(function(){
            var input_name = jQuery(this).attr("name");
            if (input_name.indexOf("_") === -1){
                var new_input_name = "field_" + id + "_" + input_name;
                jQuery(this).attr("name", new_input_name);
                if (input_name === "metaboxid"){
                    jQuery(this).val(id + "_mb");
                } else if (input_name === "metaboxlabel"){
                    jQuery(this).val(id + "_label");
                }
            }
        });
    });
    jQuery("body").on('change', 'td.isrequired input[type=checkbox]', function(){
        var current_cb = jQuery(this);
        var hidden = jQuery("input[type='hidden'][name='"+ jQuery(this).attr("name") +"']");
        
        if(current_cb.is(':checked')){
            current_cb.val("1");
            if (hidden.length){
                hidden.remove();
            }
        } else {
            current_cb.val("0");
            if (!hidden.length){
                jQuery(this).parent().after('<input type="hidden" name="'+ jQuery(this).attr("name") +'" value="'+ jQuery(this).val() +'"/>');
            }
        }
    });
    jQuery("body").on('change', 'td.isenabled input[type=checkbox]', function(){
        var current_cb = jQuery(this);
        if(current_cb.is(':checked')){
            current_cb.closest("tr").find("td.fieldtitle a").removeClass("disabled_link");
            current_cb.val("1");
        } else {
            current_cb.closest("tr").find("td.fieldtitle a").addClass("disabled_link");
            current_cb.val("0");
        }
        
        var inputs = current_cb.closest("tr").find(":input").filter(function(){
            return ((jQuery(this).prop("tagName") === "INPUT" || jQuery(this).prop("tagName") === "SELECT") 
                    && !jQuery(this).attr("name").endsWith("isenabled") 
                    && jQuery(this).attr("type") !== "hidden");
        });
        
        inputs.each(function(){
            var hidden = jQuery("input[type='hidden'][name='"+ jQuery(this).attr("name") +"']");
            if(current_cb.is(':checked')){
                if (jQuery(this).attr("name").indexOf("metabox") >= 0){
                    if (jQuery(this).attr("type") === "checkbox"){
                        jQuery(this).prop('disabled', false);
                    } else {
                        var hasmetabox = jQuery(this).closest("tr").find("td.hasmetabox input[type=checkbox]");
                        if(hasmetabox.is(':checked')){
                            jQuery(this).prop('disabled', false);
                        }
                    }
                } else {
                    jQuery(this).prop('disabled', false);
                }
                if (hidden.length){
                    hidden.remove();
                }
            } else {
                jQuery(this).prop('disabled', true);
                if (!hidden.length){
                    jQuery(this).parent().after('<input type="hidden" name="'+ jQuery(this).attr("name") +'" value="'+ jQuery(this).val() +'"/>');
                }
            }
        });
    });
    jQuery("body").on('change', 'td.hasmetabox input[type=checkbox]', function(){
        var current_cb = jQuery(this);
        var hidden = jQuery("input[type='hidden'][name='"+ jQuery(this).attr("name") +"']");
        if(current_cb.is(':checked')){
            current_cb.val("1");
            if (hidden.length){
                hidden.remove();
            }
        } else {
            current_cb.val("0");
            if (!hidden.length){
                jQuery(this).parent().after('<input type="hidden" name="'+ jQuery(this).attr("name") +'" value="'+ jQuery(this).val() +'"/>');
            }
        }
        var inputs = current_cb.closest("tr").find(":input").filter(function(){
            return ((jQuery(this).prop("tagName") === "INPUT" || jQuery(this).prop("tagName") === "SELECT") 
                    && !jQuery(this).attr("name").endsWith("hasmetabox") 
                    && jQuery(this).attr("type") !== "hidden" 
                    && jQuery(this).attr("name").indexOf("metabox") >= 0);
        });
        inputs.each(function(){
            var input_name = jQuery(this).attr("name");
            var id = jQuery(this).attr("name").split("_")[1];
            var hidden = jQuery("input[type='hidden'][name='"+ jQuery(this).attr("name") +"']");
            if(current_cb.is(':checked')){
                jQuery(this).prop('disabled', false);
                if (hidden.length){
                    hidden.remove();
                }
                if (input_name === "field_" + id + "_metaboxid"){
                    jQuery(this).val(id + "_mb");
                } else if (input_name === "field_" + id + "_metaboxlabel"){
                    jQuery(this).val(id + "_label");
                }
            } else {
                jQuery(this).prop('disabled', true);
                if (!hidden.length){
                    jQuery(this).parent().after('<input type="hidden" name="'+ jQuery(this).attr("name") +'" value="'+ jQuery(this).val() +'"/>');
                }
            }
        });
    });
    jQuery("body").on('change', '#TB_window input[type=checkbox]', function(){
        var current_cb = jQuery(this);
        var inputs = current_cb.closest("#TB_window").find(":input").filter(function(){
            return (jQuery(this).prop("tagName") === "INPUT" && !jQuery(this).attr("name").endsWith("validation"));
        });
        inputs.each(function(){
            if(current_cb.is(':checked')){
                jQuery(this).prop('disabled', false);
            } else {
                jQuery(this).prop('disabled', true);
            }
        });
    });
    jQuery("body").on('click', 'button.close-thickbox', function(){
        self.parent.tb_remove();
    });
    jQuery("body").on('click', '.action_field_delete', function(){
        var target = jQuery(this);
        target.closest("tr").remove();
    });
});
function render_new_field_row(){
    var html = '\n\
                <tr>\n\
                    <th scope="row" class="check-column">\n\
                        <input type="checkbox" name="cb" value="" disabled>\n\
                    </th>\n\
                    <td class="fieldtitle column-fieldtitle has-row-actions column-primary" data-colname="Title">\n\
                        <input type="text" name="label" placeholder="'+ configureLiterals.placeholder +'" value="" disabled> \n\
                            <div class="row-actions">\n\
                                <span class="edit">\n\
                                    <a href="#TB_inline?width=1500&height=250&inlineId=validation_thickbox" class="thickbox" title="">'+ configureLiterals.actions.validation +'</a> | \n\
                                </span>\n\
                                <span class="delete">\n\
                                    <a href="#" class="action_field_delete">'+ configureLiterals.actions.delete +'</a>\n\
                                </span>\n\
                            </div>\n\
                    </td>\n\
                    <td class="fieldtype column-fieldtype" data-colname="Type">\n\
                        <select name="type" disabled>\n\
                            <option value="areatext">AreaText</option>\n\
                            <option value="text">Text</option>\n\
                            <option value="checkbox">Checkbox</option>\n\
                        </select>\n\
                    </td>\n\
                    <td class="hasmetabox column-hasmetabox" data-colname="Metabox">\n\
                        <input type="checkbox" name="hasmetabox" value="1" disabled>\n\
                    </td>\n\
                    <td class="metaboxid column-metaboxid" data-colname="Metabox ID">\n\
                        <input type="text" name="metaboxid" placeholder="'+ configureLiterals.placeholder +'" value="" disabled>\n\
                    </td>\n\
                    <td class="metaboxlabel column-metaboxlabel" data-colname="Metabox Label">\n\
                        <input type="text" name="metaboxlabel" placeholder="'+ configureLiterals.placeholder +'" value="" disabled>\n\
                    </td>\n\
                    <td class="metaboxcontext column-metaboxcontext" data-colname="Metabox Context">\n\
                        <select name="metaboxcontext" disabled>\n\
                            <option value="side">Side</option>\n\
                            <option value="normal">Normal</option>\n\
                            <option value="advanced">Advanced</option>\n\
                        </select>\n\
                    </td>\n\
                    <td class="isrequired column-isrequired" data-colname="Required">\n\
                        <input type="checkbox" name="isrequired" value="1" disabled>\n\
                    </td>\n\
                    <td class="isenabled column-isenabled" data-colname="Enabled">\n\
                        <input type="checkbox" name="isenabled" value="1">\n\
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



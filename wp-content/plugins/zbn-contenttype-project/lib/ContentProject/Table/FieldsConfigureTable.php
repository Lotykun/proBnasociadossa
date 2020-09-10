<?php
namespace BN\ContentProject\Table;

use BN\ContentProject\AdminController;
use BN\ContentProject\Helpers;

class Fields_Configure_Table extends \WP_List_Table {
    public function get_columns(){
        $columns = array(
            'cb'        => '',
            'fieldtitle' => __('Title',Helpers::LOCALE),
            'fieldtype'    => __('Type',Helpers::LOCALE),
            'hasmetabox'      => __('Metabox',Helpers::LOCALE),
            'metaboxid'      => __('Metabox ID',Helpers::LOCALE),
            'metaboxlabel'      => __('Metabox Label',Helpers::LOCALE),
            'metaboxcontext'      => __('Metabox Context',Helpers::LOCALE),
            'isrequired'      => __('Required',Helpers::LOCALE),
            'isenabled'      => __('Enabled',Helpers::LOCALE),
        );
        return $columns;
    }
    
    public function column_cb($item) {
        $disabled = ($item['readonly'] || !$item['isenabled']) ? " disabled" : "";
        $hidden = ($item['readonly'] || !$item['isenabled']) ? '<input type="hidden" name="field_'.$item['ID'].'_cb" value="0" />' : '';
        $readonly = ($item['readonly']) ? 
            '<input type="hidden" name="field_'.$item['ID'].'_readonly" value="1" />' : 
            '<input type="hidden" name="field_'.$item['ID'].'_readonly" value="0" />';
        
        $metakey = '<input type="hidden" name="field_'.$item['ID'].'_metakey" value="'.$item['metakey'].'" />';
        
        return '<input type="checkbox" name="field_'.$item['ID'].'_cb" value="0"'.$disabled.' />'.$metakey.$readonly.$hidden;    
    }
    
    public function column_fieldtitle($item) {
        $disabled = ($item['readonly'] || !$item['isenabled']) ? " disabled" : "";
        $hidden = ($item['readonly'] || !$item['isenabled']) ? '<input type="hidden" name="field_'.$item['ID'].'_label" value="'.$item['fieldtitle'].'" />' : "";
        $disabled_actions = ($item['readonly'] || !$item['isenabled']) ? " disabled_link" : "";
        
        $actions = array(
            'edit'      => '<a href="#TB_inline?width=1500&height=250&inlineId=validation_thickbox_'.$item['ID'].'" class="thickbox'.$disabled_actions.'" title="'.__('Edit Validation', Helpers::LOCALE).' '.$item['fieldtitle'].'">'.__('Edit Validation', Helpers::LOCALE).'</a>',
            'delete'    => '<a href="#" class="action_field_delete '.$disabled_actions.'">'.__('Delete', Helpers::LOCALE).'</a>',
        );
        
        $thickbox = $this->render_field_validation_thickbox($item);
        return sprintf(''
                . '<input '
                . 'type="text" '
                . 'name="field_'.$item['ID'].'_label" '
                . 'placeholder="'.__('Please Fill',Helpers::LOCALE).'" '
                . 'value="%1$s"'.$disabled.' />'.$hidden.$thickbox.' '
                . '%2$s', 
                $item['fieldtitle'],
                $this->row_actions($actions) 
        );
    }
    
    public function column_fieldtype($item) {
        $optionSet = ($item['readonly']) ? '<option value="'.$item['fieldtype'].'" selected>'.$item['fieldtitle'].'</option>' : '';
        $disabled = ($item['readonly'] || !$item['isenabled']) ? " disabled" : "";
        $hidden = ($item['readonly'] || !$item['isenabled']) ? '<input type="hidden" name="field_'.$item['ID'].'_type" value="'.$item['fieldtype'].'" />' : "";
        
        $output = '<select name="field_'.$item['ID'].'_type"'.$disabled.'>'
                    . $optionSet
                    . '<option value="areatext" '.selected($item['fieldtype'], "areatext", FALSE ).'>AreaText</option>'
                    . '<option value="text" '.selected($item['fieldtype'], "text", FALSE ).'>Text</option>'
                    . '<option value="checkbox" '.selected($item['fieldtype'], "checkbox", FALSE ).'>Checkbox</option>'
                . '</select>'.$hidden;
        
        
        return sprintf($output);
    }
    
    public function column_metaboxcontext($item) {
        $disabled = ($item['readonly'] || !$item['isenabled'] || !$item['hasmetabox']) ? " disabled" : "";
        $hidden = ($item['readonly'] || !$item['isenabled'] || !$item['hasmetabox']) ? '<input type="hidden" name="field_'.$item['ID'].'_metaboxcontext" value="'.$item['metaboxcontext'].'" />' : "";
        
        $output = '<select name="field_'.$item['ID'].'_metaboxcontext"'.$disabled.'>'
                    . '<option value="side" '.selected($item['metaboxcontext'], "side", FALSE ).'>Side</option>'
                    . '<option value="normal" '.selected($item['metaboxcontext'], "normal", FALSE ).'>Normal</option>'
                    . '<option value="advanced" '.selected($item['metaboxcontext'], "advanced", FALSE ).'>Advanced</option>'
                . '</select>'.$hidden;
        
        
        return sprintf($output);
    }
    
    public function column_hasmetabox($item) {
        $value = ($item['hasmetabox']) ? "1" : "0";
        $output = ($item['hasmetabox']) ? " checked" : "";
        $disabled = ($item['readonly'] || !$item['isenabled']) ? " disabled" : "";
        $hidden = ($item['readonly'] || !$item['isenabled'] || !$item['hasmetabox']) ? '<input type="hidden" name="field_'.$item['ID'].'_hasmetabox" value="'.$value.'" />' : "";
        
        return '<input type="checkbox" name="field_'.$item['ID'].'_hasmetabox" value="'.$value.'"'.$output.$disabled.' />'.$hidden;
    }
    
    public function column_isrequired($item) {
        $value = ($item['isrequired']) ? "1" : "0";
        $output = ($item['isrequired']) ? " checked" : "";
        $disabled = ($item['readonly'] || !$item['isenabled']) ? " disabled" : "";
        $hidden = ($item['readonly'] || !$item['isenabled'] || !$item['isrequired']) ? '<input type="hidden" name="field_'.$item['ID'].'_isrequired" value="'.$value.'" />' : "";
        
        return '<input type="checkbox" name="field_'.$item['ID'].'_isrequired" value="'.$value.'"'.$output.$disabled.' />'.$hidden;    
    }
    
    public function column_isenabled($item) {
        $value = ($item['isenabled']) ? "1" : "0";
        $output = ($item['isenabled']) ? " checked" : "";
        $disabled = ($item['readonly']) ? " disabled" : "";
        $hidden = ($item['readonly']) ? '<input type="hidden" name="field_'.$item['ID'].'_isenabled" value="'.$value.'" />' : "";
                
        return '<input type="checkbox" name="field_'.$item['ID'].'_isenabled" value="'.$value.'"'.$output.$disabled.' />'.$hidden;    
    }
    
    public function column_default( $item, $column_name ) {
        switch( $column_name ) { 
            case 'metaboxid':
            case 'metaboxlabel':
            case 'metaboxcontext':
                $disabled = ($item['readonly'] || !$item['isenabled'] || !$item['hasmetabox']) ? " disabled" : "";
                $hidden = ($item['readonly'] || !$item['isenabled'] || !$item['hasmetabox']) ? '<input type="hidden" name="field_'.$item['ID'].'_'.$column_name.'" value="'.$item[ $column_name ].'" />' : "";
                
                return sprintf(
                    '<input '
                    . 'type="text" '
                    . 'name="field_'.$item['ID'].'_'.$column_name.'" '
                    . 'placeholder="'.__('Please Fill',Helpers::LOCALE).'" '
                    . 'value="%s"'.$disabled.' />'.$hidden,
                    $item[ $column_name ]
                );
            default:
                return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }

    public function prepare_items() {
        $fields = Helpers::getOption(Helpers::NAMESPACE."_fields");
        $items = array();
        $i = 0;
        foreach ($fields["extra"] as $fieldvalue) {
            $items[$i]['ID'] = $fieldvalue['id'];
            $items[$i]['fieldtitle'] = $fieldvalue['label'];
            $items[$i]['fieldtype'] = $fieldvalue['type'];
            $items[$i]['hasmetabox'] = (isset($fieldvalue['metabox'])) ? TRUE : FALSE;
            $items[$i]['metaboxid'] = (isset($fieldvalue['metabox'])) ? $fieldvalue['metabox']['id'] : "";
            $items[$i]['metaboxlabel'] = (isset($fieldvalue['metabox'])) ? $fieldvalue['metabox']['label'] : "";
            $items[$i]['metaboxcontext'] = (isset($fieldvalue['metabox'])) ? $fieldvalue['metabox']['context'] : "";
            $items[$i]['validation'] = (isset($fieldvalue['validation'])) ? TRUE : FALSE;
            $items[$i]['validationpattern'] = (isset($fieldvalue['validation']['pattern'])) ? $fieldvalue['validation']['pattern'] : "";
            $items[$i]['validationwidth'] = (isset($fieldvalue['validation']['width'])) ? $fieldvalue['validation']['width'] : "";
            $items[$i]['metakey'] = $fieldvalue['metakey'];
            $items[$i]['readonly'] = $fieldvalue['readonly'];
            $items[$i]['isrequired'] = $fieldvalue['required'];
            $items[$i]['isenabled'] = $fieldvalue['enabled'];
            $i++;
        }
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $items;
    }
    
    public function get_bulk_actions() {
        $actions = array(
            'addnewfield'    => __('Add new Field',Helpers::LOCALE),
            /*'delete'    => __('Delete',Helpers::LOCALE),*/
        );
        return $actions;
    }
    
    public function no_items() {
        _e( 'No books found, dude.' );
    }
    
    private function render_field_validation_thickbox($item) {
        $output = ($item['validation']) ? " checked" : "";
        
        $pattern = ($item['validation'] && isset($item['validationpattern'])) ? $item['validationpattern'] : "";
        $width = ($item['validation'] && isset($item['validationwidth'])) ? $item['validationwidth'] : "";
        
        
        $response = '
            <div id="validation_thickbox_'.$item['ID'].'" style="display:none;">
                <p>
                    <label for="field_'.$item['ID'].'_validation">'.__('Enable Field Validation', Helpers::LOCALE).'</label>
                    <input type="checkbox" name="field_'.$item['ID'].'_validation" value="'.$item['validation'].'"'.$output.' />
                </p>
                <p>
                    <label for="field_'.$item['ID'].'_validationpattern">'.__('Field Validation Match Pattern', Helpers::LOCALE).'</label>
                    <input type="text" name="field_'.$item['ID'].'_validationpattern" value="'.$pattern.'" placeholder="'.__('Please Fill', Helpers::LOCALE).'"/>
                </p>
                <p>
                    <label for="field_'.$item['ID'].'_validationwidth">'.__('Field Validation Max Width', Helpers::LOCALE).'</label>
                    <input type="text" name="field_'.$item['ID'].'_validationwidth" value="'.$width.'" placeholder="'.__('Please Fill', Helpers::LOCALE).'"/>
                </p>
                <p>
                    '.__('You must save changes after close this window.', Helpers::LOCALE).' <button class="button close-thickbox"/>'.__('Close Window', Helpers::LOCALE).'</button>
                </p>
            </div>';
        
        return $response;
    }
}

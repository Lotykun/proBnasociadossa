<?php
namespace BN\Core\Table;

use \BN\Core\AdminController;
use \BN\Core\Helpers;

class Libraries_Configure_Table extends \WP_List_Table {
    public function get_columns(){
        $columns = array(
            'cb'        => '',
            'title'     => __('Library Name',Helpers::LOCALE),
            'composer'  => __('Library Composer',Helpers::LOCALE),
            'version'   => __('Library Version',Helpers::LOCALE),            
        );
        return $columns;
    }
    
    public function column_cb($item) {
        $value = "0";
        $disabled = ($item['required']) ? " disabled" : "";
        $hidden = ($item['required']) ? '<input type="hidden" name="library_'.$item['ID'].'_cb" value="'.$value.'" />' : '';
        $required_value = ($item['required']) ? "1" : "0";
        $required = '<input type="hidden" name="library_'.$item['ID'].'_required" value="'.$required_value.'" />';
        
        return '<input type="checkbox" name="library_'.$item['ID'].'_cb" value="'.$value.'"'.$disabled.' />'.$hidden.$required;
    }
    
    public function column_title($item) {
        $value = $item['title'];
        $disabled = ($item['required']) ? " disabled" : "";
        $hidden = ($item['required']) ? '<input type="hidden" name="library_'.$item['ID'].'_title" value="'.$value.'" />' : "";
        $disabled_actions = ($item['required']) ? " disabled_link" : "";
        
        $actions = array(
            'delete'    => '<a href="#" class="action_field_delete '.$disabled_actions.'">'.__('Delete', Helpers::LOCALE).'</a>',
        );
        
        return sprintf(''
                . '<input '
                . 'type="text" '
                . 'name="library_'.$item['ID'].'_title" '
                . 'placeholder="'.__('Please Fill',Helpers::LOCALE).'" '
                . 'value="%1$s"'.$disabled.' />'.$hidden.' '
                . '%2$s', 
                $value,
                $this->row_actions($actions) 
        );
    }
    
    public function column_isrequired($item) {
        $value = ($item['required']) ? "1" : "0";
        $output = ($item['required']) ? " checked" : "";
        $disabled = ($item['required']) ? " disabled" : "";
        $hidden = ($item['required']) ? '<input type="hidden" name="library_'.$item['ID'].'_required" value="'.$value.'" />' : "";
        
        return '<input type="checkbox" name="field_'.$item['ID'].'_isrequired" value="'.$value.'"'.$output.$disabled.' />'.$hidden;    
    }
    
    public function column_default( $item, $column_name ) {
        switch( $column_name ) { 
            case 'composer':
            case 'version':
                $value = $item[ $column_name ];
                $disabled = ($item['required']) ? " disabled" : "";
                $hidden = ($item['required']) ? '<input type="hidden" name="library_'.$item['ID'].'_'.$column_name.'" value="'.$value.'" />' : "";
                
                return sprintf(
                    '<input '
                    . 'type="text" '
                    . 'name="library_'.$item['ID'].'_'.$column_name.'" '
                    . 'placeholder="'.__('Please Fill',Helpers::LOCALE).'" '
                    . 'value="%s"'.$disabled.' />'.$hidden,
                    $item[ $column_name ]
                );
            default:
                return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }

    public function prepare_items() {
        $libraries = Helpers::getOption("libraries");
        $items = array();
        $i = 0;
        foreach ($libraries as $library) {
            $items[$i]['ID'] = $library['ID'];
            $items[$i]['title'] = $library['title'];
            $items[$i]['composer'] = $library['composer'];
            $items[$i]['version'] = $library['version'];
            $items[$i]['required'] = $library['required'];
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
            'addnewlibrary'    => __('Add new Library',Helpers::LOCALE),
        );
        return $actions;
    }
    
    public function no_items() {
        _e( 'No books found, dude.' );
    }
}

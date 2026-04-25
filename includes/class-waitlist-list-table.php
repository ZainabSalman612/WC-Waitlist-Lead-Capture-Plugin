<?php
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Waitlist_List_Table extends WP_List_Table {
    
    public function __construct() {
        parent::__construct(array(
            'singular' => 'lead',
            'plural'   => 'leads',
            'ajax'     => false
        ));
    }
    
    public function get_columns() {
        return array(
            'cb'         => '<input type="checkbox" />',
            'name'       => 'Name',
            'email'      => 'Email',
            'phone'      => 'Phone',
            'interest'   => 'Interest',
            'status'     => 'Status',
            'created_at' => 'Date'
        );
    }
    
    public function get_sortable_columns() {
        return array(
            'name'       => array('name', false),
            'email'      => array('email', false),
            'status'     => array('status', false),
            'created_at' => array('created_at', false)
        );
    }
    
    protected function column_default( $item, $column_name ) {
        return isset($item[$column_name]) ? esc_html($item[$column_name]) : '';
    }
    
    protected function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="lead[]" value="%s" />',
            $item['id']
        );
    }
    
    protected function get_bulk_actions() {
        return array(
            'mark_contacted' => 'Mark Contacted',
            'mark_converted' => 'Mark Converted'
        );
    }
    
    public function prepare_items() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'waitlist_leads';
        
        $per_page = 20;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        $current_page = $this->get_pagenum();
        
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? sanitize_text_field($_REQUEST['orderby']) : 'id';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? sanitize_text_field($_REQUEST['order']) : 'desc';
        $search = isset($_REQUEST['s']) ? sanitize_text_field($_REQUEST['s']) : '';
        
        $where = '';
        if ( !empty($search) ) {
            $where = $wpdb->prepare("WHERE name LIKE %s OR email LIKE %s", '%' . $wpdb->esc_like($search) . '%', '%' . $wpdb->esc_like($search) . '%');
        }
        
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM {$table_name} {$where}");
        
        $offset = ($current_page - 1) * $per_page;
        
        $this->items = $wpdb->get_results(
            "SELECT * FROM {$table_name} {$where} ORDER BY {$orderby} {$order} LIMIT {$offset}, {$per_page}", 
            ARRAY_A
        );
        
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));
    }
}

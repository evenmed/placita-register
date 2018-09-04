<?php

if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}




class Baptism_Registers_Table extends WP_List_Table {
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $data = $this->table_data();
        usort( $data, array( &$this, 'sort_data' ) );
        $perPage = 20;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);
        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );
        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns() {
        return array(
            'first_name'       => __('First Name', 'laplacita'),
            'last_name'        => __('Last Name', 'laplacita'),
            'priest'           => __('Priest', 'laplacita'),
            'baptism_date'     => __('Baptism Date', 'laplacita'),
            'birthdate'        => __('Birth Date', 'laplacita'),
            'amount_collected' => __('Payment Collected', 'laplacita'),
            'benches'          => __('Benches', 'laplacita'),
            'is_canceled'      => __('Canceled', 'laplacita'),
            'is_noshow'        => __('No Show', 'laplacita'),
            'is_private'       => __('Private', 'laplacita'),
        );
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns() {
        return array(
            'father_phone' => __('Father\'s Phone', 'laplacita'),
            'mother_phone' => __('Mother\'s Phone', 'laplacita'),
            'date'         => __('Registry Date', 'laplacita'),
        );
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns() {
        return array(
            'first_name'   => array('first_name', false),
            'last_name'    => array('last_name', false),
            'birthdate'    => array('birthdate', false),
        );
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data() {
        global $wpdb;
        $search = isset($_REQUEST['s']) ? $_REQUEST['s'] : '';

        $search_query = $search ? "WHERE first_name LIKE '%$search%'
        OR last_name LIKE '%$search%'
        OR birthdate LIKE '%$search%'
        OR father_phone LIKE '%$search%'
        OR mother_phone LIKE '%$search%'" : "";

        $table_name = $wpdb->prefix . 'baptism_registers';
        $sql = sprintf(
            'SELECT id, first_name, last_name, birthdate, father_phone, mother_phone, date, priest, baptism_date, amount_collected, benches, is_canceled, is_noshow, is_private
            FROM %s %s
            ORDER BY date DESC',
            $table_name,
            $search_query
        );

        $results = $wpdb->get_results( $sql , ARRAY_A );

        return $results;
    }

    // Add actions to the first column
    function column_first_name($item){
        
        //Build row actions
        $actions = array(
            'view' => sprintf(
                '<a href="%s" target="_blank">View</a>',
                wp_nonce_url(
                    add_query_arg(
                        array(
                            'baptism_registry' => $item['id'],
                            'action' => 'baptism_register_view_pdf'
                        ),
                        admin_url('admin-post.php')
                    ),
                    'view_baptism_registry_pdf'
                )
            ),
        );
        
        //Return the title contents
        return sprintf('%1$s %2$s',
            /*$1%s*/ $item['first_name'],
            /*$2%s*/ $this->row_actions($actions)
        );
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name ) {
        $id = $item['id'];
        switch( $column_name ) {
            case 'first_name':
            case 'last_name':
            case 'father_phone':
            case 'mother_phone':
                return stripslashes($item[ $column_name ]);

            case 'date':
                return $item[ $column_name ];

            case 'birthdate':
                $date = date_create_from_format('Y-m-d', $item[ $column_name ]);
                $value = $date ? htmlentities($date->format('Y/m/d')) : '';
                return '<span class="value-label">' . $value . '</span>' .
                ' <a href="#" class="edit-registry-field">' . 
                    __('Edit', 'laplacita') .
                    '<span class="dashicons dashicons-edit"></span>' .
                '</a>' .
                "<input
                    name='$column_name'
                    class='input_$column_name registry-update datetimepicker'
                    data-registry='$id'
                    autocomplete='off'
                    value='". htmlentities($value) ."'
                    />";

            case 'priest':
                return '<span class="value-label">' . stripslashes($item[ $column_name ]) . '</span>' .
                ' <a href="#" class="edit-registry-field">' . 
                    __('Edit', 'laplacita') .
                    '<span class="dashicons dashicons-edit"></span>' .
                '</a>' .
                "<input
                    name='$column_name'
                    class='input_$column_name registry-update'
                    data-registry='$id'
                    autocomplete='off'
                    value=". htmlentities(stripslashes($item[ $column_name ])) ."
                    />";

            case 'baptism_date':
                $date = date_create_from_format('Y-m-d H:i:s', $item[ $column_name ]);
                $value = $date ? htmlentities($date->format('Y/m/d H:i')) : '';
                return '<span class="value-label">' . $value . '</span>' .
                ' <a href="#" class="edit-registry-field">' . 
                    __('Edit', 'laplacita') .
                    '<span class="dashicons dashicons-edit"></span>' .
                '</a>' .
                "<input
                    name='$column_name'
                    class='input_$column_name registry-update datetimepicker'
                    data-registry='$id'
                    autocomplete='off'
                    value='". htmlentities($value) ."'
                    />";

            case 'amount_collected':
                return '$' . '<span class="value-label">' . $item[ $column_name ] . '</span>' .
                ' <a href="#" class="edit-registry-field">' . 
                    __('Edit', 'laplacita') .
                    '<span class="dashicons dashicons-edit"></span>' .
                '</a>' .
                "<input
                    type='number'
                    step='0.01'
                    name='$column_name'
                    class='input_$column_name registry-update'
                    data-registry='$id'
                    value='". htmlentities($item[ $column_name ]) ."'
                    />";

            case 'benches':
                global $bench_numbers;
                $benches = $item[ $column_name ];
                $benches_string = "";

                foreach ($bench_numbers as $b) {
                    $selected = $benches == $b ? 'selected' : '';
                    $benches_string .= "<option ". $selected ." value='$b'>$b</option>";
                }

                return '<span class="value-label">' . $benches . '</span>' .
                ' <a href="#" class="edit-registry-field">' . 
                    __('Edit', 'laplacita') .
                    '<span class="dashicons dashicons-edit"></span>' .
                '</a>' .
                "<select
                    name='$column_name'
                    class='input_$column_name registry-update'
                    data-registry='$id'
                >
                    <option value=''>Select Bench</option>
                    $benches_string
                </select>";

            case 'is_canceled':
            case 'is_noshow':
            case 'is_private':
                $value = $item[ $column_name ];
                $label = $value ? 'Yes' : 'No';
                $yes   = $value ? 'selected' : '';
                $no    = $value ? '' : 'selected';
                return '<span class="value-label">' . $label . '</span>' .
                ' <a href="#" class="edit-registry-field">' . 
                    __('Edit', 'laplacita') .
                    '<span class="dashicons dashicons-edit"></span>' .
                '</a>' .
                "<select
                    name='$column_name'
                    class='input_$column_name registry-update'
                    data-registry='$id'
                >
                    <option $yes value='1'>Yes</option>
                    <option $no value='0'>No</option>
                </select>";

            default:
                return '';
        }
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data( $a, $b ) {
        // Set defaults
        $orderby = 'first_name';
        $order = 'asc';
        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }
        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }
        $result = strcmp( $a[$orderby], $b[$orderby] );
        if($order === 'asc')
        {
            return $result;
        }
        return -$result;
    }
}
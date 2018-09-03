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

                $benches = $item[ $column_name ];

                $benchA1 = $benches == 'A1' ? 'selected' : '';
                $benchA2 = $benches == 'A2' ? 'selected' : '';
                $benchA3 = $benches == 'A3' ? 'selected' : '';
                $benchA4 = $benches == 'A4' ? 'selected' : '';
                $benchA5 = $benches == 'A5' ? 'selected' : '';
                $benchA6 = $benches == 'A6' ? 'selected' : '';
                $benchA7 = $benches == 'A7' ? 'selected' : '';
                $benchA8 = $benches == 'A8' ? 'selected' : '';
                $benchA9 = $benches == 'A9' ? 'selected' : '';
                $benchA10 = $benches == 'A10' ? 'selected' : '';
                $benchA11 = $benches == 'A11' ? 'selected' : '';
                $benchA12 = $benches == 'A12' ? 'selected' : '';
                $benchA13 = $benches == 'A13' ? 'selected' : '';
                $benchA14 = $benches == 'A14' ? 'selected' : '';
                $benchA15 = $benches == 'A15' ? 'selected' : '';
                $benchA16 = $benches == 'A16' ? 'selected' : '';
                $benchA17 = $benches == 'A17' ? 'selected' : '';

                $benchB1 = $benches == 'B1' ? 'selected' : '';
                $benchB2 = $benches == 'B2' ? 'selected' : '';
                $benchB3 = $benches == 'B3' ? 'selected' : '';
                $benchB4 = $benches == 'B4' ? 'selected' : '';
                $benchB5 = $benches == 'B5' ? 'selected' : '';
                $benchB6 = $benches == 'B6' ? 'selected' : '';
                $benchB7 = $benches == 'B7' ? 'selected' : '';
                $benchB8 = $benches == 'B8' ? 'selected' : '';
                $benchB9 = $benches == 'B9' ? 'selected' : '';
                $benchB10 = $benches == 'B10' ? 'selected' : '';
                $benchB11 = $benches == 'B11' ? 'selected' : '';
                $benchB12 = $benches == 'B12' ? 'selected' : '';
                $benchB13 = $benches == 'B13' ? 'selected' : '';
                $benchB14 = $benches == 'B14' ? 'selected' : '';
                $benchB15 = $benches == 'B15' ? 'selected' : '';
                $benchB16 = $benches == 'B16' ? 'selected' : '';
                $benchB17 = $benches == 'B17' ? 'selected' : '';

                $benchC1 = $benches == 'C1' ? 'selected' : '';
                $benchC2 = $benches == 'C2' ? 'selected' : '';
                $benchC3 = $benches == 'C3' ? 'selected' : '';
                $benchC4 = $benches == 'C4' ? 'selected' : '';
                $benchC5 = $benches == 'C5' ? 'selected' : '';
                $benchC6 = $benches == 'C6' ? 'selected' : '';
                $benchC7 = $benches == 'C7' ? 'selected' : '';
                $benchC8 = $benches == 'C8' ? 'selected' : '';
                $benchC9 = $benches == 'C9' ? 'selected' : '';
                $benchC10 = $benches == 'C10' ? 'selected' : '';
                $benchC11 = $benches == 'C11' ? 'selected' : '';
                $benchC12 = $benches == 'C12' ? 'selected' : '';
                $benchC13 = $benches == 'C13' ? 'selected' : '';
                $benchC14 = $benches == 'C14' ? 'selected' : '';
                $benchC15 = $benches == 'C15' ? 'selected' : '';
                $benchC16 = $benches == 'C16' ? 'selected' : '';
                $benchC17 = $benches == 'C17' ? 'selected' : '';

                $benchD1 = $benches == 'D1' ? 'selected' : '';
                $benchD2 = $benches == 'D2' ? 'selected' : '';
                $benchD3 = $benches == 'D3' ? 'selected' : '';
                $benchD4 = $benches == 'D4' ? 'selected' : '';
                $benchD5 = $benches == 'D5' ? 'selected' : '';
                $benchD6 = $benches == 'D6' ? 'selected' : '';
                $benchD7 = $benches == 'D7' ? 'selected' : '';
                $benchD8 = $benches == 'D8' ? 'selected' : '';
                $benchD9 = $benches == 'D9' ? 'selected' : '';
                $benchD10 = $benches == 'D10' ? 'selected' : '';
                $benchD11 = $benches == 'D11' ? 'selected' : '';
                $benchD12 = $benches == 'D12' ? 'selected' : '';
                $benchD13 = $benches == 'D13' ? 'selected' : '';
                $benchD14 = $benches == 'D14' ? 'selected' : '';
                $benchD15 = $benches == 'D15' ? 'selected' : '';
                $benchD16 = $benches == 'D16' ? 'selected' : '';
                $benchD17 = $benches == 'D17' ? 'selected' : '';

                $benchE1 = $benches == 'E1' ? 'selected' : '';
                $benchE2 = $benches == 'E2' ? 'selected' : '';
                $benchE3 = $benches == 'E3' ? 'selected' : '';
                $benchE4 = $benches == 'E4' ? 'selected' : '';
                $benchE5 = $benches == 'E5' ? 'selected' : '';
                $benchE6 = $benches == 'E6' ? 'selected' : '';
                $benchE7 = $benches == 'E7' ? 'selected' : '';
                $benchE8 = $benches == 'E8' ? 'selected' : '';
                $benchE9 = $benches == 'E9' ? 'selected' : '';
                $benchE10 = $benches == 'E10' ? 'selected' : '';
                $benchE11 = $benches == 'E11' ? 'selected' : '';
                $benchE12 = $benches == 'E12' ? 'selected' : '';
                $benchE13 = $benches == 'E13' ? 'selected' : '';
                $benchE14 = $benches == 'E14' ? 'selected' : '';
                $benchE15 = $benches == 'E15' ? 'selected' : '';
                $benchE16 = $benches == 'E16' ? 'selected' : '';
                $benchE17 = $benches == 'E17' ? 'selected' : '';

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
                    <option $benchA1 value='A1'>A1</option>
                    <option $benchA2 value='A2'>A2</option>
                    <option $benchA3 value='A3'>A3</option>
                    <option $benchA4 value='A4'>A4</option>
                    <option $benchA5 value='A5'>A5</option>
                    <option $benchA6 value='A6'>A6</option>
                    <option $benchA7 value='A7'>A7</option>
                    <option $benchA8 value='A8'>A8</option>
                    <option $benchA9 value='A9'>A9</option>
                    <option $benchA10 value='A10'>A10</option>
                    <option $benchA11 value='A11'>A11</option>
                    <option $benchA12 value='A12'>A12</option>
                    <option $benchA13 value='A13'>A13</option>
                    <option $benchA14 value='A14'>A14</option>
                    <option $benchA15 value='A15'>A15</option>
                    <option $benchA16 value='A16'>A16</option>
                    <option $benchA17 value='A17'>A17</option>
                    <option $benchB1 value='B1'>B1</option>
                    <option $benchB2 value='B2'>B2</option>
                    <option $benchB3 value='B3'>B3</option>
                    <option $benchB4 value='B4'>B4</option>
                    <option $benchB5 value='B5'>B5</option>
                    <option $benchB6 value='B6'>B6</option>
                    <option $benchB7 value='B7'>B7</option>
                    <option $benchB8 value='B8'>B8</option>
                    <option $benchB9 value='B9'>B9</option>
                    <option $benchB10 value='B10'>B10</option>
                    <option $benchB11 value='B11'>B11</option>
                    <option $benchB12 value='B12'>B12</option>
                    <option $benchB13 value='B13'>B13</option>
                    <option $benchB14 value='B14'>B14</option>
                    <option $benchB15 value='B15'>B15</option>
                    <option $benchB16 value='B16'>B16</option>
                    <option $benchB17 value='B17'>B17</option>
                    <option $benchC1 value='C1'>C1</option>
                    <option $benchC2 value='C2'>C2</option>
                    <option $benchC3 value='C3'>C3</option>
                    <option $benchC4 value='C4'>C4</option>
                    <option $benchC5 value='C5'>C5</option>
                    <option $benchC6 value='C6'>C6</option>
                    <option $benchC7 value='C7'>C7</option>
                    <option $benchC8 value='C8'>C8</option>
                    <option $benchC9 value='C9'>C9</option>
                    <option $benchC10 value='C10'>C10</option>
                    <option $benchC11 value='C11'>C11</option>
                    <option $benchC12 value='C12'>C12</option>
                    <option $benchC13 value='C13'>C13</option>
                    <option $benchC14 value='C14'>C14</option>
                    <option $benchC15 value='C15'>C15</option>
                    <option $benchC16 value='C16'>C16</option>
                    <option $benchC17 value='C17'>C17</option>
                    <option $benchD1 value='D1'>D1</option>
                    <option $benchD2 value='D2'>D2</option>
                    <option $benchD3 value='D3'>D3</option>
                    <option $benchD4 value='D4'>D4</option>
                    <option $benchD5 value='D5'>D5</option>
                    <option $benchD6 value='D6'>D6</option>
                    <option $benchD7 value='D7'>D7</option>
                    <option $benchD8 value='D8'>D8</option>
                    <option $benchD9 value='D9'>D9</option>
                    <option $benchD10 value='D10'>D10</option>
                    <option $benchD11 value='D11'>D11</option>
                    <option $benchD12 value='D12'>D12</option>
                    <option $benchD13 value='D13'>D13</option>
                    <option $benchD14 value='D14'>D14</option>
                    <option $benchD15 value='D15'>D15</option>
                    <option $benchD16 value='D16'>D16</option>
                    <option $benchD17 value='D17'>D17</option>
                    <option $benchE1 value='E1'>E1</option>
                    <option $benchE2 value='E2'>E2</option>
                    <option $benchE3 value='E3'>E3</option>
                    <option $benchE4 value='E4'>E4</option>
                    <option $benchE5 value='E5'>E5</option>
                    <option $benchE6 value='E6'>E6</option>
                    <option $benchE7 value='E7'>E7</option>
                    <option $benchE8 value='E8'>E8</option>
                    <option $benchE9 value='E9'>E9</option>
                    <option $benchE10 value='E10'>E10</option>
                    <option $benchE11 value='E11'>E11</option>
                    <option $benchE12 value='E12'>E12</option>
                    <option $benchE13 value='E13'>E13</option>
                    <option $benchE14 value='E14'>E14</option>
                    <option $benchE15 value='E15'>E15</option>
                    <option $benchE16 value='E16'>E16</option>
                    <option $benchE17 value='E17'>E17</option>
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
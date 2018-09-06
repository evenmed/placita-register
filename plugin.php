<?php
/*
Plugin Name: La Placita Register
Plugin URI: http://laplacita.church/
Description: Plugin to create and handle the Baptism Pre-Register form
Version: 3.3
Author: Emilio Venegas
Author URI: http://www.emiliovenegas.me
License: GPL2
*/

global $placita_db_version, $bench_numbers;
$placita_db_version = '1.6.11';
$bench_numbers = array('A1','A2','A3','A4','A5','A6','A7','A8','A9','A10','A11','A12','A13','A14','A15','A16','A17','B1','B2','B3','B4','B5','B6','B7','B8','B9','B10','B11','B12','B13','B14','B15','B16','B17','C1','C2','C3','C4','C5','C6','C7','C8','C9','C10','C11','C12','C13','C14','C15','C16','C17','D1','D2','D3','D4','D5','D6','D7','D8','D9','D10','D11','D12','D13','D14','D15','D16','D17','E1','E2','E3','E4','E5','E6','E7','E8','E9','E10','E11','E12','E13','E14','E15','E16','E17');

// Create or update db
function placita_install_db() {
    global $placita_db_version;
    
    $installed_ver = get_option( "placita_db_version" );
    
    if ( $installed_ver != $placita_db_version ) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'baptism_registers';
        
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            first_name varchar(255) NOT NULL,
            middle_name varchar(255) NOT NULL,
            last_name varchar(255) NOT NULL,
            gender enum('male', 'female') DEFAULT 'male' NOT NULL,
            birthdate date NOT NULL,
            birthplace varchar(255) NOT NULL,
            parents_married tinyint(1) DEFAULT '0' NOT NULL,
            parents_married_church tinyint(1) DEFAULT '0' NOT NULL,
            contact_email varchar(255) NOT NULL,
            address varchar(255) NOT NULL,
            city varchar(255) NOT NULL,
            state varchar(255) NOT NULL,
            zip varchar(255) NOT NULL,
            father_name varchar(255) NOT NULL,
            father_middle varchar(255) NOT NULL,
            father_last varchar(255) NOT NULL,
            father_email varchar(255) NOT NULL,
            father_phone varchar(255) NOT NULL,
            father_catholic tinyint(1) DEFAULT '0' NOT NULL,
            father_id tinyint(1) DEFAULT '0' NOT NULL,
            mother_name varchar(255) NOT NULL,
            mother_middle varchar(255) NOT NULL,
            mother_last varchar(255) NOT NULL,
            mother_email varchar(255) NOT NULL,
            mother_phone varchar(255) NOT NULL,
            mother_catholic tinyint(1) DEFAULT '0' NOT NULL,
            mother_id tinyint(1) DEFAULT '0' NOT NULL,
            mother_married_name varchar(255) NOT NULL,
            mmn_birth_certificate tinyint(1) DEFAULT '0' NOT NULL,
            godfather_name varchar(255) NOT NULL,
            godfather_middle varchar(255) NOT NULL,
            godfather_last varchar(255) NOT NULL,
            godfather_email varchar(255) NOT NULL,
            godfather_phone varchar(255) NOT NULL,
            godfather_catholic tinyint(1) DEFAULT '0' NOT NULL,
            godmother_name varchar(255) NOT NULL,
            godmother_middle varchar(255) NOT NULL,
            godmother_last varchar(255) NOT NULL,
            godmother_email varchar(255) NOT NULL,
            godmother_phone varchar(255) NOT NULL,
            godmother_catholic tinyint(1) DEFAULT '0' NOT NULL,
            note text NULL,
            bautismal_code varchar(255) NULL,
            benches enum('A1','B1','C1','D1','E1','A2','B2','C2','D2','E2','A3','B3','C3','D3','E3','A4','B4','C4','D4','E4','A5','B5','C5','D5','E5','A6','B6','C6','D6','E6','A7','B7','C7','D7','E7','A8','B8','C8','D8','E8','A9','B9','C9','D9','E9','A10','B10','C10','D10','E10','A11','B11','C11','D11','E11','A12','B12','C12','D12','E12','A13','B13','C13','D13','E13','A14','B14','C14','D14','E14','A15','B15','C15','D15','E15','A16','B16','C16','D16','E16','A17','B17','C17','D17','E17') NULL,
            priest varchar(255) NULL,
            file varchar(255) NULL,
            amount_collected decimal(13,2) DEFAULT '0.00' NOT NULL,
            baptism_date datetime NULL,
            is_canceled tinyint(1) DEFAULT 0 NOT NULL,
            is_noshow tinyint(1) DEFAULT 0 NOT NULL,
            is_private tinyint(1) DEFAULT 0 NOT NULL,
            date timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
            lastedited timestamp ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

        update_option( 'placita_db_version', $placita_db_version );
        
    }
}

// Plugin activation scripts
function placita_activation() {
    placita_install_db();

    add_role(
        'baptism_registry_manager',
        __('Baptism Registry Manager', 'laplacita'),
        array(
            'read' => true,
            'manage_baptism' => true
        )
    );
}
register_activation_hook( __FILE__, 'placita_activation' );

function wporg_simple_role_caps()
{
    // gets the baptism_registry_manager role object
    $role = get_role('baptism_registry_manager');
    if ($role)
        $role->add_cap('manage_baptism', true);

    // gets the baptism_registry_manager role object
    $role = get_role('administrator');
    if ($role)
        $role->add_cap('manage_baptism', true);
}
 
// add simple_role capabilities, priority must be after the initial role definition
add_action('init', 'wporg_simple_role_caps', 11);

// Baptism Registry Manager limitations
function plaita_baptism_manager_hide_the_dashboard() {
    global $current_user;
    // is there a user ?
    if ( is_array( $current_user->roles ) ) {
        // substitute your role(s):
        if ( in_array( 'baptism_registry_manager', $current_user->roles ) ) {
            // hide the dashboard:
            remove_menu_page( 'index.php' );
        }
    }
}
add_action( 'admin_menu', 'plaita_baptism_manager_hide_the_dashboard' );

function placita_baptism_manager_login_redirect( $redirect_to, $request, $user ){
    // is there a user ?
    if ( is_array( $user->roles ) ) {
        // substitute your role(s):
        if ( in_array( 'baptism_registry_manager', $user->roles ) ) {
            // pick where to redirect to, in the example: Posts page
            return admin_url( 'admin.php?page=baptism_registers' );
        } else {
            return admin_url();
        }
    }
}
add_filter( 'login_redirect', 'placita_baptism_manager_login_redirect', 10, 3 );

// Update db if necessary
function placita_update_db_check() {
    global $placita_db_version;
    if ( get_site_option( 'placita_db_version' ) != $placita_db_version ) {
        placita_install_db();
    }
}
add_action( 'plugins_loaded', 'placita_update_db_check' );

require "class.templater.php";
add_action( 'plugins_loaded', array( 'PageTemplater', 'get_instance' ) );

function placita_scripts() {
    if ( is_page_template( 'baptism-register.php' ) ) {
        wp_enqueue_style( 'roboto-font', 'https://fonts.googleapis.com/css?family=Roboto+Slab:100,300,400,700' );
        wp_enqueue_style( 'bootstrap', plugin_dir_url( __FILE__ ) . 'vendor/bootstrap/css/bootstrap.min.css' );
        wp_enqueue_style( 'chosen', plugin_dir_url( __FILE__ ) . 'vendor/chosen/chosen.css' );
        wp_enqueue_style( 'jquery-ui-css', plugin_dir_url( __FILE__ ) . 'vendor/jquery-ui/jquery-ui.min.css' );
        wp_enqueue_style( 'page-template', plugin_dir_url( __FILE__ ) . 'css/style.css' );

        wp_register_script('jquery-ui', plugin_dir_url( __FILE__ ) . 'vendor/jquery-ui/jquery-ui.min.js', array('jquery'),'', true);
        wp_enqueue_script('jquery-ui');

        wp_register_script('bootstrap', plugin_dir_url( __FILE__ ) . 'vendor/bootstrap/js/bootstrap.min.js', array('jquery'),'', true);
        wp_enqueue_script('bootstrap');

        wp_register_script('chosen', plugin_dir_url( __FILE__ ) . 'vendor/chosen/chosen.jquery.js', array('jquery'),'', true);
        wp_enqueue_script('chosen');

        wp_register_script('multisptep-form', plugin_dir_url( __FILE__ ) . 'js/multistep-form.js', array('jquery', 'jquery-ui'),'', true);
        wp_enqueue_script('multisptep-form');

        wp_register_script('placita-scripts', plugin_dir_url( __FILE__ ) . 'js/scripts.js', array('jquery', 'jquery-ui'),'', true);
        wp_enqueue_script('placita-scripts');
    }
}
add_action( 'wp_enqueue_scripts', 'placita_scripts', 20 );

function placita_admin_scripts() {

    // Datetimepicker scripts
    wp_enqueue_script( 
        'datetimepicker', 
        plugin_dir_url( __FILE__ ) . 'vendor/datetimepicker/jquery.datetimepicker.full.min.js',
        array('jquery')
    );
    wp_localize_script(
        'datetimepicker',
        'server_data',
        [
            'update_registry_nonce' => 
            wp_create_nonce('placita_update_registry_nonce'),
            'loading_spinner_url' =>
            plugin_dir_url( __FILE__ ) . 'media/images/loading_spinner.gif'
        ]
    );

    // Datetimepicker styles
    wp_enqueue_style( 'datetimepicker', plugin_dir_url( __FILE__ ) . 'vendor/datetimepicker/jquery.datetimepicker.min.css' );

    // Our custom styles
    wp_enqueue_style( 'placita_admin_styles', plugin_dir_url( __FILE__ ) . 'css/admin.css' );

    // Our custom scripts
    wp_enqueue_script( 
        'placita_admin_scripts', 
        plugin_dir_url( __FILE__ ) . 'js/admin.js',
        array('datetimepicker'),
        '2.1'
    );

}
add_action( 'admin_enqueue_scripts', 'placita_admin_scripts', 10 );

function register_page(){

    $childid = $_GET['child-id'] ? $_GET['child-id'] : false;
    $child = get_child($childid);

    require_once("views/register.php");
}

function placita_handle_baptism_register_form() {

    $checkbox = function ($cb) { return $cb ? 'Yes' : 'No'; };

    //Sanitize everything and but it into our $values array
    $values = array();

    $values['first_name'] = isset($_POST['first-name']) ? 
        sanitize_text_field( $_POST['first-name'] ) : "";
    $values['middle_name'] = isset($_POST['middle-name']) ? 
        sanitize_text_field( $_POST['middle-name'] ) : "";
    $values['last_name'] = isset($_POST['last-name']) ? 
        sanitize_text_field( $_POST['last-name'] ) : "";
    $values['gender'] = isset($_POST['gender']) ? 
        sanitize_text_field( $_POST['gender'] ) : "";
    $values['birthdate'] = ( isset($_POST['birthdate']) && $_POST['birthdate'] != "" ) ? 
        date( "Y-m-d", strtotime( sanitize_text_field( $_POST['birthdate'] ) ) ) : null;
    $values['birthplace'] = isset($_POST['birthplace']) ? 
        sanitize_text_field( $_POST['birthplace'] ) : "";

    $values['parents_married'] = isset($_POST['parents-married']) ? 
        1 : 0;
    $values['parents_married_church'] = isset($_POST['parents-married-church']) ? 
        1 : 0;
    $values['contact_email'] = isset($_POST['contact-email']) ? 
        sanitize_email( $_POST['contact-email'] ) : "";
    $values['address'] = isset($_POST['address']) ? 
        sanitize_text_field( $_POST['address'] ) : "";
    $values['city'] = isset($_POST['city']) ? 
        sanitize_text_field( $_POST['city'] ) : "";
    $values['state'] = isset($_POST['state']) ? 
        sanitize_text_field( $_POST['state'] ) : "";
    $values['zip'] = isset($_POST['zip']) ? 
        sanitize_text_field( $_POST['zip'] ) : "";

    $values['father_name'] = isset($_POST['father-first-name']) ? 
        sanitize_text_field( $_POST['father-first-name'] ) : "";
    $values['father_middle'] = isset($_POST['father-middle-name']) ? 
        sanitize_text_field( $_POST['father-middle-name'] ) : "";
    $values['father_last'] = isset($_POST['father-last-name']) ? 
        sanitize_text_field( $_POST['father-last-name'] ) : "";
    $values['father_email'] = isset($_POST['father-email']) ? 
        sanitize_email( $_POST['father-email'] ) : "";
    $values['father_phone'] = isset($_POST['father-phone']) ? 
        sanitize_text_field( $_POST['father-phone'] ) : "";
    $values['father_catholic'] = isset($_POST['father-catholic']) ? 
        1 : 0;
    $values['father_id'] = isset($_POST['father-id']) ? 
        1 : 0;

    $values['mother_name'] = isset($_POST['mother-first-name']) ? 
        sanitize_text_field( $_POST['mother-first-name'] ) : "";
    $values['mother_middle'] = isset($_POST['mother-middle-name']) ? 
        sanitize_text_field( $_POST['mother-middle-name'] ) : "";
    $values['mother_last'] = isset($_POST['mother-last-name']) ? 
        sanitize_text_field( $_POST['mother-last-name'] ) : "";
    $values['mother_email'] = isset($_POST['mother-email']) ? 
        sanitize_email( $_POST['mother-email'] ) : "";
    $values['mother_phone'] = isset($_POST['mother-phone']) ? 
        sanitize_text_field( $_POST['mother-phone'] ) : "";
    $values['mother_catholic'] = isset($_POST['mother-catholic']) ? 
        1 : 0;
    $values['mother_id'] = isset($_POST['mother-id']) ? 
        1 : 0;
    $values['mother_married_name'] = isset($_POST['mother-married-name']) ? 
        sanitize_text_field( $_POST['mother-married-name'] ) : "";
    $values['mmn_birth_certificate'] = isset($_POST['mother-birth-certificate']) ? 
        1 : 0;

    $values['godfather_name'] = isset($_POST['godfather-first-name']) ? 
        sanitize_text_field( $_POST['godfather-first-name'] ) : "";
    $values['godfather_middle'] = isset($_POST['godfather-middle-name']) ? 
        sanitize_text_field( $_POST['godfather-middle-name'] ) : "";
    $values['godfather_last'] = isset($_POST['godfather-last-name']) ? 
        sanitize_text_field( $_POST['godfather-last-name'] ) : "";
    $values['godfather_email'] = isset($_POST['godfather-email']) ? 
        sanitize_email( $_POST['godfather-email'] ) : "";
    $values['godfather_phone'] = isset($_POST['godfather-phone']) ? 
        sanitize_text_field( $_POST['godfather-phone'] ) : "";
    $values['godfather_catholic'] = isset($_POST['godfather-catholic']) ? 
        1 : 0;

    $values['godmother_name'] = isset($_POST['godmother-first-name']) ? 
        sanitize_text_field( $_POST['godmother-first-name'] ) : "";
    $values['godmother_middle'] = isset($_POST['godmother-middle-name']) ? 
        sanitize_text_field( $_POST['godmother-middle-name'] ) : "";
    $values['godmother_last'] = isset($_POST['godmother-last-name']) ? 
        sanitize_text_field( $_POST['godmother-last-name'] ) : "";
    $values['godmother_email'] = isset($_POST['godmother-email']) ? 
        sanitize_email( $_POST['godmother-email'] ) : "";
    $values['godmother_phone'] = isset($_POST['godmother-phone']) ? 
        sanitize_text_field( $_POST['godmother-phone'] ) : "";
    $values['godmother_catholic'] = isset($_POST['godmother-catholic']) ? 
        1 : 0;

    $values['note'] = isset($_POST['note']) ? 
        sanitize_text_field( $_POST['note'] ) : "";
    $values['bautismal_code'] = isset($_POST['bautismal-code']) ? 
        sanitize_text_field( $_POST['bautismal-code'] ) : "";


    // PDF structure
    $html = <<<PDF
    <h1>La Placita Baptism Pre-Register</h1>

    <h2 style="margin-bottom:0;">Child's Info</h2>
    <hr />
    <section style="width:50%; float:left;">
    <div><strong>First Name:</strong> {$values['first_name']}</div>
    <div><strong>Middle Name:</strong> {$values['middle_name']}</div>
    <div><strong>Last Name:</strong> {$values['last_name']}</div>
    </section>
    <section style="width:50%; float:left;">
    <div><strong>Sex:</strong> {$values['gender']}</div>
    <div><strong>Birthdate:</strong> {$values['birthdate']}</div>
    <div><strong>Birthplace:</strong> {$values['birthplace']}</div>
    </section>


    <h2 style="margin-bottom:0;">Parent's Info</h2>
    <hr />
    <section style="width:50%; float:left;">
    <div><strong>Married:</strong> {$checkbox($values['parents_married'])}</div>
    <div><strong>Married in Church:</strong> {$checkbox($values['parents_married_church'])}</div>
    <div><strong>Street Address:</strong> {$values['address']}</div>
    <div><strong>Contact Email:</strong> {$values['contact_email']}</div>
    </section>
    <section style="width:50%; float:left;">
    <div><strong>City:</strong> {$values['city']}</div>
    <div><strong>State:</strong> {$values['state']}</div>
    <div><strong>Zip Code:</strong> {$values['zip']}</div>
    </section>

    <section style="width:50%; float:left;">
    <h3>Father</h3>
    <div><strong>First Name:</strong> {$values['father_name']}</div>
    <div><strong>Middle Name:</strong> {$values['father_middle']}</div>
    <div><strong>Last Name:</strong> {$values['father_last']}</div>
    <div><strong>Email:</strong> {$values['father_email']}</div>
    <div><strong>Phone:</strong> {$values['father_phone']}</div>
    <div><strong>Catholic:</strong> {$checkbox($values['father_catholic'])}</div>
    <div><strong>ID:</strong> {$checkbox($values['father_id'])}</div>
    </section>

    <section style="width:50%; float:left;">
    <h3>Mother</h3>
    <div><strong>First Name:</strong> {$values['mother_name']}</div>
    <div><strong>Middle Name:</strong> {$values['mother_middle']}</div>
    <div><strong>Last Name:</strong> {$values['mother_last']}</div>
    <div><strong>Email:</strong> {$values['mother_email']}</div>
    <div><strong>Phone:</strong> {$values['mother_phone']}</div>
    <div><strong>Catholic:</strong> {$checkbox($values['mother_catholic'])}</div>
    <div><strong>ID:</strong> {$checkbox($values['mother_id'])}</div>
    <div><strong>Married Last Name:</strong> {$values['mother_married_name']}</div>
    <div><strong>Birth Certificate:</strong> {$checkbox($values['mmn_birth_certificate'])}</div>
    </section>


    <h2 style="margin-bottom:0;">Godparent's Info</h2>
    <hr />

    <section style="width:50%; float:left;">
    <h3>Godfather</h3>
    <div><strong>First Name:</strong> {$values['godfather_name']}</div>
    <div><strong>Middle Name:</strong> {$values['godfather_middle']}</div>
    <div><strong>Last Name:</strong> {$values['godfather_last']}</div>
    <div><strong>Email:</strong> {$values['godfather_email']}</div>
    <div><strong>Phone:</strong> {$values['godfather_phone']}</div>
    <div><strong>Catholic:</strong> {$checkbox($values['godfather_catholic'])}</div>
    </section>

    <section style="width:50%; float:left;">
    <h3>Godmother</h3>
    <div><strong>First Name:</strong> {$values['godmother_name']}</div>
    <div><strong>Middle Name:</strong> {$values['godmother_middle']}</div>
    <div><strong>Last Name:</strong> {$values['godmother_last']}</div>
    <div><strong>Email:</strong> {$values['godmother_email']}</div>
    <div><strong>Phone:</strong> {$values['godmother_phone']}</div>
    <div><strong>Catholic:</strong> {$checkbox($values['godmother_catholic'])}</div>
    </section>

    <br/>
    <br/>

    <h3>Notes</h3>
    <div>{$values['note']}</div>

PDF;
  
    $time = time();

    // PDF title
    $title = "Baptism_Preregister_{$values['first_name']}_{$values['last_name']}_{$time}.pdf";
    
    // Remove anything which isn't a word, whitespace, number
    // or any of the following caracters -_~,;:[]().
    // If you don't need to handle multi-byte characters
    // Thanks @Łukasz Rysiak!
    $file = preg_replace("([^\w\s\d\-_~,;:\[\]\(\).])", '', $title);
    // Remove any runs of periods (thanks falstro!)
    $file = preg_replace("([\.]{2,})", '', $title);
    
    $file = plugin_dir_path(__FILE__) . 'pdfs/' . $title;

    // Require composer autoload
    require_once plugin_dir_path(__FILE__) . 'vendor/mPDF/vendor/autoload.php';
    $mpdf = new mPDF('', 'Letter');
    $mpdf->SetTitle( $title );
    $mpdf->WriteHTML($html);
    //   $content = $mpdf->Output( $title, 'I' );
    $content = $mpdf->Output( $file, 'F' );

    if ( file_exists( $file ) ) {
        wp_mail(array("baptism@laplacitachurch.org"), "La Placita Baptism Pre-register", "Attached you will find the PDF file with the pre-register info.", array(), $file);
    }

    $values['file'] = $title;

    // Save evrything to the db
    global $wpdb;

    $wpdb->insert( 
        $wpdb->prefix . 'baptism_registers', 
        $values
    );

    wp_redirect("http://laplacita.org/es/gracias-aplicacion-bautizos"); // Thank you page

    exit;
}
add_action( 'admin_post_nopriv_baptism_register_form', 'placita_handle_baptism_register_form' );
add_action( 'admin_post_baptism_register_form', 'placita_handle_baptism_register_form' );

// AJAX action to update registry fields
add_action( 'wp_ajax_update_registry', 'placita_update_registry' );
function placita_update_registry() {
    // Verify nonce and data
    if (
        wp_verify_nonce( $_REQUEST['_wpnonce'], 'placita_update_registry_nonce' ) !== 1 ||
        ! in_array( $_REQUEST['field'], 
            array(
                'priest',
                'amount_collected',
                'baptism_date',
                'benches',
                'birthdate',
                'is_canceled',
                'is_noshow',
                'is_private',
            ) ) ||
        ! $registry = intval($_REQUEST['registry'])
    ) {
        wp_send_json( array(
            'success' => 0,
            'message' => __("An error ocurred, please refresh the page and try again", 'laplacita')
         ) );
    }

    // Format the value depending on the field to be updated
    $v = $_REQUEST['value'];
    $field = $_REQUEST['field'];
    switch($field) {
        case 'priest':
            $value = sanitize_text_field( trim($v) );
            $update = array( $field => $value );
            break;

        case 'amount_collected':
            $value = number_format((float)$v, 2, '.', '');
            $update = array( $field => $value );
            break;

        case 'baptism_date':
            $date = date_create_from_format('Y/m/d H:i', $v);
            $value = $date->format("Y-m-d H:i:s");
            if ( !$value ) {
                wp_send_json( array(
                    'success' => 0,
                    'message' => __("Please enter the date in a valid format", 'laplacita')
                 ) );
            }
            $update = array(
                $field => $value,
                'benches' => 'NULL'
            );
            break;

        case 'birthdate':
            $date = date_create_from_format('Y/m/d', $v);
            $value = $date->format("Y-m-d");
            if ( !$value ) {
                wp_send_json( array(
                    'success' => 0,
                    'message' => __("Please enter the date in a valid format", 'laplacita')
                 ) );
            }
            $update = array( $field => $value );
            break;

        case 'benches':
            global $bench_numbers;
            if (
                in_array( 
                    $v, 
                    $bench_numbers
                )
            ) {
                $maybe_current_bench = placita_is_bench_available($registry, $v);
                if ( ! $maybe_current_bench ) {
                    wp_send_json( array(
                        'success' => 0,
                        'message' => __("That bench is not available at that time", 'laplacita')
                     ) );
                }
                $previous_bench = in_array($maybe_current_bench, $bench_numbers) ? $maybe_current_bench : false;
                $value = $v;
            } else {
                wp_send_json( array(
                    'success' => 0,
                    'message' => __("Please choose a valid bench number", 'laplacita')
                 ) );
            }
            $update = array( $field => $value );
            break;

        case 'is_canceled':
        case 'is_noshow':
        case 'is_private':
            $value = intval($v) === 1 ? 1 : 0;
            $update = array( $field => $value );
            break;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'baptism_registers';
    if ($wpdb->update(
        $table_name, 
        $update, 
        array( 'ID' => $registry ), 
        array( '%s' ), 
        array( '%d' ) 
    )) {
        // Get the value from the db to display it exactly as it was saved
        $results = $wpdb->get_results(
            sprintf(
                "SELECT $field
                FROM %s
                WHERE id = $registry
                LIMIT 1",
                $table_name
            ),
            ARRAY_A
        );
        $dbVal = $results[0][$field];
        $extra_fields = array();
        switch ($field) {
            case 'baptism_date':
                $date = date_create_from_format('Y-m-d H:i:s', $dbVal);
                $value = $date ? $date->format('Y/m/d H:i') : '';

                // Get the benches that are already occupied at the baptism's datetime
                global $bench_numbers, $wpdb;

                $table_name = $wpdb->prefix . 'baptism_registers';
                $unavailable_benches = array();

                $results = $wpdb->get_results(
                    sprintf(
                        "SELECT benches
                        FROM %s
                        WHERE baptism_date = '$dbVal'
                        AND id != $registry
                        AND is_canceled = 0",
                        $table_name
                    ),
                    ARRAY_A
                );
                if ( count($results) > 0 ) {
                    foreach ( $results as $r ) {
                        $unavailable_benches[] = $r['benches'];
                    }
                }
                if ( count($unavailable_benches) > 0 )
                    $extra_fields = array( 'unavailable_benches' => $unavailable_benches );
                break;
            case 'birthdate':
                $date = date_create_from_format('Y-m-d', $dbVal);
                $value = $date ? $date->format('Y/m/d') : '';
                break;
            case 'benches':
                if ($previous_bench)
                    $extra_fields = array( 'previous_bench' => $maybe_current_bench );
                break;
            case 'is_canceled':
            case 'is_noshow':
            case 'is_private':
                $value = intval($v) === 1 ? 'Yes' : 'No';
                break;
            default:
                $value = stripslashes($dbVal);
                break;
        }
        wp_send_json( array(
            'success' => 1,
            'message' => __("Saved!", 'laplacita'),
            'value'   => $value,
            'dbVal'   => $dbVal,
         ) + $extra_fields );
    }

    wp_send_json( array(
        'success' => 0,
        'message' => __("An error ocurred while updating the registry. Please refresh the page and try again.", 'laplacita'),
        'value'   => $value
    ) );
}

/**
 * Check if the bench is available for the registry based on baptism date
 * 
 * If the registry doesn't have a baptism date set, it'll always return true
 * 
 * @param int    $id    the ID of the baptism registry
 * @param string $bench the bench number to check availability for
 * @return bool/string  string indicating the previously selected bench 
 * if the bench is available, false if it isn't
 */
function placita_is_bench_available( $id, $bench ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'baptism_registers';

    // Get the baptism date and current benches of the registry
    $results = $wpdb->get_results(
        sprintf(
            "SELECT baptism_date, benches
            FROM %s
            WHERE id = $id
            LIMIT 1",
            $table_name
        ),
        ARRAY_A
    );

    if ( 
        count($results) < 1 ||
        ! isset( $results[0]['baptism_date'] ) ||
        ! $results[0]['baptism_date']
    )
        return true; // Registry doens't exist or doesn't have a date set

    $datetime = $results[0]['baptism_date'];
    $bench_results = $wpdb->get_results(
        sprintf(
            "SELECT id
            FROM %s
            WHERE benches = '$bench'
            AND baptism_date = '$datetime'
            AND is_canceled = 0
            AND id != $id
            LIMIT 1",
            $table_name
        ),
        ARRAY_A
    );

    if ( count($bench_results) > 0 )
        return false; // That bench is already assigned to a baptism at the same time

    return $results[0]['benches'] ? $results[0]['benches'] : true;
}

// Generate and show the PDF for a specific registry
function placita_baptism_register_view_pdf() {
    // First validate the nonce and user
    if ( 
        ! isset($_REQUEST['baptism_registry']) ||
        ! current_user_can('administrator') || 
        wp_verify_nonce( $_REQUEST['_wpnonce'], 'view_baptism_registry_pdf' ) !== 1
    ) {
        wp_die("Are you sure you want to do this?");
    }

    // Make sure the registry actually exists
    global $wpdb;
    $registry = intval($_REQUEST['baptism_registry']);
    $table_name = $wpdb->prefix . 'baptism_registers';
    $sql = sprintf(
        "SELECT file
        FROM %s
        WHERE id = $registry
        LIMIT 1",
        $table_name
    );

    $results = $wpdb->get_results( $sql , ARRAY_A );

    if ( count($results) == 0 ) {
        wp_die("The registry you're looking for doesn't exist");
    }

    // If everything's good, show the pdf
    $registry = $results[0];
    wp_redirect( plugin_dir_url(__FILE__) . 'pdfs/' . $registry['file'] );
    exit;

}
add_action( 'admin_post_baptism_register_view_pdf', 'placita_baptism_register_view_pdf' );

function language_switcher() {

    if ( !defined('LANGUAGE') ) return false;

    if (LANGUAGE == 'es') {
        $l_slug = "en";
        $l_name = "English";
    } else {
        $l_slug = "es";
        $l_name = "Español";
    }

    echo "<a title=$l_name class='language-switcher' href='?lang=$l_slug'><button class='btn '>$l_name</button></a>";
}

function validate_phone($phone) {
    $phone = preg_replace('/\D/', '', $phone); //strip non-numeric characters
    if ( strlen($phone) == 10 ) return true;
    else return false;
}

function parishes_select($name, $id, $classes = array(), $default = false, $placeholder = "Select the Parish") {
    global $db;
    $options = $db->select( "parishes", "*", ["ORDER"=>"name"] );

    $classes_string = "";
    foreach ($classes as $class) {
        $classes_string .= $class;
        $classes_string .= " ";
    }

    $output = "<select required name='$name' id='$id' class='$classes_string'>";
    $output .= "<option selected='selected' disabled value=''>$placeholder</option>";
    foreach ($options as $option) {
        $selected = "";
        if ($default == $option['id']) $selected = "selected";
        $output .= "<option value='". $option['id'] ."' ". $selected .">". $option['name'] ."</option>";
    }
    $output .= "</select>";

    echo $output;
}

function get_child($id = false) { // If id isn't provided, it'll return the most recently edited incomplete child of the current user
    return false;

    global $db, $current_user;
    $child = array();

    if ($id) {
        $child = $db->select( "childs", "*", ["id" => $id, "LIMIT" => 1] );
    } else {
        $child = $db->select( "childs", "*", ["registrar" => $current_user["id"], "complete" => 0, "LIMIT" => 1, "ORDER" => ['lastedited' => "DESC"]] );
    }

    if (empty($child)) return false;
    else return $child[0];

}

function is_registrar($child, $user) {
    global $db;
    $data = $db->select("childs", "id", ["id" => $child, "registrar" => $user, "LIMIT" => 1]);
    if (empty($data)) return false;
    else return true;
}

function user_has_incomplete_child($userid = false) { // Checks if the passed user (current user if empty) is halfway through finishng registring a child
    global $db;

    if ( !$userid ) {
        global $current_user;
        $userid = $current_user['id'];
    }

    if (!$userid) return false;

    $data = $db->select("childs", "id", ["registrar" => $userid, "complete" => 0, "LIMIT" => 1]);
    if ( !empty($data) && !$_SESSION['saved_for_later'] ) return true;
    else return false;

}


add_action( 'admin_menu', 'my_admin_menu' );
function my_admin_menu() {
    add_menu_page( 'Baptism Registers', 'Baptism Registers', 'manage_baptism', 'baptism_registers', 'baptism_registers_page', 'dashicons-admin-page', 6  );
    add_menu_page( 'Baptism Registers PDFs', 'Baptism Registers PDFs', 'manage_baptism', 'baptism_registers_pdfs', 'baptism_registers_pdfs_page', 'dashicons-admin-page', 7  );
}

function baptism_registers_page() {
  require_once('baptism-registers-table.class.php');
    
  //Create an instance of our package class...
  $testListTable = new Baptism_Registers_Table();
  //Fetch, prepare, sort, and filter our data...
  $testListTable->prepare_items();

  ?>
    <div class="wrap">

        <h2>Baptism Pre-registers</h2>
        <img width=200 src="<?php echo plugin_dir_url(__FILE__) . 'media/images/outline-logo-b.png' ?>" />

        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <form id="movies-filter" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <!-- Search Form -->
            <?php $testListTable->search_box('Search', 'search'); ?>
            <!-- Now we can render the completed list table -->
            <?php $testListTable->display() ?>
        </form>

        <form action="admin-post.php" target="_blank" id="registries_export" method="post">
            <h3>Export registries</h3>
            <input type="hidden" name="action" value="export_registries">
            <?php wp_nonce_field('placita_export_registries'); ?>
            <span>Date:</span><input type="text" class="registries_export_date" name="export_date">
            <button type="submit"class="button-primary">Export</button>
        </form>

    </div>
  <?php
}

// Export all registries for a given datetime as a PDF
add_action( 'admin_post_export_registries', 'placita_export_registries' );
function placita_export_registries() {
    // Verify nonce / admin referer
    check_admin_referer( 'placita_export_registries' );

    // Verifiy we have a date
    if ( !isset($_REQUEST['export_date']) ) wp_die('Please set a valid date');

    $export_date = $_REQUEST['export_date'];
    $date = date_create_from_format('Y/m/d H:i', $export_date);

    require_once plugin_dir_path(__FILE__) . '/vendor/mpdf/vendor/autoload.php';

    global $wpdb, $bench_numbers;

    $table_name = $wpdb->prefix . 'baptism_registers';

    $results = $wpdb->get_results(
        sprintf(
            "SELECT first_name, middle_name, last_name, benches
            FROM %s
            WHERE baptism_date = '$export_date'
            AND is_canceled = 0",
            $table_name
        ),
        ARRAY_A
    );

    $letters = array('E', 'D', 'C', 'B', 'A');

    $html = '<h3 style="text-align:center;">';
    $html .= $date->format("l, jS \of F Y");
    $html .= '<br/>';
    $html .= $date->format("\a\\t h:i A");
    $html .= '</h3>';
    $html .= '<table style="width: 100%; margin: 0 auto; font-size: 12px;" cellspacing=0>';

    $html .= '<thead>';
    $html .= '<tr>';
    foreach( $letters as $l ) {
        $html .= '<th style="border: 1px solid; height: 30px; font-size: 14px;" colspan="2">';
        $html .= $l;
        $html .= '</th>';
    }
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';
    
    for( $i=1; $i<18; $i++ ) {
        $html .= '<tr>';
        foreach( $letters as $l ) {
            $html .= '<td style="border: 1px solid; width: 30px; height: 38px; text-align: center;">';
            $html .= $i;
            $html .= '</td>';
            $html .= '<td style="border: 1px solid; width: 170px; height: 38px;">';
            foreach( $results as $r ) {
                if ( $r['benches'] == $l . $i ) {
                    $html .= $r['first_name'] . ' ';
                    $html .= $r['middle_name'] ? $r['middle_name'] . ' ' : '';
                    $html .= $r['last_name'];
                }
            }
            $html .= '</td>';
        }
        $html .= '</tr>';
    }

    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '</div>';

    $mpdf = new mPDF('', 'Letter-L', 0, 'dejavuSans', 6, 6, 6, 0);

    $mpdf->WriteHTML($html);

    $mpdf->Output( 'Baptism_registries_'. $export_date .'.pdf', 'I' );
}

function baptism_registers_pdfs_page() {
  require_once('admin-baptism-registers.php');
    
  //Create an instance of our package class...
  $testListTable = new Placita_List_Table();
  //Fetch, prepare, sort, and filter our data...
  $testListTable->prepare_items();

  ?>
  <div class="wrap">

      <h2>Baptism Pre-registers</h2>
      <img width=200 src="<?php echo plugin_dir_url(__FILE__) . 'media/images/outline-logo-b.png' ?>" />

      <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
      <form id="movies-filter" method="get">
          <!-- For plugins, we also need to ensure that the form posts back to our current page -->
          <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
          <!-- Now we can render the completed list table -->
          <?php $testListTable->display() ?>
      </form>

  </div>
  <?php
}
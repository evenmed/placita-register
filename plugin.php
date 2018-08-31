<?php
/*
Plugin Name: La Placita Register
Plugin URI: http://laplacita.church/
Description: Plugin to create and handle the Baptism Pre-Register form
Version: 2.0
Author: Emilio Venegas
Author URI: http://www.emiliovenegas.me
License: GPL2
*/

global $placita_db_version;
$placita_db_version = '1.6.1';

// CReate db on plugin activation
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
            benches enum('A','B','C','D','E') NULL,
            priest varchar(255) NULL,
            file varchar(255) NULL,
            amount_collected decimal(13,2) DEFAULT '0.00' NOT NULL,
            baptism_date datetime NULL,
            date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            lastedited datetime ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

        update_option( 'placita_db_version', $placita_db_version );
        
    }
}
register_activation_hook( __FILE__, 'placita_install_db' );

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
        array('datetimepicker')
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
        ! in_array( 
            $_REQUEST['field'], 
            array('priest', 'amount_collected', 'baptism_date', 'benches', 'birthdate') 
        ) ||
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
            break;

        case 'amount_collected':
            $value = number_format((float)$v, 2, '.', '');
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
            break;

        case 'benches':
            if (
                in_array( 
                    $v, 
                    array('A', 'B', 'C', 'D', 'E')
                )
            ) {
                $value = $v;
            } else {
                wp_send_json( array(
                    'success' => 0,
                    'message' => __("Please choose benches A, B, C, D or E", 'laplacita')
                 ) );
            }
            break;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'baptism_registers';
    if ($wpdb->update(
        $table_name, 
        array( 
            $field => $value,
        ), 
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
        switch ($field) {
            case 'baptism_date':
                $date = date_create_from_format('Y-m-d H:i:s', $dbVal);
                $value = $date ? $date->format('Y/m/d H:i') : '';
                break;
            case 'birthdate':
                $date = date_create_from_format('Y-m-d', $dbVal);
                $value = $date ? $date->format('Y/m/d') : '';
                break;
            case 'benches':
                $value = $dbVal . '1 - ' . $dbVal . '17';
                break;
            default:
                $value = $dbVal;
                break;
        }
        wp_send_json( array(
            'success' => 1,
            'message' => __("Saved!", 'laplacita'),
            'value'   => $value
         ) );
    }

    wp_send_json( array(
        'success' => 0,
        'message' => __("An error ocurred while updating the registry. Please refresh the page and try again.", 'laplacita'),
        'value'   => $value
    ) );
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
	add_menu_page( 'Baptism Registers', 'Baptism Registers', 'manage_options', 'baptism_registers', 'baptism_registers_page', 'dashicons-admin-page', 6  );
}

function baptism_registers_page() {
  require_once('baptism-registers-table.class.php');
    
  //Create an instance of our package class...
  $testListTable = new Baptism_Registers_Table();
  //Fetch, prepare, sort, and filter our data...
  $testListTable->prepare_items();

  ?>
  <div class="wrap">

      <div id="icon-users" class="icon32"><br/></div>
      <h2>Baptsim Pre-registers</h2>

      <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
      <form id="movies-filter" method="get">
          <!-- For plugins, we also need to ensure that the form posts back to our current page -->
          <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
          <!-- Search Form -->
          <?php $testListTable->search_box('Search', 'search'); ?>
          <!-- Now we can render the completed list table -->
          <?php $testListTable->display() ?>
      </form>

  </div>
  <?php
}
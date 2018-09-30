<?php
/*
Plugin Name: La Placita Register
Plugin URI: http://laplacita.church/
Description: Plugin to create and handle the Baptism Pre-Register form
Version: 3.5
Author: Emilio Venegas
Author URI: http://www.emiliovenegas.me
Text Domain: laplacita
License: GPL2
*/

global $placita_db_version, $bench_numbers;
$placita_db_version = '1.11';
$bench_numbers = array('A1','A2','A3','A4','A5','A6','A7','A8','A9','A10','A11','A12','A13','A14','A15','A16','A17','A18','A19','A20','A21','A22','A23','A24','A25','B1','B2','B3','B4','B5','B6','B7','B8','B9','B10','B11','B12','B13','B14','B15','B16','B17','B18','B19','B20','B21','B22','B23','B24','B25','C1','C2','C3','C4','C5','C6','C7','C8','C9','C10','C11','C12','C13','C14','C15','C16','C17','C18','C19','C20','C21','C22','C23','C24','C25','D1','D2','D3','D4','D5','D6','D7','D8','D9','D10','D11','D12','D13','D14','D15','D16','D17','D18','D19','D20','D21','D22','D23','D24','D25','E1','E2','E3','E4','E5','E6','E7','E8','E9','E10','E11','E12','E13','E14','E15','E16','E17','E18','E19','E20','E21','E22','E23','E24','E25');

// #TODO: make this into an object with properties etc for cleaner code.
// $registry_fields = array( 'first_name', 'middle_name', 'last_name', 'gender', 'birthdate', 'birthplace', 'main_phone', 'contact_email', 'address', 'city', 'state', 'zip', 'father_name', 'father_middle', 'father_last', 'father_email', 'father_phone', 'mother_name', 'mother_middle', 'mother_last', 'mother_email', 'mother_phone', 'mother_married_name', 'mmn_birth_certificate', 'godfather_name', 'godfather_middle', 'godfather_last', 'godfather_email', 'godfather_phone', 'godmother_name', 'godmother_middle', 'godmother_last', 'godmother_email', 'godmother_phone', 'note', 'bautismal_code');

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
            main_phone varchar(255) NOT NULL,
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
            mother_name varchar(255) NOT NULL,
            mother_middle varchar(255) NOT NULL,
            mother_last varchar(255) NOT NULL,
            mother_email varchar(255) NOT NULL,
            mother_phone varchar(255) NOT NULL,
            mother_married_name varchar(255) NOT NULL,
            mmn_birth_certificate tinyint(1) DEFAULT '0' NOT NULL,
            godfather_name varchar(255) NOT NULL,
            godfather_middle varchar(255) NOT NULL,
            godfather_last varchar(255) NOT NULL,
            godfather_email varchar(255) NOT NULL,
            godfather_phone varchar(255) NOT NULL,
            godmother_name varchar(255) NOT NULL,
            godmother_middle varchar(255) NOT NULL,
            godmother_last varchar(255) NOT NULL,
            godmother_email varchar(255) NOT NULL,
            godmother_phone varchar(255) NOT NULL,
            note text NULL,
            bautismal_code varchar(255) NULL,
            benches enum('A1','A2','A3','A4','A5','A6','A7','A8','A9','A10','A11','A12','A13','A14','A15','A16','A17','A18','A19','A20','A21','A22','A23','A24','A25','B1','B2','B3','B4','B5','B6','B7','B8','B9','B10','B11','B12','B13','B14','B15','B16','B17','B18','B19','B20','B21','B22','B23','B24','B25','C1','C2','C3','C4','C5','C6','C7','C8','C9','C10','C11','C12','C13','C14','C15','C16','C17','C18','C19','C20','C21','C22','C23','C24','C25','D1','D2','D3','D4','D5','D6','D7','D8','D9','D10','D11','D12','D13','D14','D15','D16','D17','D18','D19','D20','D21','D22','D23','D24','D25','E1','E2','E3','E4','E5','E6','E7','E8','E9','E10','E11','E12','E13','E14','E15','E16','E17','E18','E19','E20','E21','E22','E23','E24','E25') NULL,
            priest varchar(255) NULL,
            file varchar(255) NULL,
            amount_collected decimal(13,2) DEFAULT '0.00' NOT NULL,
            baptism_date datetime NULL,
            is_canceled tinyint(1) DEFAULT 0 NOT NULL,
            is_noshow tinyint(1) DEFAULT 0 NOT NULL,
            is_private tinyint(1) DEFAULT 0 NOT NULL,
            date timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
            lastedited timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

        update_option( 'placita_db_version', $placita_db_version );
        
    }
}

/**
 * plugins_loaded actions
 * Action 1: Update the db if necessary
 * Action 2: Load text domain
 */
function placita_plugins_loaded() {

    // Update db if necessary
    global $placita_db_version;
    if ( get_site_option( 'placita_db_version' ) != $placita_db_version ) {
        placita_install_db();
    }

    // Load plugin text domain
    load_plugin_textdomain( 'laplacita', FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'placita_plugins_loaded' );

/**
 * Register custom widget area for form language switcher
 */
function placita_widgets_init() {
    register_sidebar( array(
        'name' => __( 'Before Baptism Register Form', 'laplacita' ),
        'id' => 'placita_before_form',
        'before_widget' => '<div id="placita_before_form_widgets_wrap">',
        'after_widget' => '</div>'
    ) );
}
add_action( 'widgets_init', 'placita_widgets_init' );

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
            return admin_url( 'admin.php?page=baptism_registers' );
        } else {
            return admin_url();
        }
    }
}
add_filter( 'login_redirect', 'placita_baptism_manager_login_redirect', 10, 3 );

require_once "classes/class.templater.php";
add_action( 'plugins_loaded', array( 'PageTemplater', 'get_instance' ) );

function placita_scripts() {
    if ( is_page_template( 'baptism-register.php' ) || is_page_template( 'baptism-register-no-redirect.php' )  ) {
        wp_enqueue_style( 'roboto-font', 'https://fonts.googleapis.com/css?family=Roboto+Slab:100,300,400,700' );
        wp_enqueue_style( 'bootstrap', plugin_dir_url( __FILE__ ) . 'vendor/bootstrap/css/bootstrap.min.css' );
        wp_enqueue_style( 'chosen', plugin_dir_url( __FILE__ ) . 'vendor/chosen/chosen.css' );
        wp_enqueue_style( 'jquery-ui-css', plugin_dir_url( __FILE__ ) . 'vendor/jquery-ui/jquery-ui.min.css' );
        wp_enqueue_style(
            'page-template',
            plugin_dir_url( __FILE__ ) . 'css/style.css',
            null,
            '2.0'
        );

        // Datetimepicker styles
        wp_enqueue_style( 'datetimepicker', plugin_dir_url( __FILE__ ) . 'vendor/datetimepicker/jquery.datetimepicker.min.css' );

        // Datetimepicker scripts
        wp_enqueue_script( 
            'datetimepicker', 
            plugin_dir_url( __FILE__ ) . 'vendor/datetimepicker/jquery.datetimepicker.full.min.js',
            array('jquery')
        );

        wp_register_script('jquery-ui', plugin_dir_url( __FILE__ ) . 'vendor/jquery-ui/jquery-ui.min.js', array('jquery'),'', true);
        wp_enqueue_script('jquery-ui');

        wp_register_script('bootstrap', plugin_dir_url( __FILE__ ) . 'vendor/bootstrap/js/bootstrap.min.js', array('jquery'),'', true);
        wp_enqueue_script('bootstrap');

        wp_register_script('chosen', plugin_dir_url( __FILE__ ) . 'vendor/chosen/chosen.jquery.js', array('jquery'),'', true);
        wp_enqueue_script('chosen');

        wp_register_script('multisptep-form', plugin_dir_url( __FILE__ ) . 'js/multistep-form.js', array('jquery', 'jquery-ui'),'', true);
        wp_enqueue_script('multisptep-form');

        wp_register_script('placita-scripts', plugin_dir_url( __FILE__ ) . 'js/scripts.js', array('jquery', 'jquery-ui', 'datetimepicker'),'', true);
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

    // Datetimepicker styles
    wp_enqueue_style( 'datetimepicker', plugin_dir_url( __FILE__ ) . 'vendor/datetimepicker/jquery.datetimepicker.min.css' );

    // We use Bootstrap in the edit single registry page
    $screen = get_current_screen();
    if ( $screen->id == 'toplevel_page_baptism_registers' && isset($_GET['registry']) ) {
        wp_enqueue_style(
            'bootstrap',
            plugin_dir_url( __FILE__ ) . 'vendor/bootstrap/4.0/bootstrap.min.css'
        );
    }

    // Our custom styles
    wp_enqueue_style( 'placita_admin_styles', plugin_dir_url( __FILE__ ) . 'css/admin.css' );

    // Our custom scripts
    wp_enqueue_script( 
        'placita_admin_scripts', 
        plugin_dir_url( __FILE__ ) . 'js/admin.js',
        array('datetimepicker'),
        '2.2'
    );
    wp_localize_script(
        'placita_admin_scripts',
        'server_data',
        [
            'update_registry_nonce' => 
            wp_create_nonce('placita_update_registry_nonce'),
            'loading_spinner_url' =>
            plugin_dir_url( __FILE__ ) . 'media/images/loading_spinner.gif'
        ]
    );

}
add_action( 'admin_enqueue_scripts', 'placita_admin_scripts', 10 );

function register_page( $redirect = true ){

    $redirect;
    $child = array();
    require_once("views/register.php");
}

function placita_handle_baptism_register_form() {

    $checkbox = function ($cb) { return $cb ? 'Yes' : 'No'; };

    //Sanitize everything and put it into our $values array
    $values = sanitize_registry_data();

    // Add current timestamp
    $values['date'] = current_time('Y-m-d G:i:s');

    // Generate the pdf with the sanitized values
    $pdf = placita_generate_pdf($values, false);

    $file = $pdf['file'];

    if ( file_exists( $file ) ) {
        wp_mail(array("baptism@laplacitachurch.org"), "La Placita Baptism Pre-register", "Attached you will find the PDF file with the pre-register info.", array(), $file);
        if ( is_writable($file) )
            unlink($file);
    }

    $values['file'] = $pdf['title'];

    // Save evrything to the db
    global $wpdb;

    $wpdb->insert( 
        $wpdb->prefix . 'baptism_registers', 
        $values
    );

    if ( intval($_POST['thankyou-page']) === 1 ) {
        wp_redirect("http://laplacita.org/es/gracias-aplicacion-bautizos"); // Thank you page
    } else {
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'baptism-register-no-redirect.php'
        ));
        if (empty($pages)) {
            wp_redirect("http://laplacita.org/es/gracias-aplicacion-bautizos"); // Thank you page
        } else {
            $page = $pages[0];
            wp_redirect($page->guid); // Restart form
        }
    }
    exit;
}
add_action( 'admin_post_nopriv_baptism_register_form', 'placita_handle_baptism_register_form' );
add_action( 'admin_post_baptism_register_form', 'placita_handle_baptism_register_form' );

/**
 * Generate pre-registry PDF with passed values
 * 
 * @param array $values Values to fill the PDF fields with
 * @param bool  $inline When true, the PDF will be displayed inline instead of saved. Default: false
 * @return array|bool If $inline is false, it'll return an array with the following structure:
 * [
 *     'file' => full path to the generated file,
 *     'title' => title of the file
 * ]
 * If $inline is true, it'll always return true.
 */
function placita_generate_pdf($values, $inline = false) {

    // Function to print checkboxes as 'Yes'/'No'
    $checkbox = function ($cb) { return $cb ? 'Yes' : 'No'; };

    // Convert dates to mm/dd/yyyy
    $baptism_date = date_create_from_format( 'Y-m-d H:i:s', $values['baptism_date'] );
    $baptism_date = $baptism_date ? $baptism_date->format('m/d/Y g:i a') : 'Not set';

    $birthdate = date_create_from_format( 'Y-m-d', $values['birthdate'] );
    $birthdate = $birthdate ? $birthdate->format('m/d/Y') : 'Not set';

    $date_submitted = date_create_from_format( 'Y-m-d H:i:s', $values['date'] );
    $date_submitted = $date_submitted ? $date_submitted->format('m/d/Y g:i a') : 'Not set';

    $printed_date = current_time('m/d/Y g:i a');

    // Prevent warnings from bench no.
    global $bench_numbers;
    $benches = in_array( $values['benches'], $bench_numbers ) ? $values['benches'] : 'Not set';

    // PDF structure
    $html = <<<PDF
    <h1>La Placita Baptism Pre-Register</h1>

    <section style="width:50%; float:left;">
        <h3>Baptism Date</h3>
        <div>{$baptism_date}</div>
    </section>
    <section style="width:50%; float:left;">
        <h3>Bench No.</h3>
        <div>{$benches}</div>
    </section>

    <h2 style="margin-bottom:0;">Child's Info</h2>
    <hr />

    <section style="width:50%; float:left;">
        <div><strong>First Name:</strong> {$values['first_name']}</div>
        <div><strong>Middle Name:</strong> {$values['middle_name']}</div>
        <div><strong>Last Name:</strong> {$values['last_name']}</div>
    </section>

    <section style="width:50%; float:left;">
        <div><strong>Sex:</strong> {$values['gender']}</div>
        <div><strong>Birthdate:</strong> {$birthdate}</div>
        <div><strong>Birthplace:</strong> {$values['birthplace']}</div>
    </section>


    <h2 style="margin-bottom:0;">Parent's Info</h2>
    <hr />

    <section style="width:50%; float:left;">
        <div><strong>Street Address:</strong> {$values['address']}</div>
        <div><strong>Main Phone:</strong> {$values['main_phone']}</div>
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
    </section>

    <section style="width:50%; float:left;">
        <h3>Mother</h3>
        <div><strong>First Name:</strong> {$values['mother_name']}</div>
        <div><strong>Middle Name:</strong> {$values['mother_middle']}</div>
        <div><strong>Last Name:</strong> {$values['mother_last']}</div>
        <div><strong>Email:</strong> {$values['mother_email']}</div>
        <div><strong>Phone:</strong> {$values['mother_phone']}</div>
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
    </section>

    <section style="width:50%; float:left;">
        <h3>Godmother</h3>
        <div><strong>First Name:</strong> {$values['godmother_name']}</div>
        <div><strong>Middle Name:</strong> {$values['godmother_middle']}</div>
        <div><strong>Last Name:</strong> {$values['godmother_last']}</div>
        <div><strong>Email:</strong> {$values['godmother_email']}</div>
        <div><strong>Phone:</strong> {$values['godmother_phone']}</div>
    </section>

    <br/>
    <br/>

    <section style="width:50%; float:left;">
        <h3>Date Printed</h3>
        <div>{$printed_date}</div>
    </section>
    <section style="width:50%; float:left;">
        <h3>Date Submitted</h3>
        <div>{$date_submitted}</div>
    </section>
 
PDF;
   
     $time = time();
 
     // PDF title
     $title = "Baptism_Preregister_{$values['first_name']}_{$values['last_name']}_{$time}.pdf";
     
     // Remove anything which isn't a word, whitespace, number
     // or any of the following caracters -_~,;:[]().
     // If you don't need to handle multi-byte characters
     // Thanks @Łukasz Rysiak!
     $title = preg_replace("([^\w\s\d\-_~,;:\[\]\(\).])", '', $title);
     // Remove any runs of periods (thanks falstro!)
     $title = preg_replace("([\.]{2,})", '', $title);
 
     // Require composer autoload
     require_once plugin_dir_path(__FILE__) . 'vendor/mPDF/vendor/autoload.php';
     $mpdf = new mPDF('', 'Letter');
     $mpdf->SetTitle( $title );

     $stylesheet = file_get_contents( plugin_dir_path(__FILE__) . 'css/all_caps_pdf.css' ); // external css
     $mpdf->WriteHTML($stylesheet,1);

     $mpdf->WriteHTML($html);

     if ( $inline ) {
        $mpdf->Output( $title, 'I' );
        return true;
     } else {
        $file = plugin_dir_path(__FILE__) . 'pdfs/' . $title;
        $mpdf->Output( $file, 'F' );
        return array(
            'file' => $file,
            'title' => $title
        );
     }

}

/**
 * Upate registry from admin screen
 */
function placita_handle_edit_single_registry() {

    if (
        wp_verify_nonce($_POST['_wpnonce'], 'placita_edit_single_registry') !== 1 ||
        ! current_user_can('manage_baptism') 
    )
        wp_die('Are you sure you want to do this?');

    //Sanitize everything and put it into our $values array
    $values = sanitize_registry_data();

    // Registry ID
    $registry = intval($_POST['registry']);

    // Save evrything to the db
    global $wpdb;

    $updated = $wpdb->update( 
        $wpdb->prefix . 'baptism_registers', 
        $values, 
        array( 'ID' => $registry )
    );

    wp_redirect(
        add_query_arg(
            array(
                'page'     => 'baptism_registers',
                'registry' => $registry,
                'updated'  => $updated ? '1' : '0'
            ),
            admin_url( 'admin.php' )
        )
    ); 
    exit;
}
add_action( 'admin_post_edit_single_registry', 'placita_handle_edit_single_registry' );

/**
 * Delte registry
 */
function placita_handle_delete_single_registry() {

    if (
        wp_verify_nonce($_POST['_wpnonce'], 'placita_delete_single_registry') !== 1 ||
        ! current_user_can('manage_baptism') 
    )
        wp_die('Are you sure you want to do this?');

    // Registry ID
    $registry = intval($_POST['registry']);

    // Delete from the db
    global $wpdb;

    $deleted = $wpdb->delete( 
        $wpdb->prefix . 'baptism_registers',
        array( 'ID' => $registry ),
        array( '%d' )
    );

    wp_redirect(
        add_query_arg(
            array(
                'page'     => 'baptism_registers',
                'deleted'  => $deleted ? '1' : '0'
            ),
            admin_url( 'admin.php' )
        )
    ); 
    exit;
}
add_action( 'admin_post_delete_single_registry', 'placita_handle_delete_single_registry' );

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
            $format = array( '%s' );
            break;

        case 'amount_collected':
            $value = number_format((float)$v, 2, '.', '');
            $update = array( $field => $value );
            $format = array( '%f' );
            break;

        case 'baptism_date':
            $date = date_create_from_format('m/d/Y H:i', $v);
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
            $format = array( '%s', '%s' );
            break;

        case 'birthdate':
            $date = date_create_from_format('m/d/Y', $v);
            $value = $date->format("Y-m-d");
            if ( !$value ) {
                wp_send_json( array(
                    'success' => 0,
                    'message' => __("Please enter the date in a valid format", 'laplacita')
                 ) );
            }
            $update = array( $field => $value );
            $format = array( '%s' );
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
            $format = array( '%s' );
            break;

        case 'is_canceled':
        case 'is_noshow':
        case 'is_private':
            $value = intval($v) === 1 ? 1 : 0;
            $update = array( $field => $value );
            $format = array( '%d' );
            break;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'baptism_registers';
    if ($wpdb->update(
        $table_name, 
        $update, 
        array( 'ID' => $registry ), 
        $format, 
        array( '%d' ) 
    )) { // If the row was updated correctly
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
                $value = $date ? $date->format('m/d/Y H:i') : '';

                // Get the benches that are already occupied at the baptism's datetime
                global $wpdb;

                $table_name = $wpdb->prefix . 'baptism_registers';
                $unavailable_benches = array();

                $results = $wpdb->get_results(
                    sprintf(
                        "SELECT benches
                        FROM %s
                        WHERE baptism_date = '$dbVal'
                        AND id != $registry
                        AND is_canceled = 0
                        AND is_noshow = 0",
                        $table_name
                    ),
                    ARRAY_A
                );
                if ( count($results) > 0 ) {
                    foreach ( $results as $r ) {
                        if ( $r['benches'] )
                            $unavailable_benches[] = $r['benches'];
                    }
                }
                if ( count($unavailable_benches) > 0 )
                    $extra_fields = array( 'unavailable_benches' => $unavailable_benches );
                break;
            case 'birthdate':
                $date = date_create_from_format('Y-m-d', $dbVal);
                $value = $date ? $date->format('m/d/Y') : '';
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
 * Sanitize registry values from $_POST and return an array of the sanitized values
 * 
 * @return array[string/int] 
 */
function sanitize_registry_data() {
    $values = array();
    
    $values['first_name'] = isset($_POST['first_name']) ? 
        sanitize_text_field( $_POST['first_name'] ) : "";
    $values['middle_name'] = isset($_POST['middle_name']) ? 
        sanitize_text_field( $_POST['middle_name'] ) : "";
    $values['last_name'] = isset($_POST['last_name']) ? 
        sanitize_text_field( $_POST['last_name'] ) : "";
    $values['gender'] = isset($_POST['gender']) ? 
        sanitize_text_field( $_POST['gender'] ) : "";
    $values['birthdate'] = ( isset($_POST['birthdate']) && $_POST['birthdate'] != "" ) ? 
        date( "Y-m-d", strtotime( sanitize_text_field( $_POST['birthdate'] ) ) ) : null;
    $values['birthplace'] = isset($_POST['birthplace']) ? 
        sanitize_text_field( $_POST['birthplace'] ) : "";

    // $values['parents_married'] = isset($_POST['parents_married']) ? 
    //     1 : 0;
    // $values['parents_married_church'] = isset($_POST['parents_married_church']) ? 
    //     1 : 0;
    $values['main_phone'] = isset($_POST['main_phone']) ? 
        sanitize_text_field( $_POST['main_phone'] ) : "";
    $values['contact_email'] = isset($_POST['contact_email']) ? 
        sanitize_email( $_POST['contact_email'] ) : "";
    $values['address'] = isset($_POST['address']) ? 
        sanitize_text_field( $_POST['address'] ) : "";
    $values['city'] = isset($_POST['city']) ? 
        sanitize_text_field( $_POST['city'] ) : "";
    $values['state'] = isset($_POST['state']) ? 
        sanitize_text_field( $_POST['state'] ) : "";
    $values['zip'] = isset($_POST['zip']) ? 
        sanitize_text_field( $_POST['zip'] ) : "";

    $values['father_name'] = isset($_POST['father_name']) ? 
        sanitize_text_field( $_POST['father_name'] ) : "";
    $values['father_middle'] = isset($_POST['father_middle']) ? 
        sanitize_text_field( $_POST['father_middle'] ) : "";
    $values['father_last'] = isset($_POST['father_last']) ? 
        sanitize_text_field( $_POST['father_last'] ) : "";
    $values['father_email'] = isset($_POST['father_email']) ? 
        sanitize_email( $_POST['father_email'] ) : "";
    $values['father_phone'] = isset($_POST['father_phone']) ? 
        sanitize_text_field( $_POST['father_phone'] ) : "";
    // $values['father_catholic'] = isset($_POST['father_catholic']) ? 
    //     1 : 0;
    // $values['father_id'] = isset($_POST['father_id']) ? 
    //     1 : 0;

    $values['mother_name'] = isset($_POST['mother_name']) ? 
        sanitize_text_field( $_POST['mother_name'] ) : "";
    $values['mother_middle'] = isset($_POST['mother_middle']) ? 
        sanitize_text_field( $_POST['mother_middle'] ) : "";
    $values['mother_last'] = isset($_POST['mother_last']) ? 
        sanitize_text_field( $_POST['mother_last'] ) : "";
    $values['mother_email'] = isset($_POST['mother_email']) ? 
        sanitize_email( $_POST['mother_email'] ) : "";
    $values['mother_phone'] = isset($_POST['mother_phone']) ? 
        sanitize_text_field( $_POST['mother_phone'] ) : "";
    // $values['mother_catholic'] = isset($_POST['mother_catholic']) ? 
    //     1 : 0;
    // $values['mother_id'] = isset($_POST['mother_id']) ? 
    //     1 : 0;
    $values['mother_married_name'] = isset($_POST['mother_married_name']) ? 
        sanitize_text_field( $_POST['mother_married_name'] ) : "";
    $values['mmn_birth_certificate'] = isset($_POST['mmn_birth_certificate']) ? 
        1 : 0;

    $values['godfather_name'] = isset($_POST['godfather_name']) ? 
        sanitize_text_field( $_POST['godfather_name'] ) : "";
    $values['godfather_middle'] = isset($_POST['godfather_middle']) ? 
        sanitize_text_field( $_POST['godfather_middle'] ) : "";
    $values['godfather_last'] = isset($_POST['godfather_last']) ? 
        sanitize_text_field( $_POST['godfather_last'] ) : "";
    $values['godfather_email'] = isset($_POST['godfather_email']) ? 
        sanitize_email( $_POST['godfather_email'] ) : "";
    $values['godfather_phone'] = isset($_POST['godfather_phone']) ? 
        sanitize_text_field( $_POST['godfather_phone'] ) : "";
    // $values['godfather_catholic'] = isset($_POST['godfather_catholic']) ? 
    //     1 : 0;

    $values['godmother_name'] = isset($_POST['godmother_name']) ? 
        sanitize_text_field( $_POST['godmother_name'] ) : "";
    $values['godmother_middle'] = isset($_POST['godmother_middle']) ? 
        sanitize_text_field( $_POST['godmother_middle'] ) : "";
    $values['godmother_last'] = isset($_POST['godmother_last']) ? 
        sanitize_text_field( $_POST['godmother_last'] ) : "";
    $values['godmother_email'] = isset($_POST['godmother_email']) ? 
        sanitize_email( $_POST['godmother_email'] ) : "";
    $values['godmother_phone'] = isset($_POST['godmother_phone']) ? 
        sanitize_text_field( $_POST['godmother_phone'] ) : "";
    // $values['godmother_catholic'] = isset($_POST['godmother_catholic']) ? 
    //     1 : 0;

    $values['note'] = isset($_POST['note']) ? 
        sanitize_text_field( $_POST['note'] ) : "";
    $values['bautismal_code'] = isset($_POST['bautismal_code']) ? 
        sanitize_text_field( $_POST['bautismal_code'] ) : "";
        

    return $values;
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
            AND is_noshow = 0
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
        ! current_user_can('manage_baptism') || 
        wp_verify_nonce( $_REQUEST['_wpnonce'], 'view_baptism_registry_pdf' ) !== 1
    ) {
        wp_die("Are you sure you want to do this?");
    }

    // Make sure the registry actually exists
    global $wpdb;
    $registry = intval($_REQUEST['baptism_registry']);
    $table_name = $wpdb->prefix . 'baptism_registers';
    $sql = sprintf(
        "SELECT *
        FROM %s
        WHERE id = $registry
        LIMIT 1",
        $table_name
    );

    $results = $wpdb->get_results( $sql , ARRAY_A );

    if ( count($results) === 0 ) {
        wp_die("The registry you're looking for doesn't exist");
    }

    // If everything's good, show the pdf
    $values = $results[0];

    placita_generate_pdf($values, true);

}
add_action( 'admin_post_baptism_register_view_pdf', 'placita_baptism_register_view_pdf' );

function validate_phone($phone) {
    $phone = preg_replace('/\D/', '', $phone); //strip non-numeric characters
    if ( strlen($phone) == 10 ) return true;
    else return false;
}

add_action( 'admin_menu', 'my_admin_menu' );
function my_admin_menu() {
    add_menu_page(
        'Baptism Registers',
        'Baptism Registers',
        'manage_baptism',
        'baptism_registers',
        'baptism_registers_page',
        'dashicons-admin-page',
        6
    );

    add_menu_page(
        'Baptism Registers PDFs',
        'Baptism Registers PDFs',
        'manage_baptism',
        'baptism_registers_pdfs',
        'baptism_registers_pdfs_page',
        'dashicons-admin-page',
        7
    );
}

function baptism_registers_page() {

    if ( isset($_GET['registry']) ) {
        require_once('admin_pages/single-baptism-register.php');
    } else {
        require_once('admin_pages/baptism-registers-page.php');
    }

}

// Export all registries for a given datetime as a PDF
add_action( 'admin_post_export_registries', 'placita_export_registries' );
function placita_export_registries() {
    // Verify nonce / admin referer
    check_admin_referer( 'placita_export_registries' );

    // Verifiy we have a date
    if ( !isset($_REQUEST['export_date']) || !$_REQUEST['export_date'] )
        wp_die('Please set a valid date');

    $export_date = sanitize_text_field($_REQUEST['export_date']);
    $date = date_create_from_format('m/d/Y H:i', $export_date);
    $formatted_date = $date->format('Y/m/d H:i');

    require_once plugin_dir_path(__FILE__) . 'vendor/mPDF/vendor/autoload.php';

    global $wpdb;

    $table_name = $wpdb->prefix . 'baptism_registers';

    $results = $wpdb->get_results(
        sprintf(
            "SELECT first_name, middle_name, last_name, benches
            FROM %s
            WHERE baptism_date = '$formatted_date'
            AND is_canceled = 0
            AND is_noshow = 0",
            $table_name
        ),
        ARRAY_A
    );

    $letters = array('E', 'D', 'C', 'B', 'A');

    $html = '<h3>';
    $html .= $date->format("l, jS \of F Y");
    $html .= '<br/>';
    $html .= $date->format("\a\\t h:i A");
    $html .= '</h3>';
    $html .= '<table cellspacing=0>';

    $html .= '<thead>';
    $html .= '<tr>';
    foreach( $letters as $l ) {
        $html .= '<th class="column-'. $l .'" colspan="2">';
        $html .= $l;
        $html .= '</th>';
    }
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';
    
    for( $i=1; $i<26; $i++ ) {
        $html .= '<tr>';
        foreach( $letters as $l ) {
            $html .= '<td class="number row-'. $i .' number-column-'. $l .'">';
            $html .= $i;
            $html .= '</td>';
            $html .= '<td class="name row-'. $i .' column-'. $l .'">';
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

    $stylesheet = file_get_contents( plugin_dir_path(__FILE__) . 'css/registries-export.css' ); // external css
    $mpdf->WriteHTML($stylesheet,1);

    $caps_stylesheet = file_get_contents( plugin_dir_path(__FILE__) . 'css/all_caps_pdf.css' ); // external css
    $mpdf->WriteHTML($caps_stylesheet,1);

    $mpdf->WriteHTML($html);

    $mpdf->Output( 'Baptism_registries_'. $export_date .'.pdf', 'I' );
}

function baptism_registers_pdfs_page() {
  require_once('classes/class.baptism-registers-pdfs-table.php');
    
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

/**
 * Generate a single certificate with the passed values
 */
add_action( 'admin_post_print_certificates', 'placita_generate_certificate' );
function placita_generate_certificate() {
    // Verify nonce / admin referer
    check_admin_referer( 'placita_print_certificates' );

    // Verifiy we have a date
    if (
        !isset($_REQUEST['certificates_date']) ||
        !( $date = date_create_from_format('m/d/Y H:i', $_REQUEST['certificates_date']) )
        )
        wp_die('Please set a valid date');

    $formatted_date = $date->format('Y/m/d H:i');

    require_once plugin_dir_path(__FILE__) . 'vendor/mPDF/vendor/autoload.php';

    global $wpdb;

    $table_name = $wpdb->prefix . 'baptism_registers';

    $results = $wpdb->get_results(
        sprintf(
            "SELECT
                first_name, middle_name, last_name,
                father_name, father_last,
                mother_name, mother_last,
                birthplace, birthdate,
                baptism_date, priest,
                godfather_name, godfather_last,
                godmother_name, godmother_last
            FROM %s
            WHERE baptism_date = '$formatted_date'
            AND is_canceled = 0
            AND is_noshow = 0",
            $table_name
        ),
        ARRAY_A
    );

    if ( empty($results) ) {
        wp_die('There are no registries for the set date (' . $date->format('m/d/Y H:i') . ')');
    }
    
    $mpdf = new mPDF('', 'Letter', 0, 'Times', 0, 0, 0, 0);
    $stylesheet = file_get_contents( plugin_dir_path(__FILE__) . 'css/certificate.css' ); // external css
    $mpdf->WriteHTML($stylesheet,1);

    foreach ( $results as $r ) {
        $html = get_certificate_html($r);
        $mpdf->AddPage();
        $mpdf->WriteHTML($html);
    }

    $mpdf->SetJS('this.print();');

    $mpdf->Output( 'Test.pdf', 'I' );
}

/**
 * Get the HTML for printing a certificate
 * 
 * @param array $args - array of values to use in the certificate
 */
function get_certificate_html( $args ) {
    $first_name  = $args['first_name'];
    $middle_name = $args['middle_name'] ? ' ' . $args['middle_name'] : '';
    $last_name   = ' ' . $args['last_name'];
    $child_name  = "$first_name $middle_name $last_name";

    $father_fn = $args['father_name'];
    $father_ln = $args['father_last'];
    $mother_fn = $args['mother_name'];
    $mother_ln = $args['mother_last'];
    $parents_name = "$father_fn $father_ln & $mother_fn $mother_ln";
    
    $birthplace = $args['birthplace'];

    if ( $birthdate = date_create_from_format('Y-m-d', $args['birthdate']) ) {
        $month = $birthdate->format('M');
        $day   = $birthdate->format('d');
        $year  = $birthdate->format('Y');
    }
    
    if ( $bapt_date = date_create_from_format('Y-m-d H:i:s', $args['baptism_date']) ) {
        $bapt_month = $bapt_date->format('M');
        $bapt_day   = $bapt_date->format('d');
        $bapt_year  = $bapt_date->format('Y');
    }
    
    $priest_name = $args['priest'];
    
    $godfather_fn = $args['godfather_name'];
    $godfather_ln = $args['godfather_last'];
    $godmother_fn = $args['godmother_name'];
    $godmother_ln = $args['godmother_last'];
    $godparents_name = "$godfather_fn $godfather_ln & $godmother_fn $godmother_ln";

    $signature_url = plugin_dir_url( __FILE__ ) . 'media/images/firma_padre.png';

    $html = "<div id='bg'></div>";
    $html .= $child_name ? "<div id='child_name'>$child_name</div>" : "";
    $html .= $parents_name ? "<div id='parents_name'>$parents_name</div>" : "";
    $html .= $birthplace ? "<div id='birthplace'>$birthplace</div>" : "";
    $html .= $month ? "<div id='month'>$month</div>" : "";
    $html .= $day ? "<div id='day'>$day</div>" : "";
    $html .= $year ? "<div id='year'>$year</div>" : "";
    $html .= $bapt_month ? "<div id='bapt_month'>$bapt_month</div>" : "";
    $html .= $bapt_day ? "<div id='bapt_day'>$bapt_day</div>" : "";
    $html .= $bapt_year ? "<div id='bapt_year'>$bapt_year</div>" : "";
    $html .= $priest_name ? "<div id='priest_name'>$priest_name</div>" : "";
    $html .= $godparents_name ? "<div id='godparents_name'>$godparents_name</div>" : "";
    $html .= "<div id='signature'><img class='singature_img' src='$signature_url' /></div>";

    return $html;
}
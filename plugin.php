<?php
/*
Plugin Name: La Placita Register
Plugin URI: http://laplacita.church/
Description: Plugin to create and handle the Baptism Pre-Register form
Version: 1.0
Author: Emilio Venegas
Author URI: http://www.emiliovenegas.me
License: GPL2
*/

require "class.templater.php";
add_action( 'plugins_loaded', array( 'PageTemplater', 'get_instance' ) );

function placita_baptism_preregister_styles() {
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
add_action( 'wp_enqueue_scripts', 'placita_baptism_preregister_styles', 100 );

function register_page(){

    $childid = $_GET['child-id'] ? $_GET['child-id'] : false;
    $child = get_child($childid);

    require_once("views/register.php");
}

function placita_handle_baptism_register_form() {

  $checkbox = function ($cb) { return $cb ? 'Yes' : 'No'; };

  $values = array();

  //$values['registrar'] = $current_user['id'];

  $values['first_name'] = isset($_POST['first-name']) ? $_POST['first-name'] : "";
  $values['middle_name'] = isset($_POST['middle-name']) ? $_POST['middle-name'] : "";
  $values['last_name'] = isset($_POST['last-name']) ? $_POST['last-name'] : "";
  $values['gender'] = isset($_POST['gender']) ? $_POST['gender'] : "";
  $values['birthdate'] = ( isset($_POST['birthdate']) && $_POST['birthdate'] != "" ) ? date("Y-m-d", strtotime($_POST['birthdate'])) : null;
  $values['birthplace'] = isset($_POST['birthplace']) ? $_POST['birthplace'] : "";

  $values['parents_married'] = isset($_POST['parents-married']) ? 1 : 0;
  $values['parents_married_church'] = isset($_POST['parents-married-church']) ? 1 : 0;
  $values['contact_email'] = isset($_POST['contact-email']) ? $_POST['contact-email'] : "";
  $values['address'] = isset($_POST['address']) ? $_POST['address'] : "";
  $values['city'] = isset($_POST['city']) ? $_POST['city'] : "";
  $values['state'] = isset($_POST['state']) ? $_POST['state'] : "";
  $values['zip'] = isset($_POST['zip']) ? $_POST['zip'] : "";
  //$values['parents_parish'] = isset($_POST['parents-parish']) ? $_POST['parents-parish'] : "";

  $values['father_name'] = isset($_POST['father-first-name']) ? $_POST['father-first-name'] : "";
  $values['father_middle'] = isset($_POST['father-middle-name']) ? $_POST['father-middle-name'] : "";
  $values['father_last'] = isset($_POST['father-last-name']) ? $_POST['father-last-name'] : "";
  $values['father_email'] = isset($_POST['father-email']) ? $_POST['father-email'] : "";
  $values['father_phone'] = isset($_POST['father-phone']) ? $_POST['father-phone'] : "";
  $values['father_catholic'] = isset($_POST['father-catholic']) ? 1 : 0;
  $values['father_id'] = isset($_POST['father-id']) ? 1 : 0;

  $values['mother_name'] = isset($_POST['mother-first-name']) ? $_POST['mother-first-name'] : "";
  $values['mother_middle'] = isset($_POST['mother-middle-name']) ? $_POST['mother-middle-name'] : "";
  $values['mother_last'] = isset($_POST['mother-last-name']) ? $_POST['mother-last-name'] : "";
  $values['mother_email'] = isset($_POST['mother-email']) ? $_POST['mother-email'] : "";
  $values['mother_phone'] = isset($_POST['mother-phone']) ? $_POST['mother-phone'] : "";
  $values['mother_catholic'] = isset($_POST['mother-catholic']) ? 1 : 0;
  $values['mother_id'] = isset($_POST['mother-id']) ? 1 : 0;
  $values['mother_married_name'] = isset($_POST['mother-married-name']) ? $_POST['mother-married-name'] : "";
  $values['mmn_birth_certificate'] = isset($_POST['mother-birth-certificate']) ? 1 : 0;

  $values['godfather_name'] = isset($_POST['godfather-first-name']) ? $_POST['godfather-first-name'] : "";
  $values['godfather_middle'] = isset($_POST['godfather-middle-name']) ? $_POST['godfather-middle-name'] : "";
  $values['godfather_last'] = isset($_POST['godfather-last-name']) ? $_POST['godfather-last-name'] : "";
  $values['godfather_email'] = isset($_POST['godfather-email']) ? $_POST['godfather-email'] : "";
  $values['godfather_phone'] = isset($_POST['godfather-phone']) ? $_POST['godfather-phone'] : "";
  //$values['godfather_parish'] = isset($_POST['godfather-parish']) ? $_POST['godfather-parish'] : "";
  $values['godfather_catholic'] = isset($_POST['godfather-catholic']) ? 1 : 0;

  $values['godmother_name'] = isset($_POST['godmother-first-name']) ? $_POST['godmother-first-name'] : "";
  $values['godmother_middle'] = isset($_POST['godmother-middle-name']) ? $_POST['godmother-middle-name'] : "";
  $values['godmother_last'] = isset($_POST['godmother-last-name']) ? $_POST['godmother-last-name'] : "";
  $values['godmother_email'] = isset($_POST['godmother-email']) ? $_POST['godmother-email'] : "";
  $values['godmother_phone'] = isset($_POST['godmother-phone']) ? $_POST['godmother-phone'] : "";
  //$values['godmother_parish'] = isset($_POST['godmother-parish']) ? $_POST['godmother-parish'] : "";
  $values['godmother_catholic'] = isset($_POST['godmother-catholic']) ? 1 : 0;

  $values['note'] = isset($_POST['note']) ? $_POST['note'] : "";
  $values['bautismal_code'] = isset($_POST['bautismal-code']) ? $_POST['bautismal-code'] : "";

  $values['lastedited'] = date( 'Y-m-d H:i:s' );

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
  
  $title = "Baptism_Preregister_{$values['first_name']}_{$values['last_name']}_{$values['lastedited']}.pdf";
  
  // Remove anything which isn't a word, whitespace, number
  // or any of the following caracters -_~,;:[]().
  // If you don't need to handle multi-byte characters
  // you can use preg_replace rather than mb_ereg_replace
  // Thanks @Łukasz Rysiak!
  $file = preg_replace("([^\w\s\d\-_~,;:\[\]\(\).])", '', $title);
  // Remove any runs of periods (thanks falstro!)
  $file = preg_replace("([\.]{2,})", '', $title);
  
  $file = plugin_dir_path(__FILE__) . 'pdfs/' . $title;

  // Require composer autoload
  require_once __DIR__ . '/vendor/mPDF/vendor/autoload.php';
  $mpdf = new mPDF('', 'Letter');
  $mpdf->SetTitle( $title );
  $mpdf->WriteHTML($html);
//   $content = $mpdf->Output( $title, 'I' );
  $content = $mpdf->Output( $file, 'F' );

  if ( file_exists( $file ) ) {
    wp_mail(array("baptism@laplacitachurch.org"), "La Placita Baptism Pre-register", "Attached you will find the PDF file with the pre-register info.", array(), $file);
  }

  wp_redirect("http://laplacita.org/es/gracias-aplicacion-bautizos"); // Thank you page

  exit;
}
add_action( 'admin_post_nopriv_baptism_register_form', 'placita_handle_baptism_register_form' );
add_action( 'admin_post_baptism_register_form', 'placita_handle_baptism_register_form' );

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
  require_once('admin-baptism-registers.php');
    
  //Create an instance of our package class...
  $testListTable = new Placita_List_Table();
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
          <!-- Now we can render the completed list table -->
          <?php $testListTable->display() ?>
      </form>

  </div>
  <?php
}
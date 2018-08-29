<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function register_page(){

    global $current_user;

    $childid = $_GET['child-id'] ? $_GET['child-id'] : false;
    $child = get_child($childid);

    require_once("views/register.php");
}

function print_nav_2( $language_switcher, $echo ) {
?>
    <nav id="nav" class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <?php
                if ($language_switcher){
                    language_switcher();
                }
                ?>
                <a class="navbar-brand" href="/"><img alt="La Placita" src="/media/images/lightgray_logo.png" /></a>
            </div>
        </div>
        <!-- /.container-fluid -->
    </nav>
<?php
}

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

function logout_link() {
    echo "<a slt='Log out' href='?logout=true'><button class='btn '>Logout</button></a>";
}

function validate_phone($phone) {
    $phone = preg_replace('/\D/', '', $phone); //strip non-numeric characters
    if ( strlen($phone) == 10 ) return true;
    else return false;
}

function login_or_signup($phone) {
    global $db;
    $data = $db->select( "users", "*", ["phone" => $phone, "LIMIT" => 1] );
    if ( empty($data) ) signup($phone);
    else login($data[0]);
}

function signup($phone) {
    if ( validate_phone($phone) ){ //validate again just in case
        global $db;
        $db->insert( "users", ["phone" => $phone, "language" => LANGUAGE ] );
        if ( $new_id = $db->id() ) {
            $data = get_user_data($new_id);
            login($data);
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function page_specific_scripts() {
    if ( PAGE == "register" ) { ?>
        <script src="/baptism-register/js/multistep-form.js"></script>
    <?php }
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

function is_admin_page() {
    $uri = $_SERVER['REQUEST_URI'];
    if ( substr( $uri, 0, 6 ) === "/admin" ) {
        return true;
    } else {
        return false;
    }
}

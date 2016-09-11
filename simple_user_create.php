<?php
/*
Plugin Name: Quick Create User
Description: Create a shib-compatible user ahead of time, and a portfolio for them if desired
Author: Joe Bacal
Version: 0.1
*/

//add the menu page
add_action('admin_menu', 'qcu_setup_menu');

//add the form to the menu page
function qcu_setup_menu(){
  add_menu_page( 'Quick Create User Page', 'Quick Create User', 'manage_options', 'quick-create-user', 'qcu_form' );
}

function qcu_form(){

  ?>
    <style>
      #qcu-form input {
        width:400px;
        margin-bottom:25px;
      }
    </style>

    <div class="wrap">

      <h2>Quick Create Shibboleth-Compatible User</h2>
      <h3>(and optionally a Portfolio for the user)</h3>
      <h4>Use this form <span style="color:red;">only</span> when creating users who will use Shibboleth to log in</h4>

      <form method="post" action="<?php echo admin_url() . 'admin-post.php'; ?>">

        <input type="hidden" name="action" value="qcu_handle">

        <label for="qcu-email">Email</label><br>
        <input type="text" name="qcu-email" value=""><br>

        <label for="qcu-first-name">First Name</label><br>
        <input type="text" name="qcu-first-name" value=""><br>

        <label for="qcu-last-name">Last Name</label><br>
        <input type="text" name="qcu-last-name" value=""><br><br>

        <label for="qcu-setup-new-site">Set up a new Portfolio for this user?</label>
        <input type="checkbox" name="qcu-setup-new-site" value=""><br><br>

        <label for="qcu-setup-domain">Domain for site:<br><em>If you are not at Smith College, you'll want to change this</em></label>
        <input type="text" name="qcu-setup-domain" value="sophia.smith.edu"><br><br>

        <input type="submit" name="submit" class="button button-primary" value="Create Shibboleth compatible user">
      </form>
    </div>
  <?php
}


function qcu_create_user() {

  //make sure no existe ya
  if ((null == username_exists($_POST['qcu-email'])) && ( null == email_exists( $_POST['qcu-email']))) {

    $domain   = sanitize_text_field($_POST['qcu-setup-domain']);
    $username = sanitize_text_field($_POST['qcu-email']);
    $first    = sanitize_text_field($_POST['qcu-first-name']);
    $last     = sanitize_text_field($_POST['qcu-last-name']);

    //set up the new users stuff
    $userdata = array(
      'user_login'  => $username,
      'user_email'  => $username,
      'first_name'  => $first,
      'last_name'   => $last
    );

    //create the new user and collect the ID
    $user_id = wp_insert_user( $userdata );

    //flag for shibboleth
    $user = new WP_User($user_id);
    update_usermeta($user->ID, 'shibboleth_account', true);

    if ($_POST['qcu-setup-new-site'] == true ){
      //set up new site data
      $path = '/' .  substr($email, 0, strpos($email, '@')) . '/';
      $title = $first . ' ' . $last;

      //add the new site
      $new_site = wpmu_create_blog($domain, $path, $title, $user_id);

      //TODO: email them
    }

    //TODO: add confirmation
  }

  else {
    echo 'ya existe este user';
    //TODO: handle errors
  }

  //do it again if you like
  wp_redirect( admin_url() . 'admin.php?page=quick-create-user' );
  exit;

}
add_action( 'admin_post_qcu_handle', 'qcu_create_user' );

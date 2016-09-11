<?php
/*
Plugin Name: Quick Create User
Description: Create a shib-compatible user ahead of time
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

      <h2>Quick Create User</h2>
      <h4>Use this form <span style="color:red;">only</span> when creating users who will use Shibboleth to log in</h4>

      <form method="post" action="<?php echo admin_url() . 'admin-post.php'; ?>">

        <input type="hidden" name="action" value="qcu_handle">

        <label for="qcu-email">Email</label><br>
        <input type="text" name="qcu-email" value=""><br><br><br>

        <input type="submit" name="submit" class="button button-primary" value="Create Shibboleth compatible user">
      </form>
    </div>
  <?php
}


function qcu_create_user() {

  //make sure no existe ya
 	if ((null == username_exists($_POST['qcu-email'])) && ( null == email_exists( $_POST['qcu-email']))) {

    //set up the new users stuff
    $userdata = array(
 			'user_login' => $_POST['qcu-email']
 			//'user_email' => $_POST['qcu-email'] because it gets overwritten anyway
 		);

 		//create the new user
 		$user_id = wp_insert_user( $userdata );

    //flag for shibboleth
    $user = new WP_User($user_id);
    update_usermeta($user->ID, 'shibboleth_account', true);
  }

  //do it again if you like
  wp_redirect( admin_url() . 'admin.php?page=quick-create-user' );
  exit;
}
add_action( 'admin_post_qcu_handle', 'qcu_create_user' );

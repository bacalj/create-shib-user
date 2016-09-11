<?php
/*
Plugin Name: Quick Create User
Description: Getting around requirement to not have @maildomain in username, for shib
Author: Joe Bacal
Version: 0.1
*/

//add the menu page
add_action('admin_menu', 'qcu_setup_menu');

//add the setup to the menu page
function qcu_setup_menu(){
  add_menu_page( 'Quick Create User Page', 'Quick Create User', 'manage_options', 'quick-create-user', 'qcu_setup' );
}

function qcu_setup(){
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

      <form method="post" action="<?php echo admin_url() . '/admin-post.php'; ?>">

        <input type="hidden" name="action" value="qcu_handle">

        <label for="qcu-email">Email</label><br>
        <input type="text" name="qcu-email" value=""><br>

        <label for="qcu-first-name">First Name</label><br>
        <input type="text" name="qcu-first-name" value=""><br>

        <label for="qcu-last-name">Last Name</label><br>
        <input type="text" name="qcu-last-name" value=""><br>

        <input type="submit" name="submit" class="button button-primary" value="Create Shibboleth compatible user">
      </form>
    </div>
  <?php
}


add_action( 'admin_post_qcu_handle', 'qcu_create_user' );

function qcu_create_user() {
    echo '<pre>';
    print_r($_POST);
    echo '</pre>';
    die();
}

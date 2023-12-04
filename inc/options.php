<?php
// Define the plugin option name
define('CSV_UPLOAD_OPTION', 'csv_upload_option');

// Add a menu item to the admin menu
add_action('admin_menu', 'csv_upload_plugin_menu');
function csv_upload_plugin_menu() {
    add_menu_page(
        'CSV Upload Plugin',
        'CSV Upload',
        'manage_options',
        'csv-upload-plugin',
        'csv_upload_plugin_page'
    );
}

// Callback function to render the options page
function csv_upload_plugin_page() {
    ?>
    <div class="wrap">
        <h2>CSV Upload Plugin</h2>
        <form method="post" enctype="multipart/form-data" action="<?php echo esc_url(admin_url('admin-post.php?action=csv-upload-plugin')); ?>">
            <?php
            settings_fields('csv_upload_plugin_settings');
            do_settings_sections('csv-upload-plugin');
            echo '<input type="hidden" name="action" value="csv-upload-plugin">';
            echo '<input name="_wpnonce" type="hidden" value="'.wp_create_nonce( 'csv_upload_action' ).'">';
            submit_button('Upload CSV and Create Posts');
            ?>
        </form>
    </div>
    <?php
}

// Register settings and fields
add_action('admin_init', 'csv_upload_plugin_settings');
function csv_upload_plugin_settings() {
    register_setting('csv_upload_plugin_settings', CSV_UPLOAD_OPTION);

    add_settings_section(
        'csv_upload_plugin_section',
        'CSV Upload Settings',
        'csv_upload_plugin_section_callback',
        'csv-upload-plugin'
    );

    add_settings_field(
        'csv_file',
        'Upload CSV File',
        'csv_file_callback',
        'csv-upload-plugin',
        'csv_upload_plugin_section'
    );

    add_settings_field(
        'checkbox_field',
        'Append data',
        'checkbox_field_callback',
        'csv-upload-plugin',
        'csv_upload_plugin_section'
    );
}

// Section callback function
function csv_upload_plugin_section_callback() {
    echo '<p>Upload a CSV file and check the box if data needs to be appended.</p>';
}

// Callback function for CSV file field
function csv_file_callback() {
    $value = get_option(CSV_UPLOAD_OPTION);
    ?>
    <input type="file" name="<?php echo CSV_UPLOAD_OPTION; ?>[csv_file]" />
    <?php
}

// Callback function for checkbox field
function checkbox_field_callback() {
    $value = get_option(CSV_UPLOAD_OPTION);
    ?>
    <label>
        <input type="checkbox" name="<?php echo CSV_UPLOAD_OPTION; ?>[append_data]" value="1" />
        Append data
    </label>
    <?php
}

// Handle Submit
add_action('admin_post_csv-upload-plugin', 'process_csv_upload');
function process_csv_upload() {

    if (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'csv_upload_action')) {
    
        $csv_option = get_option(CSV_UPLOAD_OPTION);
        $result = false;
        if (isset( $_FILES[CSV_UPLOAD_OPTION]['tmp_name']['csv_file'] ) ) {
            if( ! $_POST['csv_upload_option']['append_data'] ){
               $result = delete_data();
            }else{
                $result = true;
            }
            if ( $result ) {
                $handle = fopen($_FILES[CSV_UPLOAD_OPTION]['tmp_name']['csv_file'], "r");
                $headers = fgetcsv($handle, null, ",");
                while (($row  = fgetcsv($handle, 1000, ",")) !== FALSE) {
                
                    // Use $row data to create posts
                    $post_args = array(
                        'post_title' => $row[1], // Assuming the second column is the post title
                        'post_type' => 'perpetualregister',
                        'post_status' => 'publish',
                    );
                    $post_id = wp_insert_post($post_args);
                    // Update meta data
                    update_field( 'field_prs_lifestats' , $row[2], $post_id );
                    update_field( 'field_prs_sort' , $row[3], $post_id );
                }
                fclose($handle);
            }

            // Optionally, you can add a success message or redirect the user
            wp_redirect(admin_url('admin.php?page=csv-upload-plugin&success=1'));
            exit();
        }
    }
    // Handle errors or redirect on failure
    wp_redirect(admin_url('admin.php?page=csv-upload-plugin&error=1'));
    exit();
} 
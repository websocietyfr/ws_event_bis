<?php
/**
 * Plugin Name:       WS Event Plugin
 * Plugin URI:        https://formation.websociety.fr/
 * Description:       add Event content-type on Wordpress.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.0
 * Author:            WEB SOCIETY
 * Author URI:        https://websociety.fr/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ws_event_plugin
 * Domain Path:       /event
 */

function ws_event_plugin_event_contenttype_creation() {
    $labels = [
        'name' => __('Evénements'),// libellé du nom du type de contenu
		'singular_name' => __('Evénement'),// Libellé singulier du type de contenu
		'add_new' => __('Ajouter'),// Libellé du bouton d'ajout
		'add_new_item' => __('Ajouter un événement'),// Libellé du bouton d'ajout d'un item (menu)
		'edit_item' => __('Modifier un événement'),// libellé de modification d'un événement
		'new_item' => __('Nouvel événement'),// Libellé de l'indicateur de nouvel événement
		'view_item' => __('Voir l\'événement'),// Libellé de l'action permettant d'accéder à l'édition d'un adhérent
		'search_items' => __('Rechercher un événement'),// Libellé lié à la recherche sur ce type de contenu
		'not_found' => __('Aucun événement trouvé'),// Libellé lors de l'absence de contenu pour ce type
		'not_found_in_trash' => __('Aucun événement dans la corbeille'),// Libellé pour l'absence de contenu dans la corbeille
		'parent_item_colon' => __('Evénement parent :'),// Libellé pour la fonctionnaltié de contenu parent sur ce type de contenu
		'menu_name' => __('Evénements'),// Libellé du menu pour ce type de contenu
    ];
    register_post_type('ws_event', array(
        'labels' => $labels,
        'hierarchical' => false,
        'description' => __('Liste des événements'),
        'supports' => array('title', 'editor', 'thumbnail', 'author', 'revision', 'excerpt'),
        'taxonomies' => array('category'),
        'public' => true,
        'show_in_menu' => true,
        'show_ui' => true,
        'menu_position' => 5,
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'capability_type' => 'post',
        'rewrite' => [ "slug" => 'events' ]
    ));
}
add_action('init', 'ws_event_plugin_event_contenttype_creation');

function ws_event_plugin_assets() {
    wp_enqueue_style( 'ws_plugin', plugin_dir_url(__FILE__) . '/assets/css/style.css', array(), '1.0', 'all');
}
add_action('wp_enqueue_scripts', 'ws_event_plugin_assets');

function ws_event_plugin_assetsadmin( $hook ) {
    wp_enqueue_script( 'admin_script', plugin_dir_url(__FILE__) . '/assets/js/admin-ws_event.js', array( 'jquery' ), '0.1.0', true );
    // Localize script is needed to have the nonce included
    wp_localize_script(
        'admin_script',
        'global',
        array(
            'ajax' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce('media-form')
        )
    );
}
add_action('admin_enqueue_scripts', 'ws_event_plugin_assetsadmin');

function ws_event_plugin_create_metaboxes() {
    add_meta_box('events_data', __('Configuration de l\'événement'), 'ws_event_plugin_build_form', 'ws_event');
}
function ws_event_plugin_build_form() {
    global $post;
    $custom = get_post_custom($post->ID);
    $address = '';
    $linkToRegister = '';
    $linktoDocumentation = '';
    $startDate = '';
    $endDate = '';
    if (count($custom) > 0) {
        $address = isset($custom['address']) ? $custom['address'][0] : '';
        $linkToRegister = isset($custom['linkToRegister']) ? $custom['linkToRegister'][0] : '';
        $linktoDocumentation = isset($custom['linktoDocumentation']) ? $custom['linktoDocumentation'][0] : '';
        $startDate = isset($custom['startDate']) ? $custom['startDate'][0] : '';
        $endDate = isset($custom['endDate']) ? $custom['endDate'][0] : '';
    }
    ?>
        <p>
            <label for="address"><?php echo __('Adresse : '); ?></label>
            <input type="text" name="address" value="<?php echo $address; ?>" required/>
        </p>
        <p>
            <label for="linkToRegister"><?php echo __('Lien d\'inscription chez le partenaire : '); ?></label>
            <input type="url" name="linkToRegister" value="<?php echo $linkToRegister; ?>"/>
        </p>
        <p>
            <label for="linktoDocumentation"><?php echo __('Lien de la documentation de l\'événement : '); ?></label>
            <input type="url" name="linktoDocumentation" value="<?php echo $linktoDocumentation; ?>"/>
        </p>
        <p>
            <label for="startDate"><?php echo __('Date de début de l\'événement : '); ?></label>
            <input type="datetime-local" name="startDate" value="<?php echo $startDate; ?>" required/>
        </p>
        <p>
            <label for="endDate"><?php echo __('Date de fin de l\'événement : '); ?></label>
            <input type="datetime-local" name="endDate" value="<?php echo $endDate; ?>" required/>
        </p>
        <!-- section MEDIAS -->
        <p>
            <input class="input_file" type="file" id="img_file" name="input_file" />
            <input type="hidden" id="hidden-file-field" class="hidden-file-field" name="hidden_file_field" value="" />
        </p>
    <?php
}
add_action('add_meta_boxes', 'ws_event_plugin_create_metaboxes');

function ws_event_plugin_save_events() {
    global $post;
    $post_type = get_post_type();
    if ($post_type == 'ws_event') {
        if (isset($_POST['address'])) {
            update_post_meta($post->ID, 'address', $_POST['address']);
        }
        if (isset($_POST['linkToRegister'])) {
            update_post_meta($post->ID, 'linkToRegister', $_POST['linkToRegister']);
        }
        if (isset($_POST['linktoDocumentation'])) {
            update_post_meta($post->ID, 'linktoDocumentation', $_POST['linktoDocumentation']);
        }
        if (isset($_POST['startDate'])) {
            update_post_meta($post->ID, 'startDate', $_POST['startDate']);
        }
        if (isset($_POST['endDate'])) {
            update_post_meta($post->ID, 'endDate', $_POST['endDate']);
        }
        // MEDIAS SECTION
        if (isset($_POST['hidden_file_field'])) {
            // var_dump($_POST['hidden_file_field']);die;
            update_post_meta($post->ID, 'hidden_file_field', $_POST['hidden_file_field']);
        }
    }
}
add_action('save_post', 'ws_event_plugin_save_events');


function ws_event_plugin_columns() {
    return [
        'cb' => "<input type='checkbox' />",
        'id' => __('Numéro d\'événement'),
        'title' => __('Nom de l\'événement'),
        'startDate' => __('Date de début'),
        'endDate' => __('Date de fin')
    ];
}
add_filter('manage_edit-ws_event_columns', 'ws_event_plugin_columns');

function ws_event_plugin_manage_columns($column) {
    global $post;
    $post_type = get_post_type();
    if ($post_type == 'ws_event') {
        $custom = get_post_custom($post->ID);
        if ($column == 'id') {
            echo $post->ID;
        }
        if ($column == 'startDate') {
            echo isset($custom['startDate']) ? $custom['startDate'][0] : '';
        }
        if ($column == 'endDate') {
            echo isset($custom['endDate']) ? $custom['endDate'][0] : '';
        }
    }
}
add_action('manage_posts_custom_column', 'ws_event_plugin_manage_columns');

function ws_event_plugin_singletemplate($single) {
    global $post;
    if ($post->post_type == 'ws_event' && file_exists(dirname(__FILE__) . '/single-ws_event.php')) {
        $single = dirname(__FILE__) . '/single-ws_event.php';
    }
    return $single;
}
add_filter('single_template', 'ws_event_plugin_singletemplate');

function ws_event_plugin_archivetemplate($archivetemplate) {
    global $post;
    if (is_post_type_archive('ws_event')) {
        $archivetemplate = dirname(__FILE__) . '/archive-ws_event.php';
    }
    return $archivetemplate;
}
add_filter('archive_template', 'ws_event_plugin_archivetemplate');


// GESTION D'ENVOI DE MEDIAS
add_action( 'wp_ajax_media_upload', 'media_upload' );
add_action( 'wp_ajax_nopriv_media_upload', 'media_upload');

function media_upload(){
    if ( check_ajax_referer( 'media-form', 'nonce', false ) == false ) {
        wp_send_json_error(array('error' => 'nonce failed'));
    }
    require_once(ABSPATH . 'wp-load.php');
    if (isset($_FILES['input_file'] ) && !empty($_FILES['input_file']['name']) )
    {
        $allowedExts = array("png", "jpg", "jpeg", "gif");


        $temp = explode(".", $_FILES["input_file"]["name"]);
        $extension = end($temp);
        if ( in_array($extension, $allowedExts))
        {
            if ( ($_FILES["input_file"]["error"] > 0) && ($_FILES['input_file']['size'] <= 3145728 ))
            {
                $response = array(
                    "status" => 'error',
                    "message" => 'ERROR Return Code: '. $_FILES["input_file"]["error"],
                );
            }
            else
            {
                $uploadedfile = $_FILES['input_file'];
                $upload_name = $_FILES['input_file']['name'];
                $uploads = wp_upload_dir();
                $filepath = $uploads['path']."/$upload_name";

                if ( ! function_exists( 'wp_handle_upload' ) )
                {
                    require_once( ABSPATH . 'wp-admin/includes/file.php' );
                }
                require_once( ABSPATH . 'wp-admin/includes/image.php' );
                $upload_overrides = array( 'test_form' => false );
                $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
                if ( $movefile && !isset( $movefile['error'] )  ) {

                    $file = $movefile['file'];
                    $url = $movefile['url'];
                    $type = $movefile['type'];

                    $attachment = array(
                        'post_mime_type' => $type ,
                        'post_title' => $upload_name,
                        'post_content' => 'File '.$upload_name,
                        'post_status' => 'inherit'
                    );

                    $attach_id = wp_insert_attachment( $attachment, $file, 0);
                    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
                    wp_update_attachment_metadata( $attach_id, $attach_data );

                }

                $response = array(
                    "status" => 'success',
                    "url" => $url,
                    "attachment_id" => $attach_id
                );

            }
        }
        else
        {
            $response = array(
                "status" => 'error',
                "message" => 'something went wrong, most likely file is to large for upload. check upload_max_filesize, post_max_size and memory_limit in you php.ini',
            );
        }
    }
    wp_send_json_success($response);
}

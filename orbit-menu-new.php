<?php
/*
 * Plugin Name:       Orbit Menu New
 * Plugin URI:        https://bit.ly/about-me-Litvinov
 * Description:       Menu to circule
 * Version:           1.0
 * Requires PHP:      7.3
 * Author:            Artem Litvinov
 * Author URI:        http://bit.ly/orbit-menu
 * Text Domain:       orbit-menu-new-new
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'plugins_loaded', function(){
    load_plugin_textdomain( 'orbit-menu-new', false, __DIR__ . '/languages' );
} );

add_action( 'wp_enqueue_scripts', 'orbit_menu_new_plugin_add_scripts', 99999 );
function orbit_menu_new_plugin_add_scripts() {
    wp_enqueue_style( 'orbit-style', plugins_url( 'assets/css/orbit-style.css' , __FILE__ ), array(), false, 'all' );
    wp_enqueue_script( 'orbit-script-template-script', plugins_url( 'assets/js/orbit-script.js' , __FILE__ ), false, null, true );
}

add_action( 'admin_menu', 'orbit_menu_new_plugin_add_admin_page' );

function orbit_menu_new_plugin_add_admin_page() {
    $hook_suffix = add_menu_page( __('Orbit Menu New Options', 'orbit-menu-new'), __('Orbit Menu New', 'orbit-menu-new'), 'manage_options', 'orbit-menu-new-options', 'orbit_menu_new_plugin_create_page', 'dashicons-admin-site-alt3' );

    add_action( "admin_print_scripts-{$hook_suffix}", 'orbit_menu_new_plugin_admin_scripts' );
    add_action( 'admin_init', 'orbit_menu_new_plugin_custom_setting' );
}


function orbit_menu_new_plugin_admin_scripts() {
    wp_enqueue_style( 'orbit-menu-new-main-style', __DIR__ . '/assets/css/main.css' );
    wp_enqueue_script( 'orbit-menu-new-main-script', __DIR__ . '/assets/js/main.js', array( 'jquery' ), false, true );
}

function orbit_menu_new_plugin_create_page() {
    require __DIR__ . '/templates/orbit-menu-new-options.php';
}

function orbit_menu_new_plugin_custom_setting() {
    register_setting( 'orbit_menu_new_plugin_general_group', 'orbit_menu_new_plugin_name_cat' );
    add_settings_section( 'orbit_menu_new_plugin_general_section', __('Add categories to Orbit', 'orbit-menu-new'), '', 'orbit-menu-new-options' );
    add_settings_field( 'name_cat', __('Category list', 'orbit-menu-new'), 'orbit_menu_new_plugin_add_categories', 'orbit-menu-new-options', 'orbit_menu_new_plugin_general_section' );
}

function orbit_menu_new_plugin_add_categories() {

    $val = 0;    //For checkbox, attr checked
    
    $options = get_option( 'orbit_menu_new_plugin_name_cat', [] );

    $checkbox_field_1 = isset( $options['field_1'] )
        ? (array) $options['field_1'] : [];

    $categories = get_categories( [
        'taxonomy' => 'product_cat',
        'get' => 'all',
    ] );

    if( $categories ){
        foreach( $categories as $cat ){
            $val = in_array( $cat->cat_ID, $checkbox_field_1);           

            echo '<div class="cat-item">';
            echo '<label for="' .$cat->slug. '"><input name="orbit_menu_new_plugin_name_cat[field_1][]" type="checkbox" id="' .$cat->slug. '" value="' .$cat->cat_ID. '"' . checked( 1, $val, false ) .' >' .$cat->name.' - ' .$cat->cat_ID. '</label>';
            echo '</div>';
        }
    }   
}

add_shortcode( 'orbit-new', 'show_orbit_menu_new' );
function show_orbit_menu_new() {
    $res = get_option( 'orbit_menu_new_plugin_name_cat' );
    $list_cat_ID = $res['field_1'];
    $list_cat_name = array();

    $categories = get_categories( [
        'taxonomy' => 'product_cat',
        'get'           => 'all',
    ] );


    $shop_url = get_permalink( wc_get_page_id( 'shop' ) ); //url to base page woocommerce - shop

    ob_start();
    ?>

    <nav
        class="orbit element-animation">
        <a class="orbit__planet" href="<?php echo $shop_url ?>">
            <svg class="orbit__planet-img" width="64"
                height="64" version="1.1" viewBox="0 0 16.933 16.933"
                xmlns="http://www.w3.org/2000/svg">
                <rect x="-41.316" y="-12.141" width="115.83" height="28.956" ry=".0062204"
                    display="none" stroke-linecap="round" stroke-linejoin="round"
                    stroke-width=".08629" />
                <path
                    d="m12.806 15.571 2.6772-1.3667 0.02949-0.18814c0.1672-1.0668 0.51148-4.9626 0.44458-5.0312-0.02473-0.025307-0.50699 0.122-1.3188 0.4028-1.0985 0.37994-1.8378 0.64715-4.019 1.4526-0.20238 0.07473-0.39094 0.15426-0.41908 0.17674-0.03616 0.02892-0.0509 0.19535-0.05038 0.56879 4.46e-4 0.29037-0.02118 1.5961-0.04795 2.9017-0.02973 1.4497-0.03399 2.3885-0.01095 2.4119 0.02285 0.02315 1.093-0.50052 2.715-1.3285zm-3.4008 1.3024c0.023854-0.04618 0.051882-1.2343 0.069871-2.9628 0.025222-2.4237 0.021063-2.8931-0.026096-2.9316-0.063421-0.05198-3.6432-1.2728-3.6656-1.2501-0.00795 0.00814 0.00497 0.78193 0.028564 1.7197 0.023595 0.93779 0.037718 1.7104 0.031368 1.7168-0.00636 0.0064-0.53495-0.19997-1.1747-0.45876l-1.1632-0.47053-0.026229-0.52094c-0.014441-0.28651-0.040263-1.0258-0.057398-1.6427l-0.031149-1.1218-0.10493-0.040795c-0.15168-0.058978-1.8829-0.58344-2.5975-0.78694-0.49738-0.14162-0.61836-0.16347-0.65779-0.11885-0.035572 0.040237-0.039657 0.19268-0.015445 0.57727 0.082186 1.3064 0.38042 4.2573 0.43601 4.3142 0.016886 0.01725 0.49947 0.23651 1.0724 0.48718 1.4192 0.62096 2.3834 1.0597 5.2529 2.3905 1.3492 0.62566 2.4837 1.1466 2.5211 1.1576 0.039319 0.01148 0.084869-0.01262 0.10788-0.05716zm-3.8938-4.5818c-0.012463-0.21269-0.033991-0.90606-0.047846-1.5409l-0.025156-1.1542-0.55766-0.17944c-0.30672-0.098696-0.69382-0.22311-0.86022-0.27648l-0.30259-0.09704 0.027024 0.9498c0.014868 0.5224 0.038573 1.1963 0.052687 1.4975l0.025692 0.54768 0.8223 0.3207c0.45226 0.17638 0.8372 0.3203 0.85539 0.31981 0.018218-2.65e-4 0.022919-0.17492 0.010456-0.3876zm-0.9598-0.72454c-0.17205-0.0704-0.24899-0.20815-0.24899-0.44585 0-0.06774-0.03905-0.17926-0.086778-0.2478-0.34759-0.49918 0.04423-0.98894 0.55389-0.69241 0.32047 0.18649 0.44277 0.65386 0.23129 0.88405-0.060111 0.06543-0.085118 0.14772-0.085118 0.28018 0 0.28942-0.07995 0.33812-0.36429 0.22178zm10.48-2.7897c0.13322-0.050359 0.2485-0.11125 0.25616-0.13532 8e-3 -0.025023-0.31804-0.13034-0.76353-0.24656-0.4276-0.11155-0.7774-0.21021-0.7774-0.21923 0-0.00904 0.03574-0.088043 0.07943-0.17562l0.07944-0.15923 0.83024 0.22303c0.45662 0.12267 0.86241 0.22329 0.90178 0.22361 0.12252 0.00112 0.07898-0.10856-0.10418-0.26202-0.13864-0.11617-0.27451-0.17397-0.64391-0.2739-0.25752-0.069649-0.74419-0.20164-1.0815-0.29331-3.3334-0.90583-6.2325-1.6477-7.7216-1.9758-0.13196-0.029067-0.27207-0.082292-0.31136-0.11826-0.068997-0.063175-0.55372-0.85415-1.5132-2.4693-0.25272-0.42539-0.46459-0.77928-0.47081-0.78647-0.034995-0.040216-0.46967-0.05603-0.46967-0.017085 0 0.045358 0.64386 1.1705 1.5843 2.7685l0.529 0.89889 0.52635 0.11717c0.28949 0.064445 0.53447 0.12548 0.54439 0.13562 0.02939 0.030064-0.13254 0.31159-0.17924 0.31159-0.023595 0-0.32145-0.062768-0.66188-0.13947l-0.61899-0.13947-0.56557 0.1771c-1.9036 0.59612-4.1109 1.3092-4.138 1.3369-0.011062 0.011309-0.002882 0.036263 0.018079 0.055501 0.049954 0.045746 0.42547 0.14876 0.51665 0.14175 0.03906-0.00295 0.39813-0.10829 0.79795-0.23394 3.0092-0.94574 3.3379-1.0404 3.5232-1.0145 0.50171 0.070319 4.8847 1.1547 8.1199 2.0091 0.74206 0.19594 1.3768 0.35538 1.4105 0.35429 0.03373-0.00112 0.17033-0.043175 0.30354-0.093533zm-4.6028-1.4434c-0.9971-0.25224-1.9969-0.50177-2.2217-0.55453-0.22486-0.052758-0.41631-0.10143-0.42547-0.10816-0.00914-0.00671 0.016519-0.087364 0.05699-0.17918 0.058998-0.13378 0.090713-0.16288 0.15972-0.14651 1.2211 0.28968 4.4422 1.1031 4.4914 1.1342 0.05022 0.031739-0.14246 0.35412-0.19851 0.33213-0.02712-0.010656-0.86512-0.22573-1.8622-0.47795zm5.7297 0.60094c0.108-0.29048 0.38762-1.4253 0.5309-2.1546 0.21041-1.071 0.25782-1.5003 0.24044-2.1773-0.013347-0.5198-0.022451-0.57508-0.12434-0.75221-0.19383-0.33715-0.46888-0.61171-0.75915-0.75786-0.24077-0.1212-0.31709-0.13814-0.68542-0.15208-0.47123-0.017878-0.87134 0.064962-1.4644 0.30307-0.67437 0.27072-1.8258 0.94727-1.8258 1.0728 0 0.099255 3.8693 4.6978 3.9665 4.7138 0.05626 0.00925 0.09351-0.01993 0.12154-0.095381zm-8.3519-5.1206c0.32955-0.4247 0.32726-0.42037 0.22201-0.42037-0.045521 0-0.092602-0.016261-0.10463-0.036192-0.012026-0.019848 0.17877-0.23625 0.424-0.48078 0.24522-0.24453 0.44257-0.44585 0.43852-0.44739-0.0040751-0.0014229-0.17908-0.025845-0.38899-0.054109-0.37318-0.050197-0.38452-0.04919-0.51107 0.045349-0.1364 0.1019-0.80963 0.77228-0.79277 0.78947 0.00537 0.00549 0.17326 0.0392 0.37302 0.074862 0.19977 0.035674 0.37148 0.073275 0.38159 0.083604 0.010108 0.010353-0.079443 0.14358-0.19898 0.29613-0.17052 0.21759-0.23518 0.27245-0.3001 0.25463-0.045521-0.012511-0.19316-0.044332-0.32807-0.070767-0.13492-0.026394-0.26148-0.063002-0.28125-0.081266-0.019769-0.018284 0.039627-0.14212 0.13205-0.27536 0.14114-0.20344 0.15793-0.24792 0.10501-0.2782-0.034638-0.019829-0.089461-0.025622-0.12181-0.012938-0.055589 0.02182-0.46771 0.63143-0.46771 0.69182 0 0.015571 0.25688 0.084732 0.57085 0.1537l0.57085 0.1254zm4.3251-0.020997c0.59724-0.38289 1.5196-0.85692 1.9624-1.0085 0.17989-0.061599 0.46506-0.12423 0.63367-0.13918l0.30664-0.027197-0.20442-0.070431c-1.4329-0.49378-7.3118-1.5333-8.749-1.5472-0.49098-0.0047767-0.63189 0.039332-1.2805 0.40046-0.68519 0.38157-1.6135 1.1195-1.5469 1.2297 0.015445 0.02554 0.13357 0.058611 0.26252 0.07348 0.50291 0.057981 2.1448 0.2843 2.7065 0.37306l0.59137 0.093452 0.41028-0.42379c0.22566-0.23308 0.48601-0.47532 0.57853-0.53828l0.16825-0.11449 0.66867 0.087638c0.36776 0.048194 0.67327 0.092344 0.67891 0.098116 0.00567 0.0058-0.15382 0.14813-0.35435 0.31636-0.39589 0.33213-0.83381 0.78382-0.78672 0.81142 0.016141 0.00943 0.12135 0.030642 0.23378 0.047016 0.15238 0.022228 2.3616 0.46818 3.148 0.63555 0.0516 0.010995 0.26273-0.098614 0.57238-0.29713z"
                    stroke-width=".041344" />
            </svg>
        </a>
        <ul data-orbit
            class="orbit__list">
        
            <?php  

                if( $categories ):
                    foreach( $categories as $cat ):
                        if( in_array( $cat->cat_ID, $list_cat_ID ) ): 
                            $thumbnail_id = get_woocommerce_term_meta( $cat->cat_ID, 'thumbnail_id', true );
                            $image = wp_get_attachment_url( $thumbnail_id );  
            ?>
                        <li class="orbit__item">
                            <a class="orbit__link"
                                href="<?php echo get_category_link( $cat->cat_ID ); ?>">
                                <?php if( $image ) : ?>
                                    <img class="orbit__link-icon" src="<?php echo $image; ?>" alt="">
                                <?php else: ?>
                                    <div class="orbit__link-icon"></div>
                                <?php endif; ?>
                                <div class="orbit__link-text"><?php echo $cat->name; ?></div>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </nav>
    <?php
    
    return ob_get_clean();
}


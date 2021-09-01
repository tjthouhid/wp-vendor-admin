<?php
/**
 *
 * @link              https://www.tjthouhid.me
 * @since             1.0.0
 * @package           WP_VENDOR_ADMIN
 *
 * @wordpress-plugin
 * Plugin Name:       WP VENDOR ADMIN
 * Plugin URI:        https://github.com/tjthouhid/wp-vendor-admin
 * Description:       You Can Use Custom Amount to pay via tera wallet.also added custom api endpoint for this.
 * Version:           1.0.0
 * Author:            Tj Thouhid
 * Author URI:        https://www.tjthouhid.me
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-vendor-admin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_VENDOR_ADMIN', '1.0.0' );


add_action( 'admin_menu', 'wp_vendor_menus' );

function wp_vendor_menus() {
   $user = wp_get_current_user();
    $roles = ( array ) $user->roles;
    //if($roles[0] == "farmer-superhero"){
         add_menu_page( 'Wp Vendor Admin', 'Wp Vendor Admin', 1, 'wp-vendor-admin', 'wp_vendor_admin_setting', '', 66.9 );
    //}
   
}
function wp_vendor_admin_setting(){
    
    $user_ID = get_current_user_id();
    
    if(isset($_POST['update'])){

        update_user_meta( $user_ID, '_delivery', $_POST['_delivery'] );
        update_user_meta( $user_ID, '_delivery_radius', $_POST['_delivery_radius'] );
        update_user_meta( $user_ID, '_delivery_weight', $_POST['_delivery_weight'] );
        update_user_meta( $user_ID, '_delivery_cost', $_POST['_delivery_cost'] );

    }
    $delivery = get_user_meta( $user_ID, '_delivery', true );
    $delivery_weight = get_user_meta( $user_ID, '_delivery_weight', true );
    $delivery_cost = get_user_meta( $user_ID, '_delivery_cost', true );
    //tj($delivery_weight);
    $delivery_radius = get_user_meta( $user_ID, '_delivery_radius', true );
    if($delivery==""){
        $delivery = array();
    }
    ?>
    <style type="text/css">
        .btn-add-more{
            margin: 10px 6px !important;
        }
        .costing-box{
            display: block;
            margin: 10px 0px;
        }
        .hlable{
            margin-left: 5px;
            margin-right: 5px;
            width: 165px;
            display: inline-block;
            font-weight: 700;
        }
    </style>
    <script type="text/javascript">
        jQuery(function($){
            $(".btn-add-more").on("click",function(e){
                e.preventDefault();
                var $elem ='<div class="costing-box">';
                    $elem +='<input type="text" style="margin-left: 5px; margin-right: 5px; width: auto;" class="" name="_delivery_weight[]" id="delivery_radius" value="" >';
                    $elem +='<input type="text" style="margin-left: 8px; margin-right: 5px; width: auto;" class="" name="_delivery_cost[]" id="delivery_radius" value="" >';
                    $elem +='<a class="button button-secondary btn-remove-more" href="javascript:void(0)" >x</a>';
                    $elem +='</div>';
                    $(".dc-container").append($elem);

            });
            $("body").on("click",".btn-remove-more",function(e){
                $(this).closest(".costing-box").remove();
            });
        });
    </script>
    <h2>Vendor Admin SETTING</h2>
    <form action="" method="post">
    <table class="form-table">
        <tr>
            <th>
                <label for="shipping">Shipping Option</label>
            </th>
            <td>
                <label class="jmfe-checklist-label">
                    <input type="checkbox" style="margin-left: 5px; margin-right: 5px; width: auto;" class="" name="_delivery[]" id="_delivery-Drop" value="Drop" <?php if (in_array("Drop", $delivery)){ ?> checked="'checked'" <?php } ?>>Drop
                </label>
                <label class="jmfe-checklist-label">
                    <input type="checkbox" style="margin-left: 5px; margin-right: 5px; width: auto;" class="" name="_delivery[]" id="_delivery-Pickup" value="Pickup" <?php if (in_array("Pickup", $delivery)){ ?> checked="'checked'" <?php } ?>>Pickup
                </label>
                
                <p class="description">
                    Please enter your Shipping Option.
                </p>
            </td>
        </tr>
        <tr>
            <th>
                <label for="delivery_radius">Delivery Radius</label>
            </th>
            <td>
                <input type="text" style="margin-left: 5px; margin-right: 5px; width: auto;" class="" name="_delivery_radius" id="delivery_radius" value="<?php echo $delivery_radius; ?>" >
                <p class="description">
                    Please enter your Delivery Radius.
                </p>
            </td>
        </tr>
        <tr>
            <th>
                <label for="delivery_costing">Delivery Costing</label>
            </th>
            <td>
                <div class="dc-container">
                    <div class="costing-box">
                        <label class="hlable">Wight(gram)</label>
                        <label class="hlable">Amount(₹)</label>
                    </div>
                    <?php if($delivery_weight !==""):
                    foreach($delivery_weight as $dw=>$val): ?>
                    <div class="costing-box">
                        <input type="text" style="margin-left: 5px; margin-right: 5px; width: auto;" class="" name="_delivery_weight[]" id="delivery_radius" value="<?php echo $val; ?>" >
                        <input type="text" style="margin-left: 5px; margin-right: 5px; width: auto;" class="" name="_delivery_cost[]" id="delivery_radius" value="<?php echo $delivery_cost[$dw]; ?>" >
                        <?php if($dw>0){ ?>
                            <a class="button button-secondary btn-remove-more" href="javascript:void(0)" >x</a>
                        <?php }?> 
                    </div>
                    <?php 
                        endforeach;
                    else: ?>
                        <div class="costing-box">
                            <input type="text" style="margin-left: 5px; margin-right: 5px; width: auto;" class="" name="_delivery_weight[]" id="delivery_radius" value="" >
                            <input type="text" style="margin-left: 5px; margin-right: 5px; width: auto;" class="" name="_delivery_cost[]" id="delivery_radius" value="" >
                        </div>
                    <?php
                    endif;
                    ?>
                </div>
                <a class="button button-secondary btn-add-more" href="javascript:void(0)" >ADD MORE</a>

                
                
                <p class="description">
                    Please enter your Delivery wight only number will calculate is as gram like for 1kg put 1000.
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <?php submit_button( __( 'Save Changes', 'wp-vendor-admin' ),'primary','update');?>
            </td>
        </tr>
    </table>
    </form>
    <?php
}

function tj($ar,$r = false){
    echo "<pre>";
    print_r($ar);
    echo "</pre>";
    if($r){
        exit;
    }
}
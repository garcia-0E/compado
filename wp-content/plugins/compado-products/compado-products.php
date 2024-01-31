<?php

/**
 * @version 0.0.1
 */
/*
Plugin Name: Compado Products Fetch
Description: This plugin was developed to comply with Compado's technical test  
Author: Ramon Garcia
Version: 0.0.1
Author URI: http://ma.tt/
*/

function compado_enqueue_scripts()
{
    wp_enqueue_style('compado-styles', plugin_dir_url(__FILE__) . 'css/compado-styles.css');
}

add_action('wp_enqueue_scripts', 'compado_enqueue_scripts');

function compado_product_display_shortcode()
{
    $api_url = 'https://api.compado.com/v2_1/host/205/category/home/default';
    $response = wp_remote_get($api_url);

    if (!is_wp_error($response) && $response['response']['code'] == 200) {
        $body = wp_remote_retrieve_body($response);
        $partners = json_decode($body, true);
        // // Display products
        ob_start();
        ?>
        <div class="compado-products">
            <?php
            foreach ($partners['partners'] as $partner) {
                echo '<div class="compado-product">';
                echo '<img src="https://media.api-domain-compado.com/' . $partner['logo_image'] . '?d=200x120&q=100' . '">'; 
                echo '<p>' . $partner['partner_name'] . '</p>';
                echo '<p> Score: ' . $partner['score'] . ' / 10</p>';
                echo '<p> Rating ' . $partner['rating'] . ' / 5</p>';
                echo '<p>' . $partner['flag'] . ' </p>';
                echo '<p>' . $partner['introduction'] . ' </p>';
                echo '</div>';
            }
            ?>
        </div>
        <?php
        $content = ob_get_clean();

        // Append the generated content to the main post content
        return $content;
    } else {
        echo 'Error fetching data from Compado API.';
    }
}
add_shortcode('compado_product_display', 'compado_product_display_shortcode');
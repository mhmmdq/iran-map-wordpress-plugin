<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>

<!-- IranMap -->
<style>
    #iran_map_selles_list .item {
        display: inline-block; padding: 4px 8px; margin: 4px;
        background: #f1f1f1; border: 1px solid #ccc;
    }
</style>

<div>


    <select id="iran_map_stores">
        <?php foreach( $sellers['users'] as $seller ):  $store_info = dokan_get_store_info($seller->ID); $store_name = $store_info['store_name'] ?>
        <option value="<?php echo $seller->ID?>"><?php echo $store_name;?></option>
        <?php endforeach; ?>
    </select>
    <div class="button button-primary" id="iran_map_add_store">
        افزودن فروشنده
    </div>
    

    <input type="hidden" name="iran_map_sellers" value="<?php echo $sellers_in_city; ?>">

    <div>
        <h4>
            فروشندگان
        </h4>
        <hr>
        <div id="iran_map_selles_list">
        
            <?php
                $sellers_in_city = str_replace( "'",'"' , $sellers_in_city );
                if( !empty( json_decode($sellers_in_city) ) ) {
                    $sellers_in_city = json_decode($sellers_in_city);
                    foreach( $sellers_in_city as $seller_id ) {
                        $store_info = dokan_get_store_info($seller_id);
                        $store_name = $store_info['store_name'] ?>
                        <div class="item">
                            <?php echo $store_name;?>
                        </div>
                    <?php }
                }
            ?>

        </div>
    </div>




</div>

<script>

    function iran_map_add_store() {
        
        var sellers = jQuery('input[name="iran_map_sellers"]').val();
        


    }

    jQuery('#iran_map_add_store').click(function() {
        let select = jQuery('#iran_map_stores');
        let selected_store = select.val();
        let selected_store_name = select.find(':selected').text();
        let selllers = jQuery('input[name="iran_map_sellers"]').val();
        selllers = selllers.replace(/'/g,'"');
        selllers = JSON.parse(selllers);
        if(selllers.indexOf(selected_store) == -1) {
            selllers.push(selected_store);
            jQuery('input[name="iran_map_sellers"]').val(JSON.stringify(selllers));
            jQuery('#iran_map_selles_list').append(`<span class="item">${selected_store_name}</span>`);
        }

    });
</script>
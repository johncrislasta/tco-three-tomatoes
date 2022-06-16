<button id="delivery-synchronize-start-button">Start</button>
<div id="sync-status-container">
<!--    Sync status goes here...-->
</div>
<script>
    jQuery(function($){
        const $deliverySyncStartBtn = $('#delivery-synchronize-start-button');
        const $syncStatusContainer = $('#sync-status-container');

        $deliverySyncStartBtn.click(function(){
            alert('hoy gising!');
            update_sync_status('Sync in progress...');
            update_sync_status('Retrieveing delivery orders...');
            console.log(tco_ttc_js);
            $.ajax( {
                type: 'GET',
                url: tco_ttc_js.delivery_portal . 'wp-json/wc/v3/orders'
            } );
        });

        function update_sync_status( text ) {
            $syncStatusContainer.append(
                $('<p>'+text+'</p>')
            );
        }
    })
</script>
<?php


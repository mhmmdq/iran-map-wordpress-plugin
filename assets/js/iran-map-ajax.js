

var cityDefaultHtml = '';

jQuery('.iranMapAjax').click(function(e) {
    
    // e.preventDefault();

    let $this = jQuery(this);
    let term_id = $this.data('term');
    jQuery('.modal-loader-wrapper').css({'visibility': 'visible' , 'opacity': '1'});

    jQuery.ajax({
        url: ajaxUrl,
        type: 'POST',
        data: {
            action: 'iran_map_ajax_get_sellers',
            term: term_id
        },
        success:function(data){

            cityDefaultHtml = jQuery('#iranmap-categories').html();
            jQuery('#iranmap-categories').html('');
            let sellers = JSON.parse(data);
            let length = Object.keys(sellers).length - 1;
            if( length > -1 ) {
                for( let i = 0; i <= length; i++ ) {
                    jQuery('#iranmap-categories').append(`
                        <div class="iranmap-category">
                            <div class="iranmap-category-image">
                                <a class="iranmap-category-title-link" href="../../shop/?vendor_id=${sellers[i].id}&category_id=${term_id}"> 
                                    <img src="${sellers[i].image}">
                                </a>
                            </div>
                            <div class="iranmap-category-title">
                                <a class="iranmap-category-title-link" href="../../shop/?vendor_id=${sellers[i].id}&category_id=${term_id}">
                                    ${sellers[i].name}
                                </a>
                            </div>
                        </div>
                    `);
                }
            }
            else {
                jQuery('#iranmap-categories').append("<h3>فروشنده ای در این دسته وجود ندارد</h3>");
            }

            jQuery('.modal-loader-wrapper').css({'visibility': 'hidden' , 'opacity': '0'});


            

        }
    });
});

jQuery(window).on('popstate', function(e) {
    e.preventDefault();
   
    if( cityDefaultHtml != '' ) {
        window.location.reload();
    }
    
});
var iranMapData;

jQuery.ajax({
    url:ajaxUrl,
    type:'get',
    data:{
        action:'iran_map_get_cities',
    },
    success:function(data){
        
        data = JSON.parse(data);
        iranMapData = data;
        console.log(data);
        // foreach array in js
        jQuery.each(data,function(index,value){
            
            let name = value.name;
            let cities = value.cities;

            let state = jQuery('.' + name);
            state.addClass('active');

        });

        
    }
});


document.querySelector('#IranMap').addEventListener('click',function(e){
    
    let pathClass = e.target.className.baseVal;
    let stateName = pathClass.replace(' active','');
    
    if(stateName !== '') {

        let cities = iranMapData[stateName].cities;
        let cityList = jQuery('#stateData .body');
        cityList.html('');
        cityList.append('<ul>');
        jQuery.each(cities,function(index,value){
            cityList.append('<li><a href="' + value.url + '" target="_blank">' + value.title + '</a></li>');
        });
        cityList.append('</ul>');
        jQuery('#stateData').show();

    }

});

jQuery('#IranMapDataClose').click(function(){
    jQuery('#stateData').hide();
});



jQuery(function() {

    jQuery('#IranMap svg g path').hover(function() {
        var className = jQuery(this).attr('class');
        className = className.replace(' active', '');
        var parrentClassName = jQuery(this).parent('g').attr('class');
        var itemName = jQuery('#IranMap .list .' + parrentClassName + ' .' + className + ' a').html();
        if (itemName) {
            jQuery('#IranMap .list .' + parrentClassName + ' .' + className + ' a').addClass('hover');
            jQuery('#IranMap .show-title').html(itemName).css({'display': 'block'});
        }
    }, function() {
        jQuery('#IranMap .list a').removeClass('hover');
        jQuery('#IranMap .show-title').html('').css({'display': 'none'});
    });

    jQuery('#IranMap .list ul li ul li a').hover(function() {
        var className = jQuery(this).parent('li').attr('class');
        var parrentClassName = jQuery(this).parent('li').parent('ul').parent('li').attr('class');
        var object = '#IranMap svg g.' + parrentClassName + ' path.' + className;
        var currentClass = jQuery(object).attr('class');
        jQuery(object).attr('class', currentClass + ' hover');
    }, function() {
        var className = jQuery(this).parent('li').attr('class');
        var parrentClassName = jQuery(this).parent('li').parent('ul').parent('li').attr('class');
        var object = '#IranMap svg g.' + parrentClassName + ' path.' + className;
        var currentClass = jQuery(object).attr('class');
        jQuery(object).attr('class', currentClass.replace(' hover', ''));
    });

    jQuery('#IranMap').mousemove(function(e) {
        var posx = 0;
        var posy = 0;
        if (!e)
            var e = window.event;
        if (e.pageX || e.pageY) {
            posx = e.pageX;
            posy = e.pageY;
        } else if (e.clientX || e.clientY) {
            posx = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
            posy = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
        }
        if (jQuery('#IranMap .show-title').html()) {
            var offset = jQuery(this).offset();
            var x = (posx - offset.left + 25) + 'px';
            var y = (posy - offset.top - 5) + 'px';
            jQuery('#IranMap .show-title').css({'left': x, 'top': y});
        }
    });

    

});
$(window).load(function() {
    $('#tabber-tickets .tabbernav').children().each(function(){
        var el = $(this);
        if(!el.hasClass('tabberactive')){
            el.css('visibility', 'hidden');
        }
    });
});
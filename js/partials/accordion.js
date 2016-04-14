$(window).resize(function(){
   
$('#accordion-container .accordion-content').css({
'height' : 'auto',
'overflow' : 'auto'
})
   
$('#accordion-container .accordion-content').each(function(){
$(this).attr('data-accordion-height', $(this).height());
});
  
$('#accordion-container .accordion-content').css({
'height' : 0,
'overflow' : 'hidden'
})
             
$('#accordion-container .accordion-header').on('click',function() {
                 
var accordionContentCurrent = $(this).next('.accordion-content');
                 
if ($(this).hasClass('accordion-active')) {} else {
$('#accordion-container .accordion-header').removeClass('accordion-active');
 
$('#accordion-container .accordion-content').animate({
'height' : 0,
'padding' : 0
}, 300);
 
accordionContentCurrent.animate({
'height' : accordionContentCurrent.attr('data-accordion-height'),
'padding' : 3
}, 300);
 
$(this).addClass('accordion-active');
}
                 
});
 
});
 
$(window).resize();
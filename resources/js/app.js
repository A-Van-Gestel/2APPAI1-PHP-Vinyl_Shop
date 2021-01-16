require('./bootstrap');

// Make 'VinylShop' accessible inside the HTML pages
import VinylShop from "./vinylShop";
window.VinylShop = VinylShop;
// Run the hello() function
VinylShop.hello();

$(function(){
    // Add * to all required form inputs
    $('[required]').each(function () {
        $(this).closest('.form-group')
            .find('label')
            .append('<sup class="text-danger mx-1">*</sup>');
    });

    // Give Font Awesome Icons fixed width and add right margin
    $('nav i.fas').addClass('fa-fw mr-1');

    // Enable the Bootstrap tooltips
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
        html : true,
    }).on('click', '[data-toggle="tooltip"]', function () {
        // hide tooltip when you click on it
        $(this).tooltip('hide');
    });

    // Go back to the previous page
    $('#back').click(function () {
        window.history.back();
    });
});

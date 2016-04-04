


/**
 * Called when user clicks add acount button
 */
$('#add-account').click(function()
{
    console.log('hi');
    
    var file = $('#new-account-upload');
    file.replaceWith( file.clone(true) );
    $('#new-account-name').val('');
    
    $('#dialog-background').toggleClass('active');
    $('#new-account-dialog').toggleClass('active');
});

/**
 * Called when user clicks cancel button on a dialog.
 */
$('.dialog-cancel').click(function()
{
    $('#dialog-background').toggleClass('active');
    $(this).parents('.dialog').toggleClass('active');
});
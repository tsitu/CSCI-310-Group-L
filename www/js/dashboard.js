
/**
 * Called when user presses logout button.
 * Call the logout script to log user out.
 */
$('button.logout').click(function()
{
    window.location.href = "src/scripts/logout.php";
});



/**
 * Called when user clicks add acount button
 */
$('#add-account').click(function()
{    
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

/**
 * Called when user presses the add button
 */
$('.new-account-button').click(function()
{
    parseCSV();
//    location.reload();
});
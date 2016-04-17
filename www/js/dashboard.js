var id='';
var graphClickedId = 0;
var listClicked = [];

$(document).ready(function() {
    document.getElementById("graph-module").style.display = "none";
});

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
	$('#new-account-dialog').toggleClass('active');
    parseCSV();
});

/**
 * Called when user presses remove account button
 */
$('.account-remove').click(function()
{
    $('#dialog-background').toggleClass('active');
    $('#remove-account-dialog').toggleClass('active');
    id = this.id;
});

/**
 * Called when user presses remove account button
 */
$('.remove-account-confirm').click(function()
{
    $('#remove-account-dialog').toggleClass('active');
    removeAccount(accountInstitutions[id-1], accountTypes[id-1]);
});

/**
 * Called when user presses button to graph account
 */
$('.account-chart').click(function()
{
    var x = this.id;
    if (x == graphClickedId) {
        document.getElementById("graph-module").style.display = "none";
        graphClickedId = 0;
    }
    else {
        document.getElementById("graph-module").style.display = "inherit";
        graphAccount(x);
        graphClickedId = x;
    }
});

/**
 * Called when user presses button to list account transactions
 */
$('.account-list').click(function()
{
    var x = this.id;
    if (listClicked.indexOf(x) > -1) {
        $(this).toggleClass("active");
        listAccountRemove(x);
        listClicked.splice(listClicked.indexOf(x), 1);
    }
    else {
        $(this).toggleClass("active");
        listClicked.push(x);
        listAccount(x);
    }
});
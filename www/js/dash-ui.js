/**
 * dash-ui.js
 *
 * Focuses on UI component of dashboard.
 */
'use strict';


/**
 * Initalize/bind events related to UI
 */
function initUI()
{
	$(document).on('click', '#curtain', toggleSide);
    $(document).on('click', '.toggle-side', toggleSide);
    $(document).on('click', '.toggle-edit', toggleEdit);
    $(document).on('click', '.toggle-list', toggleList);
    $(document).on('click', '.toggle-graph', toggleGraph);
    $(document).on('click', '.toggle-upload', toggleUpload);
    
    //events
    $(document).on('click', '.logout', logout);
    $(document).on('click', '.rename-butotn', renameClicked);
    $(document).on('click', '.delete-button', deleteClicked);
    $(document).on('change', '#csv-file', changeClicked);
    $(document).on('click', '#csv-upload', uploadClicked);

}




/* --- ACCOUNT --- */
/**
 * Returns an account id given an element inside a 'li.account-item'
 */
function getAccountID(element)
{
    var search = $(element).parents('li.account-item');
    if (search.size() == 0)
        return -1;
    
    return +search.attr('data-id');
}



/**
 * Called when user clicks 'Upload' for a CSV file
 */
function uploadClicked(event)
{
	event.preventDefault();
    $('#csv-upload').html('Uploading...');

    upload(document.getElementById('csv-file').files[0]);
}

/**
 * Called when CSV file changes.
 */
function changeClicked()
{    
    var input = document.getElementById('csv-file');
    if (input.files.length > 1)
    {
        csvError('Upload one CSV');
        return;
    }
    
    var file = input.files[0];
    if (file === undefined || !file.name.match(/\.(csv)$/))
    {
        csvError('Invalid file type');
		return;
	}
    
    csvMessage(file.name);
    $('#csv-label').html("Change");
    $('#csv-upload').attr('disabled', false);
}








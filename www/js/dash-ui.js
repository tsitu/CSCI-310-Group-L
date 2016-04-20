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
    $(document).on('click', '.toggle-upload', toggleUpload);

    $(document).on('click', '.toggle-list', toggleList);
    $(document).on('click', '.toggle-graph', toggleGraph);
    
    //events
    $(document).on('click', '.logout', logout);
    $(document).on('click', '.rename-butotn', renameClicked);
    $(document).on('click', '.delete-button', deleteClicked);
    $(document).on('change', '#csv-file', changeClicked);
    $(document).on('click', '#csv-upload', uploadClicked);

}


/* --- TOGGLES --- */
/**
 * Show/hide curtain backdrop by toggling class 'show'
 */
function toggleCurtain()
{
    $('#curtain').toggleClass('show');
}

/**
 * Show/hide side panel by toggling class 'show'
 */
function toggleSide()
{
    toggleCurtain();
    $('.side-panel').toggleClass('show');
}

/**
 * Show/hide edit options for accounts
 */
function toggleEdit()
{
    var module = $(this).parent().siblings('.account-edit');
    if (!module.hasClass('show'))
        module.children('form')[0].reset();
    
    module.toggleClass('show');
}

/**
 * Show/hide add account from module by toggling class 'show' and 'active'
 */
function toggleUpload()
{    
    var form = $('#upload-form');
    if (!form.hasClass('show'))
        csvReset();
    
    form.toggleClass('show');
    $('.upload-toggle').toggleClass('active');
    $('.upload-header').toggleClass('active');
}

/**
 * Toggle account from transactions list
 */
function toggleList()
{
    $(this).toggleClass('active');
    var id = getAccountID(this);

    if (activeList.has(id))
        activeList.delete(id);
    else
        activeList.add(id);

    listManager.filter(filterList);
}

/**
 * 
 */
function toggleGraph()
{
    $(this).toggleClass('active');
    var series = highcharts.get( getAccountID(this) );

    if (series)
        series.setVisible( !series.visible );
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
 * Called when user clicks 'Rename' for an account
 */
function renameClicked(event)
{
	event.preventDefault();
    
    var id = getAccountID(this);
    
    var instField = $(this).siblings('.inst-field')[0];
    var typeField = $(this).siblings('.type-field')[0];
    
    var inst = instField.value;
    var type = typeField.value;
    
    if (inst.length === 0)
        inst = instField.getAttribute('placeholder');
    if (type.length === 0)
        type = typeField.getAttribute('placeholder');

    renameAccount(id, inst, type);
    $(this).parents('.account-edit').siblings('.account-name').html(inst + ' - ' + type);
}

/**
 * Called when user clicks 'Delete Account' for an account
 */
function deleteClicked(event)
{
	event.preventDefault();
    
    //TODO: confirmation check
    deleteAccount( getAccountID(this) );
    $(this).parents('.account-item').remove();
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

/**
 * Reset the add account form
 */
function csvReset()
{
    var input = $('#csv-file');
    input.wrap('<form>').closest('form')[0].reset();
    input.unwrap();
    
    $('#csv-label').html("Choose CSV");
    $('#csv-upload').html("Upload");
    csvMessage("No CSV");
    
    $('#csv-upload').attr('disabled', true);
}

/**
 * Show csv error by setting error message and disabling upload button.
 */
function csvError(str)
{
    csvMessage(str, true);
    $('#csv-upload').attr('disabled', true);
}

/**
 * Set csv message, and toggle 'error' class according to param.
 */
function csvMessage(str, error = false)
{
    var msg = $('#csv-msg');
    msg.html(str);
    
    if (error)
        msg.addClass('error');
    else
        msg.removeClass('error');
}






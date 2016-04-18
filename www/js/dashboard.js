/*
 * dashboard.js
 *
 * Main js file for dashboard interactions and manipulation
 */
"use strict";


/* CONST */
var DAY_MS = 24 * 60 * 60 * 1000;

/* VARS */
var toggle = null;

var graph = null;
var highcharts = null;
//var begPicker = null;
//var endPicker = null;

var csvInput = document.getElementById('csv-file');


/**
 * Init function
 */
$(document).ready(function()
{
    //ui
    $(document).on('click', '#curtain', hideDialog);
    $(document).on('click', '.toggle-side', toggleSide);
    $(document).on('click', '.option-edit', toggleEdit);
    $(document).on('click', '#add-toggle', toggleAdd);
    
    //events
    $(document).on('click', '.logout', logout);
    $(document).on('click', '.confirm-edit', renameAccount);
    $(document).on('click', '.delete-button', deleteAccount);
    $(document).on('change', '#csv-file', csvChange);
    $(document).on('click', '#csv-upload', csvUpload);
    
    
    //init settings
    initPicker();
    initGraph();
    
//    toggleSide();
});

/**
 * Initialize and setup date pickers
 */
function initPicker()
{
    //get buttons
    var begGraph = document.getElementById('beg-graph');
    var endGraph = document.getElementById('end-graph');
    var begTransaction = document.getElementById('beg-transaction');
    var endTransaction = document.getElementById('end-transaction');
    
    //init pickers
    var begGraphPicker = new Pikaday({
        field: begGraph,
        position: 'bottom right',
        onSelect: function(date)
        {
            //store or pass date to graph
            begGraph.innerHTML = this.toString('YYYY. M. D');
        }
    });
    
    var endGraphPicker = new Pikaday({
        field: endGraph,
        onSelect: function(date)
        {
            //store or pass date to graph
            endGraph.innerHTML = this.toString('YYYY. M. D');
        }
    });
    
    var begTransactionPicker = new Pikaday({
        field: begTransaction,
        onSelect: function(date)
        {
            //store or pass date to graph
            begTransaction.innerHTML = this.toString('YYYY. M. D');
        }
    });
    
    var endTransactionPicker = new Pikaday({
        field: endTransaction,
        onSelect: function(date)
        {
            //store or pass date to graph
            endTransaction.innerHTML = this.toString('YYYY. M. D');
            console.log(endTransaction.innerHTML);
        }
    });
    
    //default to 1 month
    var today = new Date();
    var weekAgo = new Date(today.valueOf() - (7 * DAY_MS));
    var monthAgo = new Date(today.valueOf() - (3 * 30 * DAY_MS));
    
    begGraphPicker.setDate(monthAgo);
    endGraphPicker.setDate(today);
    begTransactionPicker.setDate(monthAgo);
    endTransactionPicker.setDate(today);
}

/**
 *
 */
function initGraph()
{
    
}



/* --- UI --- */
/**
 * Show/hide curtain backdrop by toggling class 'show'
 */
function toggleCurtain()
{
    $('#curtain').toggleClass('show');
}

/**
 * Hide the currently open dialog and curtain
 */
function hideDialog()
{
    toggle();
    toggle = null;
}

/**
 * Show/hide side panel by toggling class 'show'
 * and store it as currently shown
 */
var toggleSide = function toggleSide()
{
    toggleCurtain();
    var target = $('.side-panel').toggleClass('show');
    
    toggle = toggleSide;
}

/**
 *
 */
function toggleEdit()
{
    var module = $(this).parent().siblings('.account-edit');
    if (!module.hasClass('show'))
        module.children('form')[0].reset();
    
    module.toggleClass('show');
}

/**
 * Show/hide add account form module by toggling class 'show' and 'active'
 */
function toggleAdd()
{    
    var form = $('#add-form');
    if (!form.hasClass('show'))
        csvReset();
    
    form.toggleClass('show');
    $('#add-toggle').toggleClass('active');
    $('#add-header').toggleClass('active');
}



/* --- EVENTS ---*/
/**
 * Logout user
 */
function logout()
{
    window.location = 'src/scripts/logout.php';
}

/**
 * Returns an account id given an element inside a 'li.account-item'
 */
function getAccountID(element)
{
    var search = $(element).parents('li.account-item');
    if (search.size() == 0)
        return -1;
    
    return search[0].id.split('-')[1];
}

/**
 * Rename account associated with clicked edit form.
 */
function renameAccount(event)
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
    
    
    //change
    $(this).parents('.account-edit').siblings('.account-name').html(inst + ' - ' + type);
    
    $.ajax({
        type: 'POST',
        url: 'src/scripts/rename.php',
        data: {id: id, inst: inst, type: type}
    });
}

/**
 * Delete account associated with clicked delete form
 */
function deleteAccount(event)
{
    event.preventDefault();
    
    var id = getAccountID(this);
    
    //TODO: confirmation check
    
    //remove
    $(this).parents('.account-item').remove();
    
    $.ajax({
        type: 'POST',
        url: 'src/scripts/delete.php',
        data: {id: id}
    });
}

/**
 * Called when csv file input changes.
 * Check metadata is valid and pase to `parseCSV()`
 */
function csvChange()
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

/**
 * Parse CSV into array of objects, convert to JSON, and POST to upload.php
 */
function csvUpload(event)
{
    event.preventDefault();
    $('#csv-upload').html('Uploading...');
    
    Papa.parse(csvInput.files[0], {
        newline: '\n',
        delimiter: ', ',
        header: true,
        fastMode: true,
        dynamicTyping: true,
        skipEmptyLines: true,
        complete: function(results) {
            console.log(JSON.stringify(results.data));
            
            $.ajax({
                type: "POST",
                url: "src/scripts/upload.php",
                data: {data: JSON.stringify(results.data)},
                dataType: "json",
                success: csvCallback
            });
        },
        error: function(xhr, status, error) {
          console.log(xhr.responseText);
        }
    });
}

/**
 * Callback for CSV upload post 
 */
function csvCallback(accounts)
{
    console.log('upload callback: ' + accounts);
    
    toggleAdd();
    $('#csv-upload').html("Done");
    for (var i = 0; i < accounts.length; i++)
    {
        var a = accounts[i];
        
        var item = document.getElementById('account-' + a.id);
        if (item)
            $(item).children('.account-amount').html(a.balance.toFixed(2));
        else
        {
            $('#account-list').append(newAccountItem(a.id, a.institution, a.type, a.balance.toFixed(2)));
        }
    }
}

/**
 * Return string for a new account list item with given params
 */
function newAccountItem(id, inst, type, amount)
{
    var str = ""
    + "<li id='account-" + id + "' class='account-item'>" 
    +   "<p class='account-name'>" + inst + " - " + type + "</p>"
    +   "<p class='account-amount'>" + amount + "</p>"
    +   "<div class='account-menu'>"
    +       "<button class='account-option fa fa-line-chart'></button>"
    +       "<button class='account-option fa fa-list-ul'></button>"
    +       "<button class='account-option option-edit fa fa-cog'></button>"
    +   "</div>"
    +   "<div class='account-edit'>"
    +       "<form class='edit-form'>"
    +           "<input name='new-institution' placeholder='" + inst + "'"
    +                   "class='edit-option edit-field inst-field'>"
    +           "<input name='new-type' placeholder='" + type + "'"
    +                   "class='edit-option edit-field type-field'>"
    +           "<button class='edit-option confirm-edit'>Confirm</button>"
    +           "<button class='edit-option delete-button'>Delete Account</button>"
    +       "</form>"
    +   "</div>"
    + "</li>";
    
    return str;
}
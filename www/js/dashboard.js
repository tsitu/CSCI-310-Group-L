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

var begPicker = null;
var endPicker = null;

var csvInput = document.getElementById('csv-file');


/**
 * Init function
 */
$(document).ready(function()
{
    //ui
    $('#curtain').click(hideDialog);
    $('.toggle-side').click(toggleSide);
    $('#add-toggle').click(toggleAdd);
    
    //events
    $('.logout').click(logout);
    $('#csv-file').change(csvChange);
    $('#csv-upload').click(csvUpload);
    
    //init settings
    initPicker();
});

/**
 * Initialize and setup date pickers
 */
function initPicker()
{
    //get buttons
    var beg = document.getElementById('beg-date');
    var end = document.getElementById('end-date');
    
    //init pickers
    begPicker = new Pikaday({
        field: beg,
        position: 'bottom right',
        onSelect: function(date)
        {
            //store or pass date to graph
            beg.innerHTML = this.toString('YYYY. M. D');
        }
    });
    
    endPicker = new Pikaday({
        field: end,
        onSelect: function(date)
        {
            //store or pass date to graph
            end.innerHTML = this.toString('YYYY. M. D');
        }
    });
    
    //default to 1 week
    var today = new Date();
    var weekAgo = new Date(today.valueOf() - (7 * DAY_MS));
    begPicker.setDate(weekAgo);
    endPicker.setDate(today);
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
 * Show/hide add account form module by toggling class 'show' and 'active'
 */
function toggleAdd()
{
    $('#add-form').toggleClass('show');
    
    $('#add-toggle').toggleClass('active');
    $('#add-header').toggleClass('active');
    
    csvReset();
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
            // console.log(JSON.stringify(results.data));
            
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
 *
 */
function csvCallback(accounts)
{
    toggleAdd();
    
    var template = ""
    + "<li id='account-{id}' class='account-item'>" 
    +   "<p class='account-name'>{name}</p>"
    +   "<p class='account-amount'>{amount}</p>"
    +   "<div class='account-menu'>"
    +       "<button class='account-option fa fa-line-chart'></button>"
    +       "<button class='account-option fa fa-list-ul active'></button>"
    +       "<button class='account-option fa fa-cog'></button>"
    +   "</div>"
    + "</li>";
    
    $('#csv-upload').html("Done");
    for (var i = 0; i < accounts.length; i++)
    {
        var a = accounts[i];
        
        var item = document.getElementById('account-' + a.id);
        if (item)
            $(item).children('.account-amount').html('$' + a.balance.toFixed(2));
        else
        {
            var newItem = template.replace('{id}', a.id)
                                  .replace('{name}', a.institution + ' - ' + a.type)
                                  .replace('{amount}', a.balance.toFixed(2));
            $('#account-list').append(newItem);
        }
    }
}


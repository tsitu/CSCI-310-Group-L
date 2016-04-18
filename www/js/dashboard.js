/*
 * dashboard.js
 *
 * Main js file for dashboard interactions and manipulation
 */
"use strict";


/* CONST */
var DATE_FORMAT = 'YYYY. M. D';
var DAY_MS = 24 * 60 * 60 * 1000;

var INACTIVITY_TIME = 2 * 60 * 1000; //2min


/* VARS */
var today = new Date();
var tmAgo = new Date();
    tmAgo.setMonth(today.getMonth() - 3);

var timeout = null;

/**
 * Initialize functionality
 */
$(document).ready(function()
{
    initGraph();
    initList();
    initUI();

    //autologout
    resetTimeout();
    document.onkeypress = resetTimeout;
    document.onmousemove = resetTimeout;
});


/* --- FUNCTIONS ---*/
/**
 * Logout user
 */
function logout()
{
    window.location = 'src/scripts/logout.php';
}

/**
 * Reset inactivity timeout logout.
 * Start countdown from 2min again.
 */
function resetTimeout()
{
    clearTimeout(timeout);
    timeout = setTimeout(logout, INACTIVITY_TIME);
}

/**
 * Rename account associated with clicked edit form.
 */
function renameAccount(id, inst, type)
{
    $.ajax({
        type: 'POST',
        url: 'src/scripts/rename.php',
        data: {id: id, inst: inst, type: type}
    });
}

/**
 * Delete account associated with clicked delete form
 */
function deleteAccount(id)
{
    $.ajax({
        type: 'POST',
        url: 'src/scripts/delete.php',
        data: {id: id}
    });
}

/**
 * Upload the given file
 */
function upload(file)
{
    Papa.parse(file, {
        newline: '\n',
        delimiter: ', ',
        header: true,
        fastMode: true,
        dynamicTyping: true,
        skipEmptyLines: true,
        complete: function(results)
        {
            var json = JSON.stringify(results.data);
            //console.log(json);
            
            $.ajax({
                type: "POST",
                url: "src/scripts/upload.php",
                data: {data: json},
                dataType: "json",
                success: uploadSuccess
            });
        },
        error: function(xhr, status, error)
        {
            console.log(xhr.responseText);
        }
    });
}

/**
 * Callback for CSV upload post 
 */
function uploadSuccess(accounts)
{
    toggleUpload();

    //console.log('upload callback: ' + accounts);
    
    for (var i = 0; i < accounts.length; i++)
    {
        var a = accounts[i];
        
        var item = document.getElementById('account-' + a.id);
        if (item)
            $(item).children('.account-amount').html(a.balance.toFixed(2));
        else
            $('#account-list').append(newAccountItem(a.id, a.institution, a.type, a.balance.toFixed(2)));
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
    +       "<button class='account-option toggle-graph fa fa-line-chart'></button>"
    +       "<button class='account-option toggle-list fa fa-list-ul'></button>"
    +       "<button class='account-option toggle-edit fa fa-cog'></button>"
    +   "</div>"
    +   "<div class='account-edit'>"
    +       "<form class='edit-form'>"
    +           "<input name='new-institution' placeholder='" + inst + "'"
    +                   "class='edit-option edit-field inst-field'>"
    +           "<input name='new-type' placeholder='" + type + "'"
    +                   "class='edit-option edit-field type-field'>"
    +           "<button class='edit-option rename-button'>Rename</button>"
    +           "<button class='edit-option delete-button'>Delete Account</button>"
    +       "</form>"
    +   "</div>"
    + "</li>";
    
    return str;
}
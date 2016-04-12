<<<<<<< HEAD
/*
 * dashboard.js
 *
 * Main js file for dashboard interactions and manipulation
 */
"use strict";


/* CONST */
var DAY_MS = 24 * 60 * 60 * 1000;

/* VARS */
var shown = null;

var begPicker = null;
var endPicker = null;

=======
var id='';
>>>>>>> tran

/**
 * Init function
 */
$(document).ready(function()
{
    //ui
    $('#curtain').click(hideDialog);
    $('.toggle-side').click(toggleSide);
    
    //events
    $('.logout').click(logout);
    
    
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
    if (shown !== null)
        shown.removeClass('show');
    
    $('#curtain').removeClass('show');
}

/**
 * Show/hide side panel by toggling class 'show'
 * and store it as currently shown
 */
function toggleSide()
{
    toggleCurtain();
    var target = $('.side-panel').toggleClass('show');
    
    shown = target.hasClass('show') ? target : null;
}


/* --- EVENTS ---*/
/**
 * Logout user
 */
function logout()
{
    window.location = 'src/scripts/logout.php';
}

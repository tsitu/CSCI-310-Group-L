/*
 * dashboard.js
 *
 * Main js file for dashboard interactions and manipulation
 */

/* VARS */
var shown = null;

var begPicker = null;
var endPicker = null;

/**
 * Init function
 */
$(document).ready(function()
{
    /* UI */
    $('#curtain').click(hideDialog);
    $('.toggle-side').click(toggleSide);
    
    /* Events */
    $('.logout').click(logout);
    
    initPicker();
});

/**
 * Initialize and setup date pickers
 */
function initPicker()
{
    var beg = document.getElementById('beg-date');
    var end = document.getElementById('end-date');
    
    begPicker = new Pikaday({
        field: beg,
        position: 'bottom left',
        onSelect: function(date)
        {
            //store or pass date to graph
            beg.innerHTML = this.toString('M/D/YYYY');
        }
    });
    
    endPicker = new Pikaday({
        field: end,
        onSelect: function(date)
        {
            //store or pass date to graph
            end.innerHTML = this.toString('M/D/YYYY');
        }
    });
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



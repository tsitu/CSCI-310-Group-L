/*
 * dashboard.js
 *
 * Main js file for dashboard interactions and manipulation
 */

/* VARS */
var shown = null;


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
});


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
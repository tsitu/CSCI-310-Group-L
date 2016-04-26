/*
 * dashboard.js
 *
 * Main js file for dashboard interactions and manipulation
 */
"use strict";


/* CONST */
var DEBUG = true;

var INACTIVITY_TIME = 2 * 60 * 1000; //2min

var DATE_FORMAT = 'YYYY. M. D';
var DAY_MS = 24 * 60 * 60 * 1000;

var ICON_ARROW = 'ion-ios-arrow-';

var today = new Date();
var tmAgo = new Date();
    tmAgo.setMonth(today.getMonth() - 3);


/* VARS */
var timeout = null;

var main = null;
var side = null;
var list = null;
var active = null;

/**
 * Initialize functionality
 */
$(document).ready(function()
{
    initList();
    initGraph();

    bindEvents();
    //resetTimeout();
});


/**
 * Bind handlers to all necessary UI events
 */
function bindEvents()
{
    //frequent DOM elements
    main = $('main');
    side = $('#side');
    list = $('.account-list');

    //toggles
    $(document).click(hideActive);
    $('.toggle-side').click(showSide);
    $('.toggle-upload').click(toggleUpload);
        //possibly dynamically added elements need to be bound to document
        $(document).on('click', '.toggle-drop', toggleDrop);
        $(document).on('click', '.toggle-edit', toggleEdit);
        $(document).on('click', '.toggle-list', toggleList);
        $(document).on('click', '.toggle-graph', toggleGraph);

    //actions
    $(document).on('click', '.logout', logout);
    $(document).on('click', '.rename-butotn', renameClicked);
    $(document).on('click', '.delete-button', deleteClicked);
    $(document).on('change', '#csv-file', changeClicked);
    $(document).on('click', '#csv-upload', uploadClicked);

    $(document).on('click', '.dd-sort .dropitem, .transaction-col', sortClicked);

    //auto logout
    document.onkeypress = resetTimeout;
    document.onmousemove = resetTimeout;

    fixSideScroll();
}


/**
 * Reset inactivity timeout logout.
 * Start countdown from 2min again.
 */
function resetTimeout()
{
    clearTimeout(timeout);
    // timeout = setTimeout(logout, INACTIVITY_TIME);
}

/**
 * Output given message if debugging is on
 */
function debug(msg)
{
    if (DEBUG)
        console.log(msg);
}

/**
 * Fix the side pane scrolling on mobile.
 * Disable scrolling on body if side is active.
 * Limit scrolling to the account list and stop propagtion to underlying side div.
 * Pre default scrolling on side elements (logo & upload form) not within account list.
 */
function fixSideScroll()
{
    main.on('touchmove', function(e)
    {
        if (active === side)
            e.preventDefault();
    });

    side.on('touchmove', function(e)
    {
        e.stopPropagation();
        e.preventDefault();
    });

    list.on('touchmove', function(e)
    {
        e.stopImmediatePropagation();
        e.stopPropagation();
    });
    list.on('touchstart', function(e)
    {
        e.stopImmediatePropagation();
        e.stopPropagation();

        var scroll = this.scrollTop;
        var height = this.offsetHeight;
        var content = this.scrollHeight;

        if (content <= height)
        {
            e.preventDefault();
            return;
        }

        if (scroll <= 0)
        {
            debug('adjust top');
            list.scrollTop(1);
        }
        
        if (scroll + height >= content)
        {
            debug('adjust bot');
            list.scrollTop(content - height - 1);
        }
    });
}


/* --- TOGGLES --- */
/**
 * Show/hide curtain backdrop by toggling class 'show'
 */
function toggleCurtain(e)
{
    $('#curtain').toggleClass('show');
}

/**
 * Hide any currently active 'show' elements
 */
function hideActive(e)
{
    if (!active)
        return;

    var target = $(e.target);
    if (!target.closest(active).length && !target.is(active))
    {
        if (active === side)
            main.removeClass('disable-scroll');

        active.removeClass('show');
        active = null;
    }
}

/**
 * Show the side panel
 */
function showSide(e)
{   
    e.stopPropagation();

    if (active != null && side != active)
        hideActive(e);

    active = side;
    side.addClass('show');
    main.addClass('disable-scroll');
}

/**
 * Show/hide dropdown menu
 */
function toggleDrop(e)
{
    e.stopPropagation();
    hideActive(e);

    var parent = $(this).parent();
    parent.toggleClass('show');
    
    if (parent.hasClass('show'))
        active = parent;
}

/**
 * Show/hide edit options for accounts
 */
function toggleEdit()
{
    var button = $(this);
    var module = button.parent().siblings('.account-edit');
    if (!module.hasClass('show'))
        module.children('form')[0].reset();
    
    module.toggleClass('show');
    button.toggleClass('active');
}

/**
 * Show/hide all transactions associated with clicked account
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
 * Show/hide graph balnce lines associated with clicked account
 */
function toggleGraph()
{
    $(this).toggleClass('active');
    var series = highcharts.get( getAccountID(this) );

    if (series)
        series.setVisible( !series.visible );
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
    $('.toggle-upload').toggleClass('active');
    $('.upload-header').toggleClass('active');
}



/* --- ACTIONS --- */
/**
 * Called when user clicks 'Rename' from account edit
 */
function renameClicked(e)
{
    e.preventDefault();
    
    var id = getAccountID(this);
    if (!id || id <= 0)
        return;
    
    var instField = $(this).siblings('.inst-field')[0];
    var typeField = $(this).siblings('.type-field')[0];
    
    var inst = instField.value;
    var type = typeField.value;
    
    if (inst.length === 0)
        inst = instField.getAttribute('placeholder');
    if (type.length === 0)
        type = typeField.getAttribute('placeholder');

    renameAccount(id, inst, type,
    {
        context: this,
        success: function(data)
        {
            debug('[Log] successfully renamed account with id: ' + id);
            $(this).parents('.account-edit').siblings('.account-name').html(inst + ' - ' + type);
        },
        error: function()
        {
            //show error somewhere
            debug('[Error] failed to rename account with id: ' + id);
        }
    });
}

/**
 * Called when user clicks 'Delete Account' from account edit
 */
function deleteClicked(e)
{
    var id = getAccountID(this);
    if (!id || id <= 0)
        return;

    deleteAccount(id,
    {
        context: this,
        success: function(data)
        {
            debug('[Log] successfully deleted account with id: ' + id);

            removeFromGraph(id);
            $(this).parents('.account-item').remove();
        },
        error: function()
        {
            //show error somewhere
            debug('[Error] failed to delete account with id: ' + id);
        }
    });
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
 * Called when user clicks 'Upload' for a CSV file
 */
function uploadClicked(e)
{
    e.preventDefault();
    $('#csv-upload').html('Uploading...');

    //params
    var file = document.getElementById('csv-file').files[0];
    var beg = graphBegPicker.getDate().valueOf()/1000;
    var end = graphEndPicker.getDate().valueOf()/1000;

    upload(file, beg, end, {
        context: this,
        error: function()
        {
            //show error somewhere
            debug('[Error] failed to upload csv');
        },
        success: uploadSucces
    });
}

/**
 * Callback for upload csv success.
 * 
 */
function uploadSucces(data)
{
    toggleUpload();

    for (var a of data.accounts)
    {
        var item = document.getElementById('account-' + a.id);
        var balance = a.balance.toFixed(2);

        if (item)
            $(item).children('.account-amount').html(a.balance);
        else
            $('#account-list').append( newAccountItem(a.id, a.institution, a.type, balance) );

        addToGraph(a.id, a.name, data.transactions[a.id]);
    }
}

/**
 * Called when user clicks a 
 */
function sortClicked(e)
{
    e.stopPropagation();

    var item = $(this);
    var name = item[0].childNodes[0].nodeValue;
    var sort = item.attr('data-sort');

    debug(name);

    //sort & get new direction
    var order = sortList(sort);
    var point = order ? 'up' : 'down';
    var icon  = ICON_ARROW + point;

    //change dropmain
    var label = $('.dd-sort > .toggle-drop');
    label.children('.sort-label').html(name);
    label.children('.sort-icon').removeClass().addClass('sort-icon icon ' + icon);

    //close list
    item.parents('.dropdown').removeClass('show');

    //change col
    $('.transaction-col > .icon').removeClass().addClass('icon');
    $('.transaction-col[data-sort="' + sort + '"] > .icon').addClass(icon);
}


/* --- HELPERS --- */
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
function csvMessage(str, error)
{
    if (!error)
        error = false;

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
 * Callback for CSV upload post 
 */
function csvCallback(accounts)
{
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
    + "<li id='account-" + id + "' class='account-item' data-id='" + id + "'>" 
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

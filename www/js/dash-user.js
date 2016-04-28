/**
 * dash-user.js
 *
 * Provides functions that send requests to server related to
 * modifying account data of currently logged in user.
 */
'use strict';


/* Vars */
var dataBegTime = null;
var dataEndTime = null;


/* Account List */
/**
 * Sort account list by alphabetical name
 */
function sortAccounts()
{
    $(".account-item").sort(function(a, b){

        var an = $(a).children('.account-name').text().toLowerCase();
        var bn = $(b).children('.account-name').text().toLowerCase();

        return (an < bn) ? -1 : ( (an > bn) ? 1 : 0 );

    }).appendTo('#account-list');
}


/* User Actions */
/**
 * Logout user
 */
function logout()
{
    window.location = 'src/scripts/logout.php';
}

/**
 * Rename account of given id to specified name
 *
 * @param id 		[int]		id of account to rename
 * @param inst		[string]	new institution to rename to
 * @param type 		[string] 	new type to rename to
 * @param callback 	[object]
 * 		context - of callback handlers, defaults the jQuery request object
 * 		success - handler for request completes
 * 		error   - handler for request fails 
 */
function renameAccount(id, inst, type, callback)
{
    // debug('[Log] rename account with id ' + id ' to ' + inst + ' - ' + type);

    $.ajax('src/scripts/rename.php',
    {
        type: 'POST',
        data: {id: id, inst: inst, type: type},
        error: function()
        {
            // debug('[Error] failed to rename account with id ' + id ' to ' + inst + ' - ' + type);

        	if (callback && callback.error)
        		callback.error.call(callback.context || this);
        },
        success: function(data)
        {
            // debug('[Log] successfully renamed account with id ' + id ' to ' + inst + ' - ' + type);

        	if (callback && callback.success)
        		callback.success.call(callback.context || this, data);
        }
    });
}

/**
 * Delete account with given id
 *
 * @param id 		[int]		id of account to delete
 * @param callback 	[object]
 * 		context - of callback handlers, defaults the jQuery request object
 * 		success - handler for request completes
 * 		error   - handler for request fails 
 */
function deleteAccount(id, callback)
{
    debug('[Log] delete account with id ' + id);

    $.ajax('src/scripts/delete.php',
    {
        type: 'POST',
        data: {id: id, beg: dataBegTime.valueOf()/1000, end: dataEndTime.valueOf()/1000},
        error: function()
        {
        	if (callback && callback.error)
        		callback.error.call(callback.context || this);
        },
        success: function(data)
        {
        	if (callback && callback.success)
        		callback.success.call(callback.context || this, data);
        }
    });
}

/**
 * Upload the given file
 *
 * @param file 		[File]		csv text file to parse & upload
 * @param callback 	[object]
 * 		context - of callback handlers, defaults the jQuery request object
 * 		success - handler for request completes
 * 		error   - handler for request fails 
 */
function upload(file, callback)
{
    debug('[Log] upload file ' + file.name);

    Papa.parse(file,
    {
        newline: '',
        delimiter: ', ',
        header: true,
        fastMode: true,
        dynamicTyping: true,
        skipEmptyLines: true,
        error: function()
        {
            debug('[Error] failed to upload ' + file.name);

            toggleUpload();

        	if (callback && callback.error)
        		callback.error.call(callback.context || this);
        },
        complete: function(results)
        {
            var newlist = JSON.stringify(results.data);
                    
            $.ajax('src/scripts/upload.php',
            {
                type: 'POST',
                data: {data: newlist, beg: dataBegTime.valueOf()/1000, end: dataEndTime.valueOf()/1000},
                error: function(jqXHR, textStatus, errorThrown, data)
                {
                	if (callback && callback.error)
        				callback.error.call(callback.context || this, jqXHR, textStatus, errorThrown, data);
                },
                success: function(data)
                {
                    debug('[Log] successfully uploaded ' + file.name + '. See results below');

                	if (callback && callback.success)
        				callback.success.call(callback.context || this, data);
                }
            });
        }
    });
}

/**
 * Fetch transaction data from dates [newBeg, oldBeg].
 * 
 */
function fetch(newBeg, oldBeg, callback)
{
    debug('[Log] fetch data from ' + formatDate(newBeg) + ' ~ ' + formatDate(oldBeg));

    $.ajax('src/scripts/fetch.php',
    {
        type: 'POST',
        data: {newBeg: newBeg.valueOf()/1000, oldBeg: oldBeg.valueOf()/1000},
        error: function()
        {
            if (callback && callback.error)
                callback.error.call(callback.context || this);
        },
        success: function(data)
        {
            if (callback && callback.success)
                callback.success.call(callback.context || this, data);
        }
    });
}

/**
 *
 */
function updateBudget(category, amount, callback)
{
    debug('[Log] update budget category ' + category + ' to ' + amount + ' for current month');

    var date = budgetDatePicker.getDate();

    $.ajax('src/scripts/updateBudget.php',
    {
        type: 'POST',
        data: {category: category, budget: amount, month: date.getUTCMonth() + 1, year: date.getUTCFullYear()},
        error: function()
        {
            if (callback && callback.error)
                callback.error.call(callback.context || this);
        },
        success: function()
        {
            if (callback && callback.success)
                callback.success.call(callback.context || this);
        }
    });
}

/**
 *
 */
function getBudgetAndSpending(month, year, callback)
{
    debug('[Log] get budgets across all categories for ' + year + '-' + month);

    $.ajax('src/scripts/getBudget.php',
    {
        type: 'POST',
        data: {month: month, year: year},
        error: function()
        {
            if (callback && callback.error)
                callback.error.call(callback.context || this);
        },
        success: function(data)
        {
            if (callback && callback.success)
                callback.success.call(callback.context || this, data);
        }
    });
}


/* Date Range */
/**
 * Set end of data range to given date
 */
function setDataBeg(date)
{
    if ( !dataBegTime || !(date < dataBegTime) )
    {
        dataBegTime = date;
        setListPickerBeg(date);
        setGraphPickerBeg(date);
        return;
    }

    fetch(date, dataBegTime, 
    {
        context: this,
        error: function()
        {
            //error handling
            debug('[Error] failed to fetch older data');
        },
        success: function(data)
        {
            debug('[Log] successfully fetched older data');

            updateList(data);
            updateGraph(data);

            dataBegTime = date;
            setListPickerBeg(date);
            setGraphPickerBeg(date);
        }
    });
}

/**
 * Set end of data range to given date
 */
function setDataEnd(date)
{
    dataEndTime = date;
    setListPickerEnd(date);
    setGraphPickerEnd(date);
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
    +       "<button class='account-option toggle-graph ion-arrow-graph-up-right active'></button>"
    +       "<button class='account-option toggle-list ion-ios-list active'></button>"
    +       "<button class='account-option toggle-edit icon ion-ios-gear'></button>"
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


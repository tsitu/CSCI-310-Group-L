/**
 * dash-user.js
 *
 * Provides functions that send requests to server related to
 * modifying account data of currently logged in user.
 */
'use strict';


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
    $.ajax('src/scripts/rename.php',
    {
        type: 'POST',
        data: {id: id, inst: inst, type: type},
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
    $.ajax('src/scripts/delete.php',
    {
        type: 'POST',
        data: {id: id},
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
function upload(file, beg, end, callback)
{
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
        	if (callback && callback.error)
        		callback.error.call(callback.context || this);
        },
        complete: function(results)
        {
            var json = JSON.stringify(results.data);
            //debug(json);
            
            $.ajax('src/scripts/upload.php',
            {
                type: 'POST',
                data: {data: json, beg: beg, end: end},
                dataType: "json",
                error: function(jqXHR, textStatus, errorThrown, data)
                {
                	if (callback && callback.error)
        				callback.error.call(callback.context || this, jqXHR, textStatus, errorThrown, data);
                },
                success: function(data)
                {
                	if (callback && callback.success)
        				callback.success.call(callback.context || this, data);
                }
            });
        },
    });
}

/**
 *
 */
function fetch(newBeg, oldBeg)
{
    $.ajax('src/scripts/fetch.php',
    {
        type: 'POST',
        data: {newBeg: newBeg, oldBeg: oldBeg},
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




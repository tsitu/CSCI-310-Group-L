/**
 * dash-list.js
 *
 * Focuses on transaction list funcitonality
 */
'use strict';

var listBegDate = tmAgo;
var listEndDate = today;
var listBegField = null;
var listEndField = null;
var listBegPicker = null;
var listEndPicker = null;

var listManager = null;

var listItem = "<li class='transaction-item'>" +
                    "<p class='account-id hidden'></p>" +
                    "<p class='transaction-account'></p>" +
                    "<p class='transaction-date'   ></p>" +
                    "<p class='transaction-amount' ></p>" +
                    "<p class='transaction-category'></p>" +
                    "<p class='transaction-merchant'></p>" +
                "</li>";

/**
 * Initialize transaction list
 */
function initList()
{
	initListPickers();

	listManager = new List('transaction-module', {
		valueNames: ['account-id', 'transaction-account', 'transaction-date', 'transaction-amount', 'transaction-category', 'transaction-merchant'],
		item: listItem
	});

	listManager.filter(filterList);
}


/**
 * 
 */
function filterList(item)
{
	return listActive.has( +item.values()['account-id'] );
}


/* --- PICKERS --- */
/**
 * Initialize pickers for transaction list
 */
function initListPickers()
{
	listBegField = document.getElementById('list-beg');
	listEndField = document.getElementById('list-end');

	listBegPicker = new Pikaday({
		field: listBegField,
		onSelect: listBegChanged
	});

	listEndPicker = new Pikaday({
		field: listEndField,
		onSelect: listEndChanged
	});

	listBegPicker.setDate(tmAgo);
	listEndPicker.setDate(today);
}

/**
 * Called when beg date for list is changed
 */
function listBegChanged(date)
{
	listBegDate = date;

	listEndPicker.setMinDate(date);
	listBegPicker.setStartRange(date);
	listEndPicker.setStartRange(date);

	listBegField.innerHTML = listBegPicker.toString(DATE_FORMAT);
}

/**
 * Called when end date for list is changed
 */
function listEndChanged(date)
{
	listEndDate = date;

	listBegPicker.setMaxDate(date);
	listBegPicker.setEndRange(date);
	listEndPicker.setEndRange(date);

	listEndField.innerHTML = listEndPicker.toString(DATE_FORMAT);
}


/* --- LIST --- */






/**
 * dash-list.js
 *
 * Focuses on transaction list funcitonality
 */
'use strict';

/* CONST */
var listItem =
"<li class='transaction-item' data-id='' data-account-id=''>" +
    "<p class='transaction-account'></p>" +
    "<p class='transaction-date'   ></p>" +
    "<p class='transaction-amount' ></p>" +
    "<p class='transaction-category'></p>" +
    "<p class='transaction-merchant'></p>" +
"</li>";

var fields = [
	'transaction-account',
	'transaction-date',
	'transaction-amount',
	'transaction-category',
	'transaction-merchant',

	{ data: ['id', 'account-id', 'unixtime', 'amount'] }
];



/* VARS */
var listManager = null;
var listBegField = null;
var listEndField = null;
var listBegPicker = null;
var listEndPicker = null;

var listBegTime = tmAgo.valueOf();
var listEndTime = today.valueOf();

var sortedBy = '';
var sortOrder = 'desc';


/**
 * Initialize transaction list
 */
function initList()
{
	listManager = new List('transaction-module', {
		item: listItem,
		valueNames: fields
	});

	sortList('transaction-date');

	initListPickers();
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
	listBegTime = date.valueOf();

	listEndPicker.setMinDate(date);
	listBegPicker.setStartRange(date);
	listEndPicker.setStartRange(date);

	listBegField.innerHTML = listBegPicker.toString(DATE_FORMAT);

	//change range and filter
	listBegTime = date.valueOf();
	listManager.filter(filterList);
}

/**
 * Called when end date for list is changed
 */
function listEndChanged(date)
{
	listBegPicker.setMaxDate(date);
	listBegPicker.setEndRange(date);
	listEndPicker.setEndRange(date);

	listEndField.innerHTML = listEndPicker.toString(DATE_FORMAT);

	//change range and filter
	listEndTime = date.valueOf();
	listManager.filter(filterList);
}


/* --- LIST --- */
/**
 * 
 */
function filterList(item)
{
	var shown = activeList.has( +item.values()['account-id'] );

	var time = item.values().unixtime;
	var range = listBegTime <= time && time <= listEndTime;

	return shown & range;
}


/**
 * Return true for asc, false for desc.
 */
function sortList(col)
{
	if (sortedBy === col)
		sortOrder = (sortOrder === 'asc') ? 'desc' : 'asc';
	else
	{
		sortedBy = col;

		//for date & amount, default to desc
		if (['transaction-date', 'trasaction-amount'].includes(col))
			sortOrder = 'desc';
	}

	if (sortedBy === 'transaction-amount')
		listManager.sort('amount', {order: sortOrder});
	else
		listManager.sort(sortedBy, {order: sortOrder});

	return sortOrder === 'asc';
}





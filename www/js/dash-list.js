/**
 * dash-list.js
 *
 * Focuses on transaction list funcitonality
 */
'use strict';

/* CONST */
var listItem = ""
+ "<li class='transaction-item' data-id='' data-account-id=''>"
+     "<p class='transaction-account'></p>" 
+     "<p class='transaction-date'   ></p>" 
+     "<p class='transaction-amount' ></p>" 
+     "<p class='transaction-merchant'></p>"
+     "<p class='transaction-category'></p>"  
+ "</li>";

var listFields = [
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
		valueNames: listFields
	});

	sortList('transaction-date');

	initListPickers();
}

/* --- LIST --- */
/**
 * Filter function given to listManager.
 * Given an item, check that its id is in the activeList & that date is within range.
 */
function filterList(item)
{
	var shown = activeList.has( +item.values()['account-id'] );

	var time = item.values().unixtime;
	var range = listBegTime <= time && time <= listEndTime;

	return shown && range;
}


/**
 * Sort the list by the given column name.
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
		if (['transaction-date', 'trasaction-amount'].indexOf(col) >= 0)
			sortOrder = 'desc';
	}

	if (sortedBy === 'transaction-amount')
		listManager.sort('amount', {order: sortOrder});
	else
		listManager.sort(sortedBy, {order: sortOrder});

	return sortOrder === 'asc';
}

/**
 * Update transaction accounts in list when an account is renamed.
 */
function renameListAccount(id, name)
{
	for (var item of listManager.items)
	{
		var values = item.values();

		if (id === +values['account-id'])
		{
			values['transaction-account'] = name;
			item.values( values );
		}
	}
}

/**
 * Remove transactions with given account id
 */
function removeFromList(id)
{
	listManager.remove('account-id', id);
}

/**
 * Update the graph with newly fetched data points
 */
function updateList(data)
{
	var items = [];
	for (var id in data)
	{
		var list = data[id];
		for (var ta of list)
			items.push( getItem(ta.id, ta['account_id'], ta.institution, ta.type, ta.unixtime, ta.amount, ta.category, ta.merchant) );
	}

	listManager.add(items, function(items){
		listManager.sort(sortedBy, {order: sortOrder});
	});
}

/**
 *
 */
function refreshList(data)
{
	listManager.clear();

	var items = [];
	for (var id in data)
	{
		var list = data[id];
		for (var ta of list)
			items.push( getItem(ta.id, ta['account_id'], ta.institution, ta.type, ta.unixtime, ta.amount, ta.category, ta.merchant) );
	}

	listManager.add(items, function(items){
		listManager.sort(sortedBy, {order: sortOrder});
	});
	listManager.filter(filterList);
}

/**
 * Return transaction object recognized by List.js
 */
function getItem(id, aid, inst, type, unixtime, amount, category, merchant)
{
	return {
		'transaction-account'	: inst + ' - ' + type,
		'transaction-date'		: formatDate(new Date(unixtime * 1000)),
		'transaction-amount'	: amount.toFixed(2),
		'transaction-category'	: category,
		'transaction-merchant'	: merchant,

		'id'		: id + '', 
		'account-id': aid + '', 
		'unixtime'	: (unixtime * 1000) + '', 
		'amount'	: amount + ''
	};
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
		onSelect: setDataBeg
	});

	listEndPicker = new Pikaday({
		field: listEndField,
		onSelect: setDataEnd
	});

	listEndPicker.setMaxDate(today);
}

/**
 * Adjust range beg picker for list to given date.
 * Filter the list to apply new range 
 */
function setListPickerBeg(date)
{
	listEndPicker.setMinDate(date);
	listBegPicker.setStartRange(date);
	listEndPicker.setStartRange(date);
	listBegPicker.setDate(date, true); //dont trigger callback

	listBegField.innerHTML = formatDate(date);

	//change range and filter
	listBegTime = date.valueOf();
	listManager.filter(filterList);
}

/**
 * Adjust range end picker for list to given date.
 * Filter the list to apply new range
 */
function setListPickerEnd(date)
{
	listBegPicker.setMaxDate(date);
	listBegPicker.setEndRange(date);
	listEndPicker.setEndRange(date);
	listEndPicker.setDate(date, true); //dont trigger callback

	listEndField.innerHTML = formatDate(date);

	//change range and filter
	listEndTime = date.valueOf();
	listManager.filter(filterList);
}
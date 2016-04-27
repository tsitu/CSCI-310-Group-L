/**
 * dash-budget.js
 *
 * Focuses on on budget functionality
 */
'use strict';


/* VARS */
var budgetDateField = null;
var budgetDatePicker = null;


/**
 *
 */
function initBudget()
{
	initBudgetPicker();
}

/**
 *
 */
function initBudgetPicker()
{
	budgetDateField = document.getElementById('budget-cal');

	budgetDatePicker = new Pikaday({
		field: budgetDateField,
		onSelect: budgetDateChanged,
		disableDayFn: function(date)
		{
			return date.getUTCDate() != 1;
		}
	});

	var test = new Date(today.getUTCFullYear(), today.getUTCMonth());
	debug(test);
	budgetDatePicker.setDate(test); //dont trigger callback
}

/**
 *
 */
function budgetDateChanged(date)
{
	//call budget functions here

	budgetDateField.innerHTML = date.getUTCFullYear() + '. ' + (date.getUTCMonth() + 1);
}
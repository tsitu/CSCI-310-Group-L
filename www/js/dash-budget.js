/**
 * dash-budget.js
 *
 * Focuses on on budget functionality
 */
'use strict';


/* VARS */
var budgetDate = null;
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

	var current = new Date(today.getUTCFullYear(), today.getUTCMonth());
	budgetDatePicker.setDate(current); //dont trigger callback
}

/**
 *
 */
function budgetDateChanged(date)
{
	//call budget functions here

	var year = date.getUTCFullYear();
	var month = (date.getUTCMonth() + 1);
	budgetDateField.innerHTML = year + '. ' + month;

	var current = (year === today.getUTCFullYear() && month === today.getUTCMonth() + 1);
	$('.category-amount').prop('disabled', !current);
}
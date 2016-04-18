/**
 * dash-graph.js
 *
 * Focuses on on graph functionality
 */
'use strict';

var graphBegDate = tmAgo;
var graphEndDate = today;
var graphBegField = null;
var graphEndField = null;
var graphBegPicker = null;
var graphEndPicker = null;


/**
 * Initialize graph
 */
function initGraph()
{
	graphBegField = document.getElementById('graph-beg');
	graphEndField = document.getElementById('graph-end');

	graphBegPicker = new Pikaday({
		field: graphBegField,
		onSelect: graphBegChanged
	});

	graphEndPicker = new Pikaday({
		field: graphEndField,
		onSelect: graphEndChanged
	});

	graphBegPicker.setDate(tmAgo);
	graphEndPicker.setDate(today);
}

/**
 * Called when beg date for list is changed
 */
function graphBegChanged(date)
{
	graphBegDate = date;

	graphEndPicker.setMinDate(date);
	graphBegPicker.setStartRange(date);
	graphEndPicker.setStartRange(date);

	graphBegField.innerHTML = graphBegPicker.toString(DATE_FORMAT);
}

/**
 * Called when end date for graph is changed
 */
function graphEndChanged(date)
{
	graphEndDate = date;

	graphBegPicker.setMaxDate(date);
	graphBegPicker.setEndRange(date);
	graphEndPicker.setEndRange(date);

	graphEndField.innerHTML = graphEndPicker.toString(DATE_FORMAT);
}





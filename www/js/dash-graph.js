/**
 * dash-graph.js
 *
 * Focuses on on graph functionality
 */
'use strict';

/* CONSTS */
var graphColors = [];
var hc_options = {
	title: { text: null },
	chart: {
		zoomType: '' //disable zooming
	},
	//colors: graphColors,
	legend: {
		useHTML: true,
		layout: 'vertical',
		align: 'right',
		verticalAlign: 'top',

		y: 30,
		itemMarginBottom: 10
	},
	xAxis: {
		title: { text: 'Date' },
		type: 'datetime'
	},
	yAxis: {
		title: { text: 'Balance' },
		gridLineDashStyle: 'longdash'
	}
};


/* VARS */
var graphBegDate = tmAgo;
var graphEndDate = today;
var graphBegField = null;
var graphEndField = null;
var graphBegPicker = null;
var graphEndPicker = null;

var highcharts = null;


/**
 * Initialize graph
 */
function initGraph()
{
	initGraphPickers();
	initHighcharts();
}

/**
 *
 */
function initHighcharts()
{
	var series = [];
	for (var [id, list] of transactions.entries())
	{
		var data = [];
		for (var i = list.length-1; i >= 0; --i)
		{
			var ta = list[i];
			var part = ta.timeStr.split(' ');
			var date = Date.UTC(part[0], part[1], part[2], part[3], part[4], part[5]);

			data.push([date, ta.balance]);
		}

		series.push({
			id: id,
			name: accounts.get(id).name,
			data: data
		});
	}

	hc_options.series = series;
	highcharts = Highcharts.chart('graph', hc_options);
}

/**
 *
 */
function initGraphPickers()
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





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
		zoomType: '',
		events: {
			selection: zoomed
		}
	},
	//colors: graphColors,
	legend: {
		useHTML: true,
		floating: false,
		layout: 'horizontal',
		align: 'left',
		verticalAlign: 'top',

		y: 30,
		itemMarginBottom: 10
	},
	yAxis: {
		id: 'y',
		title: { text: null },
		gridLineDashStyle: 'longdash',
		min: 0
	},
	xAxis: {
		id: 'x',
		type: 'datetime',
		dateTimeLabelFormats: { 
			day: '%b %e<br/>%Y'
		},
		tickInterval: 7 * DAY_MS
	},
	tooltip: {
		useHTML: true,
		formatter: function()
		{
			return ''
			+ Highcharts.dateFormat('%A <br/> %b %e, %Y', this.x) + '<br/><br/>'
			+ 'Balance: <b>' + this.y + '</b>';
		}
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
	initHighcharts();
	initGraphPickers();
}


/* --- HIGHCHARTS --- */
/**
 * Initialize Highcharts graph.
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
			data.push([ta.unixtime * 1000, ta.balance]);
		}

		series.push({
			id: id,
			name: accounts.get(id).name,
			data: data
		});
	}

	hc_options.series = series;
	highcharts = Highcharts.chart('graph', hc_options);

	console.log(highcharts.series)
}

/**
 * Called when user zooms in the chart.
 * Change the picker dates accordingly.
 */
function zoomed(event)
{
	if (!event.xAxis)
		return;

	var range = event.xAxis[0];

	graphBegPicker.setDate(new Date(range.min));
	graphEndPicker.setDate(new Date(range.max));
}

/**
 * Set the min value of the graph's x-axis (datetime) to the given unix timestamp.
 */
function setGraphMin(min)
{
	var xAxis = highcharts.get('x');
	var range = xAxis.getExtremes();
	xAxis.setExtremes(min, range.max);
}

/**
 * Set the max value of the graph's x-axis (datetime) to the given unix timestamp.
 */
function setGraphMax(max)
{
	var xAxis = highcharts.get('x');
	var range = xAxis.getExtremes();
	xAxis.setExtremes(range.min, max);
}

/**
 * Remove data points associated with given account id.
 */
function removeFromGraph(id)
{
	var series = highcharts.get(id);
	if (series)
		series.remove();
}

/**
 * Add given [id, series] to the graph
 */
function updateGraph(id, name, list)
{
	var data = [];
	for (var i = list.length-1; i >= 0; --i)
	{
		var ta = list[i];
		var part = ta.timeStr.split(' ');
		var date = Date.UTC(part[0], part[1]-1, part[2], part[3], part[4], part[5]);

		data.push([date, ta.balance]);
	}

	var series = highcharts.get(id);
	if (series)
	{
		for (var point of data)
			series.addPoint(point);
	}
	else
	{
		highcharts.addSeries({
			id: id,
			name: name,
			data: data
		});
	}
}

/**
 * Update series on graph when an account is renamed
 */
function renameGraphAccount(id, name)
{
	highcharts.get(id).update({name: name}, false);
	highcharts.redraw();
}


/* --- PIKADAY --- */
/**
 * Initialize Pikaday pickers for the graph
 */
function initGraphPickers()
{
	graphBegField = document.getElementById('graph-beg');
	graphEndField = document.getElementById('graph-end');

	graphBegPicker = new Pikaday({
		field: graphBegField,
		onSelect: graphPickerBegChanged
	});

	graphEndPicker = new Pikaday({
		field: graphEndField,
		onSelect: graphPickerEndChanged
	});

	graphBegPicker.setDate(tmAgo);
	graphEndPicker.setDate(today);
}

/**
 * Callback for graph's beg picker date change
 */
function graphPickerBegChanged(date)
{
	graphBegDate = date;
	setGraphMin(date.valueOf());

	graphEndPicker.setMinDate(date);
	graphBegPicker.setStartRange(date);
	graphEndPicker.setStartRange(date);

	graphBegField.innerHTML = date.getUTCFullYear() + '. ' + (date.getUTCMonth() + 1) + '. ' + date.getUTCDate();
}

/**
 * Callback for graph's end picker date change
 */
function graphPickerEndChanged(date)
{
	graphEndDate = date;
	setGraphMax(date.valueOf());

	graphBegPicker.setMaxDate(date);
	graphBegPicker.setEndRange(date);
	graphEndPicker.setEndRange(date);

	graphEndField.innerHTML = date.getUTCFullYear() + '. ' + (date.getUTCMonth() + 1) + '. '  + date.getUTCDate();
}






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
		backgroundColor: '#EEEEEE',
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

	//totals
	for (var [name, set] of Object.entries(totals))
	{
		var data = [];
		for (var [unixtime, balance] of Object.entries(set))
			data.push([+unixtime * 1000, balance]);

		series.push({
			id: name,
			name: name,
			marker: {enabled: true},
			data: data
		});
	}

	//accounts
	for (var [id, list] of tMap.entries())
	{
		var data = [];
		for (var i = list.length-1; i >= 0; --i)
		{
			var ta = list[i];
			data.push([ta.unixtime * 1000, ta.balance]);
		}

		series.push({
			id: id,
			name: aMap.get(id).name,
			marker: { enabled: true },
			data: data,
			step: 'left'
		});
	}

	hc_options.series = series;
	highcharts = Highcharts.chart('graph', hc_options);

	//for GC
	aMap = null;
	tMap = null;
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
 * Update series on graph when an account is renamed
 */
function renameGraphAccount(id, name)
{
	highcharts.get(id).update({name: name}, false);
	highcharts.redraw();
}

/**
 * Update the graph with newly fetched data points
 */
function updateGraph(data)
{
	for (var [id, list] of Object.entries(data))
	{
		var series = highcharts.get(+id);

		for (var ta of list)
		{
			series.addPoint({
				x: ta.unixtime * 1000,
				y: ta.balance,
				marker: {
					enabled: true
				}
			}, false);
		}
	}

	highcharts.redraw();
}

/** 
 *
 */
function refreshGraph(data)
{
	for (var [id, list] of Object.entries(data))
	{
		var existing = highcharts.get(+id);
		if (existing)
			existing.remove();

		var name;
		var data = [];
		for (var i = list.length-1; i >= 0; --i)
		{
			var ta = list[i];
			data.push([ta.unixtime * 1000, ta.balance]);

			name = ta.institution + ' - ' + ta.type;
		}

		highcharts.addSeries({
			id: id,
			name: name,
			marker: {
				enabled: true,
			},
			data: data
		}, false);
	}

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
		onSelect: setDataBeg
	});

	graphEndPicker = new Pikaday({
		field: graphEndField,
		onSelect: setDataEnd
	});

	graphEndPicker.setMaxDate(today);
}

/**
 * Adjust range beg picker for graph to given date.
 * Change graph's extremes to apply new range
 */
function setGraphPickerBeg(date)
{
	graphEndPicker.setMinDate(date);
	graphBegPicker.setStartRange(date);
	graphEndPicker.setStartRange(date);
	graphBegPicker.setDate(date, true); //dont trigger callback

	graphBegField.innerHTML = formatDate(date);

	//change graph span
	setGraphMin(date.valueOf());
}

/**
 * Adjust range beg picker for graph to given date.
 * Change graph's extremes to apply new range
 */
function setGraphPickerEnd(date)
{
	graphBegPicker.setMaxDate(date);
	graphBegPicker.setEndRange(date);
	graphEndPicker.setEndRange(date);
	graphEndPicker.setDate(date, true); //dont trigger callback

	graphEndField.innerHTML = formatDate(date);

	//change graph span
	setGraphMax(date.valueOf());
}


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
			marker: {
				enabled: true,

			},
			data: data
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
	for (var id of accounts)
	{
		var list = data[id];
		if (list)
		{
			var series = highcharts.get(id);

			for (var ta of list)
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
		onSelect: setGraphPickerEnd
	});

	setGraphPickerBeg(tmAgo);
	setGraphPickerEnd(today);
	graphEndPicker.setMaxDate(today);

	graphBegPicker.setDate(tmAgo, true); //dont trigger callback
	graphEndPicker.setDate(today, true); //dont trigger callback
}

/**
 * Callback for graph's beg picker date change
 */
function graphPickerBegChanged(date)
{
	if ( !(date < dataBegTime) )
	{
		setGraphPickerBeg(date);
		return;
	}

	//if older than whats available
	fetch(date, dataBegTime, 
	{
		context: this,
		success: function()
		{
			setGraphPickerBeg(date);
		}
	});
}

/**
 *
 */
function setGraphPickerBeg(date)
{
	setGraphMin(date.valueOf());

	graphEndPicker.setMinDate(date);
	graphBegPicker.setStartRange(date);
	graphEndPicker.setStartRange(date);

	graphBegField.innerHTML = formatDate(date);
}

/**
 *
 */
function setGraphPickerEnd(date)
{
	setGraphMax(date.valueOf());

	graphBegPicker.setMaxDate(date);
	graphBegPicker.setEndRange(date);
	graphEndPicker.setEndRange(date);

	graphEndField.innerHTML = formatDate(date);
	debug(graphEndField.innerHTML);
}


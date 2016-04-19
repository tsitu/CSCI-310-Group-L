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

var highcharts = null;


/**
 * Initialize graph
 */
function initGraph()
{
	initGraphPickers();

	//graph
	var graphDiv = document.getElementById('graph');

	var data = [];
	for (var i = 0; i < initMap.length; i++)
	{
		var tlist = initMap[i];

		var dlist = [];
		for (var j = 0; j < tlist.length; j++)
		{
			var d = tlist[j];
			var newdate = new Date(d.t.date.substring(0,4), d.t.date.substring(5,7), d.t.date.substring(8,10), d.t.date.substring(11,13), d.t.date.substring(14,16), d.t.date.substring(17,19));
			dlist.push([newdate.getTime()/1000, d.balance]);
		}

		var series = {
			name: tlist[0].institution + ' - ' + tlist[0].type,
			lineWidth: 4,
			marker: { radius: 4 },
			data: dlist
		};

		data.push(series);
	}

	highcharts = new Highcharts.Chart({
		chart: { renderTo: graph },
		title: { text: '' },
		subtitle: { text: '' },

		xAxis: {
			type: 'datetime',
			tickInterval: DAY_MS,
			tickWidth: 0,
			gridLineWidth: 1,
			labels: {
                align: 'left',
                x: 3,
                y: -3
            }
		},
		yAxis: [{ // left y axis
            title: { text: '' },
            labels: {
                align: 'left',
                x: 3,
                y: 16,
                format: '{value:.,0f}'
            },
            showFirstLabel: false
        }],
        legend: {
            align: 'left',
            verticalAlign: 'top',
            y: 20,
            floating: true,
            borderWidth: 0
        },
        plotOptions: {
            series: {
                cursor: 'pointer',
                marker: { lineWidth: 1 }
            }
        },
        series: data
	});
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





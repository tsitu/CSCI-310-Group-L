<?php

require_once __DIR__ . '/src/inc/queries.php';

?>

<!DOCTYPE html>
<html>

<head>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>

<!-- Additional files for the Highslide popup effect -->
<script src="https://www.highcharts.com/samples/static/highslide-full.min.js"></script>
<script src="https://www.highcharts.com/samples/static/highslide.config.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="https://www.highcharts.com/samples/static/highslide.css" />
</head>

<body>
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<script type = "text/javascript">
var transactions = [];
var accountz = [];
</script>

<?php
$account = getAccount(76);
$transactions = getTransactions(3, $account->getId());

foreach ($transactions as $tx)
{
    $timestamp = $tx->timestamp;
    $amount = $tx->amount;
    $accountId = $tx->accountId;
?>

<script type="text/javascript">
var accountInstitution = "<?php echo $account->getInstitution() ?>";
var accountType = "<?php echo $account->getType() ?>";
var accountId = "<?php echo $accountId ?>";

//accountInstitutions.push(accountInstitution);
//accountTypes.push(accountType);

var timestamp = "<?php echo $timestamp ?>";
var amount = "<?php echo $amount ?>";
transactions.push([+timestamp, +amount]);
accountz.push([+timestamp, +accountId]);
</script>

<?php
}
?>

<script>
console.log(transactions);
console.log(accountz);
</script>

<script>
	$(function () {
        var chart = new Highcharts.Chart({

        	chart: {
        		renderTo: container
        	},

            title: {
                text: 'Account Graph'
            },

            subtitle: {
                text: accountInstitution + " " + accountType
            },

            xAxis: {
            	type: 'datetime',
                tickInterval: 60 * 1000, // one minute
                tickWidth: 0,
                gridLineWidth: 1,
                labels: {
                    align: 'left',
                    x: 3,
                    y: -3
                }
            },

            yAxis: [{ // left y axis
                title: {
                    text: null
                },
                labels: {
                    align: 'left',
                    x: 3,
                    y: 16,
                    format: '{value:.,0f}'
                },
                showFirstLabel: false
            }, { // right y axis
                linkedTo: 0,
                gridLineWidth: 0,
                opposite: true,
                title: {
                    text: null
                },
                labels: {
                    align: 'right',
                    x: -3,
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

            tooltip: {
                shared: true,
                crosshairs: true
            },

            plotOptions: {
                series: {
                    cursor: 'pointer',
                    marker: {
                        lineWidth: 1
                    }
                }
            },

            series: [{
                name: 'Total net worth',
                lineWidth: 4,
                marker: {
                    radius: 4
                },
                data: transactions
            }, 
            {
	            name: 'Account ID',
	            lineWidth: 4,
                marker: {
                    radius: 4
                },
                data: accountz
	        }]
        });
	});

	/*$(function () {

	    // Get the CSV and create the chart
	    $.getJSON('https://www.highcharts.com/samples/data/jsonp.php?filename=analytics.csv&callback=?', function (csv) {

	        //$('#container').highcharts({
	        var chart = new Highcharts.Chart({

	        	chart: {
	        		renderTo: container
	        	},

	            data: {
	                csv: csv
	            },

	            title: {
	                text: 'Daily visits at www.highcharts.com'
	            },

	            subtitle: {
	                text: 'Source: Google Analytics'
	            },

	            xAxis: {
	                tickInterval: 7 * 24 * 3600 * 1000, // one week
	                tickWidth: 0,
	                gridLineWidth: 1,
	                labels: {
	                    align: 'left',
	                    x: 3,
	                    y: -3
	                }
	            },

	            yAxis: [{ // left y axis
	                title: {
	                    text: null
	                },
	                labels: {
	                    align: 'left',
	                    x: 3,
	                    y: 16,
	                    format: '{value:.,0f}'
	                },
	                showFirstLabel: false
	            }, { // right y axis
	                linkedTo: 0,
	                gridLineWidth: 0,
	                opposite: true,
	                title: {
	                    text: null
	                },
	                labels: {
	                    align: 'right',
	                    x: -3,
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

	            tooltip: {
	                shared: true,
	                crosshairs: true
	            },

	            plotOptions: {
	                series: {
	                    cursor: 'pointer',
	                    point: {
	                        events: {
	                            click: function (e) {
	                                hs.htmlExpand(null, {
	                                    pageOrigin: {
	                                        x: e.pageX || e.clientX,
	                                        y: e.pageY || e.clientY
	                                    },
	                                    headingText: this.series.name,
	                                    maincontentText: Highcharts.dateFormat('%A, %b %e, %Y', this.x) + ':<br/> ' +
	                                        this.y + ' visits',
	                                    width: 200
	                                });
	                            }
	                        }
	                    },
	                    marker: {
	                        lineWidth: 1
	                    }
	                }
	            },

	            series: [{
	                name: 'All visits',
	                lineWidth: 4,
	                marker: {
	                    radius: 4
	                }
	            }, {
	                name: 'New visitors'
	            }]
	        });
	    });

	});*/
</script>
</body>
</html>
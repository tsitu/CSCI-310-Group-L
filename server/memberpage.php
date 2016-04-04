<?php require('includes/config.php'); 

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: index.php'); } 

//define page title
$title = 'Members Page';

//include header template
require('layout/header.php'); 
?>

<html>

<head>
	<title>Stock Portfolio</title>

	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<meta content="utf-8" http-equiv="encoding">
	
	<link href="vendor/twbs/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
	<link href="jquery-ui-1.11.4.custom/jquery-ui.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="index.css">

	<script src = "src/SPServerCommunicator.js"></script>
	<script src = "src/updateTracker.js"></script>
	<script src = "src/uploadCSV.js"></script>
	<script src = "PapaParse/papaparse.js"></script>
	<script src = "js/drawChart.js"></script>
	<script src = "js/nasdaqlist.js"></script>
	<script src = "js/nyselist.js"></script>
	<script src = "js/search.js"></script>

	<script src="https://code.jquery.com/jquery-2.2.1.min.js"></script>
	<script src="https://code.highcharts.com/stock/highstock.js"></script>
	<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
</head>

<script type="text/javascript">
  function test(text) {
    var table = document.getElementById("stockWatchlist");

    //confirm that this ticker isn't currently in the table
    for(i = 0; i < table.rows.length; i++)
    {
    	if(table.rows[i].cells[0].innerHTML == text.value)
    		return;
    }

    //Confirm that ticker exists
    if (exchangeTickerData.indexOf(text.value) >= 0)  {
	    var row = table.insertRow(table.rows.length);
	    row.insertCell(0).innerHTML = text.value;
	    row.insertCell(1).innerHTML = tickerToNameMap[text.value];
	    row.insertCell(2).innerHTML = "<input type=\"checkbox\" checked=\"checked\">";
	    row.insertCell(3).innerHTML = "<button onClick=deleteWatchlistRow(this)>X</button>";
	}
  }

  function deleteWatchlistRow(r)
  {
  	var i = r.parentNode.parentNode.rowIndex;
  	document.getElementById("stockWatchlist").deleteRow(i);
  }

  function autoFillName(ticker) {
  	if (ticker.value.length != 0) {
  		if (exchangeTickerData.indexOf(ticker.value) >= 0)  {
  			document.getElementById("companyname").value = tickerToNameMap[ticker.value];
  		}
  	}
  }

  function buy(ticker, quantity) {
  	if (exchangeTickerData.indexOf(ticker.value) >= 0)  {
  		console.log("AYY");
  		if (quantity.value.length != 0) {
  			console.log("AYY2");
  		}
  	}
  	else {
  		console.log("No.");
  	}
  }

  function portfolioGraphTab() {

  }

  function watchlistGraphTab() {
  	var table = document.getElementById("stockWatchlist");
  	var toBeGraphed = [];

  	for (i = 1; i < table.rows.length; i++) {
    	if (table.rows[i].cells[2].childNodes[0].checked) {
    		toBeGraphed.push(table.rows[i].cells[0].innerHTML);
    	}
    }

    //call backend voodoo
    if (toBeGraphed.length > 0) {
    	//console.log(toBeGraphed);
    	updateGraph(toBeGraphed);
    }
  }
</script>

<body>
	<div id="accountInformation">
		<h2 style="text-align: center">Account Information</h2>
		Hello <?php echo $_SESSION['name'];?>!
		<a href = "logout.php">Log Out</a>
		<div>
			<?php date_default_timezone_set('America/New_York');echo date('h:i:s a', time());?>
		</div>
		<div>
			<strong>Account Balance: </strong> <?php echo $_SESSION['balance'];?>
		</div>
		<div>
			<strong>Portfolio Value: </strong> <?php echo $_SESSION['balance'];?>
		</div>
		Lost? Try the user manual <a href="https://docs.google.com/document/d/1BoRsegR4Jx4DU01mAq6f2r0nwVT5yb9BXSYRV_DRQUU/pub">here!</a>
	</div>

	<div class="search">
	  <div class="row">
	        <div class="col-md-6">
	            <div id="custom-search-input">
	                <div class="input-group col-md-12">
	                    <input type="text" class="form-control input-lg" placeholder="Search by ticker" id="searchWidgetAuto"/>
	                    <span class="input-group-btn">
	                        <button class="btn btn-info btn-lg" id="addButton" type="button" onclick="test(searchWidgetAuto)">
	                            <i class="glyphicon glyphicon-plus"></i>
	                        </button>
	                    </span>
	                </div>
	            </div>
	        </div>
	  </div>
	</div>

	<div id="graphTabs">
		<button onClick=portfolioGraphTab() id="graphPortfolio">Graph Portfolio</button>
		<button onClick=watchlistGraphTab() id="graphWatchlist">Graph Watchlist</button>
	</div>

	<div class="watchlist">
 	<h2 style="text-align: center">Stock Watchlist</h2>
 	<p>Details regarding your watched stocks. </p>
	<table id="stockWatchlist" class="table table-striped">
	<thead>
		<tr>
			<td>Ticker</td>
			<td>Company Name</td>
			<td>Graph</td>
			<td>Remove</td>
		</tr>
		</thead>
	</table>
	</div>

	<div id="">

	<div id="chart"></div>

	<br>

	<div class="tracker">
 	<h2 style="text-align: center">Portfolio Tracker</h2>
 	<p>Details regarding your tracked stocks. </p>
	<table id="portfoliotracker" class="table table-striped">
	<thead>
		<tr>
			<td>  </td>
			<td>Ticker</td>
			<td>Company Name</td>
			<td>Current Price ($)</td>
			<td>Opening Price ($)</td>
			<td>Change (%)</td>
			<td>Predicted Price ($)</td>
			<td>Owned</td>
			<td>Sell</td>
		</tr>
		</thead>
	</table>
	</div>

	<div class="stocktrader">
	<fieldset>
		<legend style="text-align: center">Stock Trader</legend>
		<div id="stocktraderInner">
			<input type="text" id="traderWidgetAuto" placeholder="Ticker" size="10" onblur="autoFillName(traderWidgetAuto)">
			<input type="text" id="companyname" placeholder="Company Name" size="20">
		</div>
		<div id="stocktraderInner">
			Quantity: 
			<input type="text" id="quantitybox">
		</div>
		<div id="stocktraderInner">
			<input type="button" value="Buy" onclick="buyStock(traderWidgetAuto,quantitybox)">
			<input type ="button" value="Sell" onclick="sellStock(traderWidgetAuto,quantitybox)">
		</div>
	</fieldset>
	</div>

	<br>
	
	<div id="csv-div">
	<input type="file" id="csv-file"> <br>
	<button onClick=uploadToPortfolio()>Upload To Portfolio</button><br>
	<button onClick=uploadToWatchlist()>Upload To Watchlist</button>
	
	</div>
<!--
<ul id="contextMenu" class="dropdown-menu" role="menu">
	<li><a tabindex="-1" href="#" class="payLink">Sell </a></li>
</ul> -->


</div>
</body>

</html>

<?php 
//include header template
require('layout/footer.php'); 
?>

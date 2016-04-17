var transactions = [];

function listAccountRemove(accountId) {
	var getAccountUrl = "https://localhost/CSCI-310-Group-L/www/src/scripts/admin.php?function=getAccount&userId=3&accountId=" + accountId;
	var getAccount = httpGet(getAccountUrl);
	var parsedAccount = JSON.parse(getAccount);
	var accountName = parsedAccount["institution"] + " " + parsedAccount["type"];

	var table = document.getElementById("transaction-table");

	var count = $('#transaction-table tr').length;
	var rowsToDelete = [];
	for (var i=1; i<count; i++) {
		if (table.rows[i].cells[0].innerHTML == accountName) {
			rowsToDelete.push(i);
		}
	}

	for (var i=0; i<rowsToDelete.length; i++) {
		table.deleteRow(rowsToDelete[i]-i);
	}
}

function listAccount(accountId) {
	var getTransactionsUrl = "https://localhost/CSCI-310-Group-L/www/src/scripts/admin.php?function=getTransactions&userId=3&accountId=" + accountId;
	var getTransactions = httpGet(getTransactionsUrl);
	var parsedTx = JSON.parse(getTransactions);

	var getAccountUrl = "https://localhost/CSCI-310-Group-L/www/src/scripts/admin.php?function=getAccount&userId=3&accountId=" + accountId;
	var getAccount = httpGet(getAccountUrl);
	var parsedAccount = JSON.parse(getAccount);
	var accountName = parsedAccount["institution"] + " " + parsedAccount["type"];

	var table = document.getElementById("transaction-table");

	for (var i=0; i<parsedTx.length; i++) {
		var row = table.insertRow(table.rows.length);
		row.className = "transaction-data";
		var cell0 = row.insertCell(0);
		cell0.className = "col-5 transaction-name";
		cell0.innerHTML = accountName;
		var cell1 = row.insertCell(1);
		cell1.className = "col-1 transaction-date";
		var date = new Date(parsedTx[i]["timestamp"] * 1000);
		dateString = date.toString().substring(0,24);
		cell1.innerHTML = dateString;
		var cell2 = row.insertCell(2);
		cell2.className = "col-2 transaction-amount";
		if (parseInt(parsedTx[i]["amount"]) >= 0) {
			cell2.innerHTML = "$" + parseInt(parsedTx[i]["amount"]).toFixed(2);
			cell2.style.color = "#006400";
		}
		else {
			cell2.innerHTML = "-$" + Math.abs(parseInt(parsedTx[i]["amount"])).toFixed(2);
			cell2.style.color = "#FF0000";
		}
		var cell3 = row.insertCell(3);
		cell3.className = "col-3 transaction-category";
		cell3.innerHTML = parsedTx[i]["category"];
		var cell4 = row.insertCell(4);
		cell4.className = "col-4 transaction-merchant";
		cell4.innerHTML = parsedTx[i]["descriptor"];
	}
}

function graphAccount(accountId) {
	var getTransactionsUrl = "https://localhost/CSCI-310-Group-L/www/src/scripts/admin.php?function=getTransactions&userId=3&accountId=" + accountId;
	var getTransactions = httpGet(getTransactionsUrl);
	var parsedTx = JSON.parse(getTransactions);

	var modifiedTx = [];
	for (var i=0; i<parsedTx.length; i++) {
		var timestamp = parsedTx[i]["timestamp"];
		var amount = parsedTx[i]["amount"];
		modifiedTx.push([+timestamp, +amount]);
	}

	var getAccountUrl = "https://localhost/CSCI-310-Group-L/www/src/scripts/admin.php?function=getAccount&userId=3&accountId=" + accountId;
	var getAccount = httpGet(getAccountUrl);
	var parsedAccount = JSON.parse(getAccount);
	var accountName = parsedAccount["institution"] + " " + parsedAccount["type"];

    var chart = new Highcharts.Chart({

    	chart: {
    		renderTo: graph
    	},

        title: {
            text: 'Account Graph'
        },

        subtitle: {
            text: accountName
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
            name: 'Transaction Amount',
            lineWidth: 4,
            marker: {
                radius: 4
            },
            data: modifiedTx
        }]
    });
}

function removeAccount(institution, type) {
	var getAccountIdUrl = "https://localhost/CSCI-310-Group-L/www/src/scripts/admin.php?function=getAccountId&institution=" + institution + "&type=" + type;
	var accountId = httpGet(getAccountIdUrl);
	//console.log(accountId);

	var removeAccountUrl = "https://localhost/CSCI-310-Group-L/www/src/scripts/admin.php?function=removeAccount&userId=1&accountId=" + accountId;
	var remove = httpGet(removeAccountUrl);
	//console.log(remove);

	location.reload();
}

function parseCSV() {
	var file = document.getElementById("csv-file").files[0];

	if (file == undefined || (!file.name.match(/\.(csv)$/))) {
		alert("Please select a CSV file!");
		location.reload();
		return undefined;
	}
	else {
		Papa.parse(file, {
			header: true,
			dynamicTyping: true,
			complete: function (results) {
				var data = results.data;
				//console.log(data);

				for (var i=0; i<data.length; i++) {
					var accountInstitution = data[i]["accountInstitution"];
					var accountType = data[i]["accountType"];
					var txAmount = data[i]["txAmount"];
					var txCategory = data[i]["txCategory"];
					var txMerchant = data[i]["txMerchant"];
					var txTime = data[i]["txTime"];

					var getAccountIdUrl = "https://localhost/CSCI-310-Group-L/www/src/scripts/admin.php?function=getAccountId&institution=" + accountInstitution + "&type=" + accountType;
					var accountId = httpGet(getAccountIdUrl);

					var insertTransactionUrl = "https://localhost/CSCI-310-Group-L/www/src/scripts/admin.php?function=insertTransaction&userId=3&accountId=" + accountId + "&descriptor=" + txMerchant + "&amount=" + txAmount + "&category=" + txCategory + "&timestamp=" + txTime;
					var insert = httpGet(insertTransactionUrl);
					//console.log(insert);
				}

				location.reload();
			}
		});
	}
}

function oldparseCSV() {
	var file = document.getElementById("csv-file").files[0];

	if (file == undefined || (!file.name.match(/\.(csv)$/))) {
		alert("Please select a CSV file!");
		return undefined;
	}
	else {
		Papa.parse(file, {
			header: true,
			dynamicTyping: true,
			complete: function (results) {
				var data = results.data;
				console.log(data);

				for (var i=0; i<data.length; i++) {
					var name = data[i]["accountInstitution"] + " " + data[i]["accountType"];
					var result = $.grep(transactions, function(e) { return e.name == name; });

					if (result.length == 1) {
						var obj = transactions.filter(function(obj) { return obj.name == name; });
						obj[0]["txTime"].push(data[i]["txTime"]);
						obj[0]["txMerchant"].push(data[i]["txMerchant"]);
						obj[0]["txAmount"].push(data[i]["txAmount"]);
						obj[0]["txCategory"].push(data[i]["txCategory"]);
					}
					else {
						var txTime = [];
						txTime.push(data[i]["txTime"]);
						var txMerchant = [];
						txMerchant.push(data[i]["txMerchant"]);
						var txAmount = [];
						txAmount.push(data[i]["txAmount"]);
						var txCategory = [];
						txCategory.push(data[i]["txCategory"]);

						transactions.push({
							name: name,
							txTime: txTime,
							txMerchant: txMerchant,
							txAmount: txAmount,
							txCategory: txCategory
						});
					}
				}

				console.log(transactions);

				//var find = transactions.filter(function(obj) { return obj.name == "Bank of America Credit Card"; });
				//console.log(find[0]["name"]);
			}
		});
	}
}

function httpGetAsync(theUrl, callback) {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() { 
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
            callback(xmlHttp.responseText);
    }
    xmlHttp.open("GET", theUrl, true); // true for asynchronous 
    xmlHttp.send(null);
}

function httpGet(theUrl) {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", theUrl, false ); // false for synchronous request
    xmlHttp.send( null );
    return xmlHttp.responseText;
}
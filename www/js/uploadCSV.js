var transactions = [];

function parseCSV() {
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

					var insertTransactionUrl = "https://localhost/CSCI-310-Group-L/www/src/scripts/admin.php?function=insertTransaction&userId=1&accountId=" + accountId + "&descriptor=" + txMerchant + "&amount=" + txAmount + "&category=" + txCategory + "&timestamp=" + txTime;
					var insert = httpGet(insertTransactionUrl);
					//console.log(insert);
				}

				location.reload();
			}
		});
	}
}

function parseCSV2() {
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
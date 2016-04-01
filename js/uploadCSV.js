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
				var transactions = [];
				//console.log(data);

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
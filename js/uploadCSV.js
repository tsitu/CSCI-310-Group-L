function parseCSV() {
	var file = document.getElementById("csv-file").files[0];
	if (file == undefined || (!file.name.match(/\.(csv)$/))) {
		alert("Please select a CSV file!");
		return undefined;
	} else {
		Papa.parse(file, {
			header: true,
			dynamicTyping: true,
			complete: function (results){
				//console.log(results);
				var data = results.data;
				console.log(data);
			}
		});
	}
}
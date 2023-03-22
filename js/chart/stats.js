"use strict";
var color = Chart.helpers.color;
var stats_data;
function createConfig(legendPosition) {
	return {
		type: 'line',
		data: {
			labels: stats_data['labels'],
			datasets: [{
				label: 'Unique Visitor',
				data: stats_data['unique_visitor'],
				backgroundColor: color(window.chartColors.green).alpha(0.3).rgbString(),
				borderColor: window.chartColors.green,
			}, {
				label: 'Visitor',
				data: stats_data['page_views'],
				backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
				borderColor: window.chartColors.blue,
			}]
		},
		options: {
			maintainAspectRatio: false,
			responsive: true,
			legend: {
				position: legendPosition,
			},
			scales: {
				xAxes: [{
					display: true,
					scaleLabel: {
						display: false,
						labelString: 'Month'
					}
				}],
				yAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: 'Value'
					}
				}]
			},
			title: {
				display: false,
				text: 'Statistics'
			}
		}
	};
}
window.onload = function() {
	if (localStorage.getItem('cloudarcade_admin-theme') === 'theme-dark') {
		Chart.defaults.global.defaultFontColor = '#adbcce';
	}
	get_data('../includes/statistics.php', {"limit":"-1","offset":"0","sub":"-7"}).then((res)=>{
		stats_data = convert_stats_data(res);
		let ctx = document.getElementById('statistics').getContext('2d');
		let config = createConfig('top');
		Stats = new Chart(ctx, config);
	});
};
function convert_stats_data(data){
	let arr = JSON.parse(data);
	let res = {
		page_views: [],
		unique_visitor: [],
		labels: [],
	};
	arr.forEach((data)=>{
		res.page_views.push(data.page_views);
		res.unique_visitor.push(data.unique_visitor);
		res.labels.push(data.date);
	});
	return res;
}
function update_stats(data){
	Stats.data.labels = data.labels;
	Stats.data.datasets[0].data = data.unique_visitor;
	Stats.data.datasets[1].data = data.page_views;
    Stats.update();
}
function get_data(url, data){
	let wait = new Promise((res) => {
		let xhr = new XMLHttpRequest();
		xhr.open('POST', url+'?data='+JSON.stringify(data));
		xhr.onload = function() {
			res(xhr.responseText);
		};
		xhr.send();
	});
	return wait;
}
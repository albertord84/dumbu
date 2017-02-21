	$(document).ready(function () {    
		//window.onload = function () {
			chart = new CanvasJS.Chart("chartContainer", {
				/*title: {
					text: "Site Traffic",
					fontSize: 30
				},*/
				animationEnabled: true,
				axisX: {
					gridColor: "Silver",
					tickColor: "silver",
					valueFormatString: "DD"
				},
				toolTip: {
					shared: true
				},
				theme: "theme2",
				axisY: {
					gridColor: "Silver",
					tickColor: "silver"
				},
				legend: {
					verticalAlign: "center",
					horizontalAlign: "right"
				},
				data: [
				{
					type: "line",
					showInLegend: true,
					lineThickness: 2,
					name: "Seguidores ganhos",
					markerType: "square",
					color: "#F08080",
					dataPoints: //followings_data 
                                        [
					{ x: 3, y: 410 },
					{ x: 5, y: 460 }
					]
                                        /*[
					{ x: new Date(2010, 0, 3), y: 650 },
					{ x: new Date(2010, 0, 5), y: 700 },
					{ x: new Date(2010, 0, 7), y: 710 },
					{ x: new Date(2010, 0, 9), y: 658 },
					{ x: new Date(2010, 0, 11), y: 734 },
					{ x: new Date(2010, 0, 13), y: 963 },
					{ x: new Date(2010, 0, 15), y: 847 },
					{ x: new Date(2010, 0, 17), y: 853 },
					{ x: new Date(2010, 0, 19), y: 869 },
					{ x: new Date(2010, 0, 21), y: 943 },
					{ x: new Date(2010, 0, 23), y: 970 }
					]*/
				},
				{
					type: "line",
					showInLegend: true,
					name: "Perfis seguidos",
					color: "#20B2AA",
					lineThickness: 2,
					dataPoints: 
                                        [
					{ x: 3, y: 510, z: 7},
					{ x: 5, y: 560, z: 7}
					]
                        //followers_data
					
				}
				],
				legend: {
					cursor: "pointer",
					itemclick: function (e) {
						if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
							e.dataSeries.visible = false;
						}
						else {
							e.dataSeries.visible = true;
						}
						chart.render();
					}
				}
			});
                        
			chart.render();
                        console.log(followings_data);
                        //alert(chart.data[0].dataPoints[0].y);
		//}
                //alert(char.data);
		
	});

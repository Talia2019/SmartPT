var today = new Date();

var date_arr = new Array();
var calory_arr = new Array();
var dailykcalarray = document.getElementById("dailykcalarray");
dailykcalarray = dailykcalarray.innerHTML;
dailykcalarray = JSON.parse(dailykcalarray);

for(var i=0;i<7;i++)
{
	if(calory_arr[i]===undefined) calory_arr[i]=0;
}

var yyyy = today.getFullYear();
var flag =0;
for(var i=0;i<7;i++)
{
	var mm = today.getMonth()-2+i; // Jan is 0

	if(mm>12){
		 mm = mm-12;
		 if(flag==0)
		 {
			 yyyy=yyyy+1;
			 flag=1;
		 }
	 }

	date_arr[i] = yyyy + '-'+ mm;

	for(var j=0;j<dailykcalarray.length;j++)
	{
		if(dailykcalarray[j].eaten_day.indexOf(date_arr[i])!=-1)
		{
			calory_arr[i]=calory_arr[i]+ parseInt(dailykcalarray[j].eaten_calory);
		}
	}

}



// 우선 컨텍스트를 가져옵니다.
var ctx = document.getElementById("month_kcal").getContext('2d');
/*
	- Chart를 생성하면서,
	- ctx를 첫번째 argument로 넘겨주고,
	- 두번째 argument로 그림을 그릴때 필요한 요소들을 모두 넘겨줍니다.
	*/
	var myChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: date_arr,
			datasets: [{
				label: '월별 섭취 칼로리',
				data: calory_arr,
				hoverBorderWidth : '3',
				backgroundColor: [
				'rgba(54, 162, 235, 0.2)',
				'rgba(54, 162, 235, 0.2)',
				'rgba(54, 162, 235, 0.2)',
				'rgba(54, 162, 235, 0.2)',
				'rgba(54, 162, 235, 0.2)',
				'rgba(54, 162, 235, 0.2)',
				'rgba(54, 162, 235, 0.2)'
				],
				borderColor: [
				'rgba(54, 162, 235, 1)',
				'rgba(54, 162, 235, 1)',
				'rgba(54, 162, 235, 1)',
				'rgba(54, 162, 235, 1)',
				'rgba(54, 162, 235, 1)',
				'rgba(54, 162, 235, 1)',
				'rgba(54, 162, 235, 1)'
				],
				borderWidth: 1
			}]
		},
		options: {
			legend: {
				labels: {
					fontColor: "black",
					fontSize: 30
				}
			},
						maintainAspectRatio: true, // default value. false일 경우 포함된 div의 크기에 맞춰서 그려짐.
						scales: {
							xAxes: [{
								ticks:{
									fontColor : 'rgba(12, 13, 13, 1)',
									fontSize : 25
								},

							}],
							yAxes: [{
								ticks: {
									beginAtZero:false,
									fontSize : 30
								}
							}]
						}
					}
		});

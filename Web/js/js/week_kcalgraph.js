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


let firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
let lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);

let weekObj = null;
let weekObjArray = new Array();
let weekStand = 8;  // 월요일 고정
let firstWeekEndDate = true;
let thisMonthFirstWeek = firstDay.getDay();

for(var num = 1; num <= 6; num++) {

		// 마지막월과 첫번째월이 다른경우 빠져나온다.

		if(lastDay.getMonth() != firstDay.getMonth()) {

				break;

		}



		weekObj = new Object();



		// 한주의 시작일은 월의 첫번째 월요일로 설정

		if(firstDay.getDay() <= 1) {



				// 한주의 시작일이 일요일이라면 날짜값을 하루 더해준다.

				if(firstDay.getDay() == 0) { firstDay.setDate(firstDay.getDate() + 1); }



				weekObj.weekStartDate =

							firstDay.getFullYear().toString()+ "-"+ numberPad((firstDay.getMonth() + 1).toString(), 2)+ "-"+ numberPad(firstDay.getDate().toString() , 2);

		}



		if(weekStand > thisMonthFirstWeek) {

				if(firstWeekEndDate) {

						if((weekStand - firstDay.getDay()) == 1) {

								firstDay.setDate(firstDay.getDate() + (weekStand - firstDay.getDay()) - 1);

						}

						if((weekStand - firstDay.getDay()) > 1) {

								firstDay.setDate(firstDay.getDate() + (weekStand - firstDay.getDay()) - 1)

						}

						firstWeekEndDate = false;

				} else {

						firstDay.setDate(firstDay.getDate() + 6);

				}

		} else {

				firstDay.setDate(firstDay.getDate() + (6 - firstDay.getDay()) + weekStand);

		}



		// 월요일로 지정한 데이터가 존재하는 경우에만 마지막 일의 데이터를 담는다.

		if(typeof weekObj.weekStartDate !== "undefined") {



				weekObj.weekEndDate =

							firstDay.getFullYear().toString()

						+ "-"

						+ numberPad((firstDay.getMonth() + 1).toString(), 2)

						+ "-"

						+ numberPad(firstDay.getDate().toString(), 2);



				weekObjArray.push(weekObj);

		}



		firstDay.setDate(firstDay.getDate() + 1);

}
function numberPad(num, width) {

        num = String(num);

        return num.length >= width ? num : new Array(width - num.length + 1).join("0") + num;

    }

for(var i=0;i<5;i++)
{
	date_arr[i] = mm +'월'+(i+1)+"주차";
	for(var j=0;j<dailykcalarray.length;j++)
	{
		if(dailykcalarray[j].eaten_day>=weekObjArray[i].weekStartDate && dailykcalarray[j].eaten_day<=weekObjArray[i].weekEndDate)
		{
			calory_arr[i]=calory_arr[i]+ parseInt(dailykcalarray[j].eaten_calory);
		}
	}

}


// 우선 컨텍스트를 가져옵니다.
var ctx = document.getElementById("week_kcal").getContext('2d');
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
				label: '주별 섭취 칼로리',
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

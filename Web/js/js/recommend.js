const { request } = require("http");
const { RequestTimeout } = require("http-errors");

	
		function SetDisplay(frm) {

			//var set = frm.selectset.selectedIndex;
			var element = document.getElementById("test");

			switch( set ){
				case 0:
			//	frm.set.value = '칼로리에 맞는 Set가 표시됩니다.';
				break;
				case 1:
				//frm.set.value = 'Set1';
				element.innerText("set1");
				break;
				case 2:
				//frm.set.value = 'Set2';
				element.innerText("set2");
				break;
				case 3:
				//frm.set.value = 'Set3';
				element.innerText("set3");
			}

			return true;
		}

function Display(){
	document.getElementById("setKcal").style.display='block';
	var inputKcal = request.getParameter("inputKcal");
	
}

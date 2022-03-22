<?php
session_start();
	//$_SESSION['code']=123456789;
	//echo $_SESSION['code'];
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>

	<meta charset="utf-8">



  <!-- 외부 css -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link href="https://fonts.googleapis.com/css2?family=Do+Hyeon&display=swap" rel="stylesheet">
	  <link rel="stylesheet" href="./css/jaehyun.css?ver=1">
  <!-- 폰트 -->
  <link href="https://fonts.googleapis.com/css2?family=Black+Han+Sans&family=Do+Hyeon&display=swap" rel="stylesheet">
  <!-- 아이콘 -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/v4-shims.css">

  <!-- 스크립트 -->
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
    crossorigin="anonymous"></script>
  <script src="./js/nav.js"></script>


</head>
<body>


	<form style="width:80%; margin: 10% auto;" method="post" action="form_userinsert.php">
		<div class="form-row">
			<div class="form-group col-md-6">
				<label for="inputGender">성별</label>
				<select id="inputGender" name="inputGender" class="form-control">
					<option value=0>Male</option>
					<option value=1>Female</option>
				</select>
			</div>
			<div class="form-group col-md-6">
				<label for="inputNumber">나이</label>
				<input type="number" min="1" max="100" class="form-control" id="inputAge" name="inputAge" placeholder="age ( 세 )" required>
			</div>
		</div>
		<br><br>
		<div class="form-row">
			<div class="form-group col-md-6">
				<label for="inputHeight">키</label>
				<input type="number" min="100" max="250" class="form-control" id="inputHeight" name="inputHeight" placeholder="height ( cm )" required>
			</div>
			<div class="form-group col-md-6">
				<label for="inputWeight">체중</label>
				<input type="number" min="10" max="200" class="form-control" id="inputWeight" name="inputWeight" placeholder="weight ( Kg )" required>
			</div>
		</div>
		<br><br>
		<div class="form-group">
			<label for="inputExercise">운동 빈도</label>
			<select id="inputExercise" name="inputExercise" class="form-control">
				<option value=1>따로 운동하지 않는다</option>
				<option value=1.12>일상활동 + 30~60 분 꾸준한 운동을 한다</option>
				<option value=1.27>일상활동 + 60 분 이상 꾸준한 운동을 한다</option>
				<option value=1.45>일상활동 + 60 분 무난한 운동 + 60 분 과격한 운동을 한다</option>
			</select>
		</div>
		<br><br>
		<div class="form-group">
			<label style="font-size:60px; float:left;">하루 섭취칼로리 설정</label>
			<div class="btn-group btn-group-toggle" data-toggle="buttons" style="margin-left:30px; margin-top:10px;">
				<button class="btn btn-outline-secondary" style="font-size:30px;"type="button" name="calory_type" value="권장칼로리" onclick="changeCalory(this.value)">권장칼로리</button>
				<button class="btn btn-outline-secondary" style="font-size:30px;"type="button" name="calory_type" value="직접 입력" onclick="changeCalory(this.value)"/> 직접 입력</button>
			</div>
			<br>

			<input type="number" min="100" max="3000"class="form-control" id="inputCalory" name="inputCalory" placeholder="Calorie ( Kcal )" readonly required>
			<br>
			<h3 style="color:gray;">[ 하루 권장 칼로리 ] 표준체중(kg) X 활동지수</h3>
			<h3 style="color:gray;">[ 다이어트 식단 칼로리 ] 여성 : 800 - 1200 Kcal , 남성 : 1200 - 1400 Kcal</h3>
		</div>
		<br><br>
		<div class="form-row">
			<div class="form-group col-md-12">
				<label for="inputState">미스케일 사용여부</label>
				<select id="inputMiscale" name="inputMiscale" class="form-control">

					<option value=1>Yes</option>
					<option value=0>No</option>
				</select>
			</div>
		</div>

		<br><br>
		<button type="submit" class="btn btn-primary" style="width:200px; height:100px; float:right; font-size:50px;">정보입력</button>
	</form>


	<br><br>

	<script>
		function changeCalory(sel){
			if(sel=='권장칼로리')
			{
				document.getElementById('inputCalory').readOnly = true;
			}
			else
			{
				document.getElementById('inputCalory').readOnly = false;
			}
		}

		$("#inputGender,#inputAge,#inputWeight,#inputHeight,#inputExercise,#inputMiscale").on("propertychange change keyup paste input", function() {
			var gender =$('#inputGender').val();
			var age =$('#inputAge').val();
			var exercise  =$('#inputExercise').val();
			var weight  =$('#inputWeight').val();
			var height  =$('#inputHeight').val() *0.01;
			var miscale =$('#inputMiscale').val();
			var kcal =0;

			if(gender==0) //남자면
			{
				kcal = 662-9.53*age+ exercise*(15.91*weight+539.6*height);
				kcal = Math.round(kcal);
				$('#inputCalory').val(kcal);
			}
			if(gender==1) //여자인경우
			{
				kcal = 354-6.91*age+exercise*(9.36*weight+726*height);
				kcal = Math.round(kcal);
				$('#inputCalory').val(kcal);
			}
		});
	</script>



</body>
</html>

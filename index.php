<?php
	//data type
	class IncentiveTracker{
		public $lastDateReportedOn;
		public $consequetiveDaysReported = 0;
	}
	
	//moduler function to check incentive
	function CheckIncentivizeFor($date, $action) {
		$diff = null;		
		$myTracker = new IncentiveTracker();
		
		//get session var
		if(isset($_SESSION["myTracker"])){
			$myTracker = $_SESSION["myTracker"];
		}
	
		//get diff
		if(!is_null($date) && !is_null($myTracker->lastDateReportedOn)){
			$diff = $date->diff($myTracker->lastDateReportedOn)->format("%a");
		}
		else if(!is_null($date)){
			$diff = 0;
		}
		//get session var
		if(isset($_SESSION["myTracker"])){
			$myTracker = $_SESSION["myTracker"];
		}
		//get diff
		if(!is_null($date) && !is_null($myTracker->lastDateReportedOn)){
			$diff = $date->diff($myTracker->lastDateReportedOn)->format("%a");
		}
		else if(!is_null($date)){
			$diff = 0;
		}
		
		//logic
		if(!is_null($diff) && $diff == 1){
			$myTracker->lastDateReportedOn = $date;
			$myTracker->consequetiveDaysReported++;
		}
		else if(!is_null($diff)){
			$myTracker->lastDateReportedOn = $date;
			$myTracker->consequetiveDaysReported = 1;
		}
		else{
			$myTracker->lastDateReportedOn = null;
			$myTracker->consequetiveDaysReported = 0;
		}
		//store session info
		$_SESSION["myTracker"] = $myTracker;
		//check if eligible
		if($action == "newborn" || $myTracker->consequetiveDaysReported >= 5){
			$myTracker->consequetiveDaysReported = 0;
			return true;
		}
		else{
			return false;
		}
	}
	
	//init GET parameters
	session_start();
	$date = isset($_GET["date"]) ? new DateTime(htmlspecialchars($_GET["date"])) : null;
	$incentivize_actions = isset($_GET["incentivize-actions"]) ? htmlspecialchars($_GET["incentivize-actions"]) : null;
	$isEligibleForIncentive = CheckIncentivizeFor($date, $incentivize_actions);
	
	//get session var to use info for reporting
	if(isset($_SESSION["myTracker"])){
		$myTracker = $_SESSION["myTracker"];
	}
?>
<html>
	<head>
		<title>Ovia Health - Coding Exercise</title>
		<link rel="icon" type="image/png" sizes="16x16" href="https://assets.oviahealth.com/wp-content/themes/ovia/favicon/favicon-16x16.png">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
    			rel="stylesheet"
    			integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
    			crossorigin="anonymous">
	</head>
	<body>
	<div class="container">
		<!-- message holder -->
		<?php if( !is_null($date) && !is_null($incentivize_actions)): ?>
			<br>
			<div class="alert alert-success <?= $isEligibleForIncentive == true ? "alert-success" : "alert-info" ?>" role="alert">
			  You have reported <?=$incentivize_actions?> for <?= (!is_null($date)) ? $date->format('d/m/Y') : null ?>. <?= $isEligibleForIncentive == true ? "You have earned an incentive based on your reporting" : null ?>
			</div>
		<?php endif; ?>
		<!-- Content here -->
		<div class="row">
			<!-- form block -->
			<div class="col-8 form">
				<h4>Form</h4>
				<form class="form-horizontal">
					<fieldset>
						<!-- Form Name -->
						<legend>Submit Activities</legend>
						<!-- Text input-->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="date">Date</label>  
						  <div class="col-md-4">
						  <input id="date" name="date" type="date" placeholder="placeholder" class="form-control input-md" required="">
						  <span class="help-block">Enter date of your log</span>  
						  </div>
						</div>
						<!-- Multiple Radios -->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="incentivize-actions">Incentivize Actions</label>
						  <div class="col-md-4">
						  <div class="radio">
							<label for="incentivize-actions-0">
							  <input type="radio" name="incentivize-actions" id="incentivize-actions-0" value="1" checked="checked">
							  1. Log Symptoms
							</label>
							</div>
						  <div class="radio">
							<label for="incentivize-actions-1">
							  <input type="radio" name="incentivize-actions" id="incentivize-actions-1" value="2">
							  2. Log Allergies
							</label>
							</div>
						  <div class="radio">
							<label for="incentivize-actions-2">
							  <input type="radio" name="incentivize-actions" id="incentivize-actions-2" value="3">
							  3. Log Moods
							</label>
							</div>
						  <div class="radio">
							<label for="incentivize-actions-3">
							  <input type="radio" name="incentivize-actions" id="incentivize-actions-3" value="newborn">
							  Report Newborn
							</label>
							</div>
						  </div>
						</div>
						<!-- Button -->
						<div class="form-group">
						  <label class="col-md-4 control-label" for="submit"></label>
						  <div class="col-md-4">
							<button id="submit" name="submit" class="btn btn-primary">Submit</button>
						  </div>
						</div>

					</fieldset>
				</form>
			</div>
			<!-- status block -->
			<div class="col-4 status bg-light">
				<h4>Status</h4>
				<p>Last Reported Date: <?= !is_null($myTracker->lastDateReportedOn) ? $myTracker->lastDateReportedOn->format('d/m/Y') : null ?></p>
				<p>Consequetive Reported: <?= $myTracker->consequetiveDaysReported ?> (in Days)</p>
			</div>
		</div>
	</div>
		
		
		<!-- Adding Javascript libraries at the end to make page load faster -->
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
				integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
				crossorigin="anonymous"/>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
				integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
				crossorigin="anonymous"/>
	</body>
<html>
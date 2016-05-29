<?php
include '/home/aj4057/verify_iron.php';
include '/home/aj4057/config_iron.php'; #Connect to db.
if(isset($_POST["EDIT"])) {
	$stmt = $conn->prepare("SELECT * FROM STUDENT$ WHERE ID = :id AND COACH = :coach");
	$stmt->execute(array('id' => $_POST["EDIT"],
						 'coach' => $_SESSION['login_user']));
	$row = $stmt->fetch();
	if($stmt->rowCount() == 0) {
		header("Location: students.php");
		die();
	}
} else {
	header("Location: students.php");
	die();
}
$weekBuilder = array();
$stmt = $conn->prepare("SELECT * FROM DATA WHERE LINKED_ID = :link");
$stmt->execute(array('link' => $row["ID"]));
$weekData = $stmt->fetchAll();

for($week = 1; $week <= 12; $week++) {
	$echoed = FALSE;
	foreach($weekData as $weekDataRow) {
		if($weekDataRow["WEEK"] == $week) {
			$echoed = TRUE;
			$weekBuilder[] = 
			'<option value="' . $week .
			'" style="width: 100%; background-color: lime;">* Week ' . $week . '</option>';
			break;
		}
	}
	if($echoed == FALSE) {
		$weekBuilder[] = 
		'<option value="' . $week .
		'" style="width: 100%;">Week ' . $week . '</option>';
	}
}
$selectedWeek = 1;
if(isset($_POST["WEEK_LOCAL"])) {
	$selectedWeek = $_POST["WEEK_LOCAL"];
	$key = array_search('<option value="' .
	$selectedWeek  . '" style="width: 100%;">Week ' .
	$selectedWeek  . '</option>', $weekBuilder);
	if($key !== FALSE) {
		$weekBuilder[$key] = 
		'<option selected="selected" value="' .
		$selectedWeek  . '" style="width: 100%;">Week ' .
		$selectedWeek  . '</option>';
	} else {
		$key = array_search('<option value="' .
		$selectedWeek  . '" style="width: 100%; background-color: lime;">* Week ' .
		$selectedWeek  . '</option>', $weekBuilder);
		if($key !== FALSE) {
			$weekBuilder[$key] = 
			'<option selected="selected" value="' .
			$selectedWeek  . '" style="width: 100%; background-color: lime;">* Week ' .
			$selectedWeek  . '</option>';
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit Student</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
<div id="navbar">
	<div id="exit" style="margin: 0;">
		<a href="students.php"><div class="headlink" style="border-radius: 0 0 30px 0;"><div class="textheadlink">Exit</div></div></a>
	</div>
</div>
<div class="pseudobody">
	<h1>Editing: <?php echo($row["NAME"]); ?></h1>
	<div class="center">
		<form method="post">
			<input type="hidden"
				   name="EDIT"
				   value="<?php echo($_POST["EDIT"]); ?>"><br>
				
			<h3 class="titlepadding">Edit individual data entries</h3>
			<h3 class="titlepadding">WARNING: Switching this field will clear any unsaved data below!</h3>
				<select class="classestext" name='WEEK_LOCAL' onchange='if(this.value != 0) {this.form.submit();}'>
<?php foreach($weekBuilder as $weeks) {echo($weeks);} ?> 
				</select>
				
<?php
$stmt = $conn->prepare("SELECT * FROM DATA WHERE WEEK = :week AND LINKED_ID = :link");
$stmt->execute(array('week' => $selectedWeek,
					 'link' => $row["ID"]));
$replace = $stmt->fetch();
?>
			<h3 class="titlepadding">Bench Press</h3>
				<input class="text"
					   type="text"
					   name="BENCH"
					   placeholder="<?php echo($replace["BENCH"]); ?>" 
					   value="<?php echo($replace["BENCH"]); ?>"><br>
					   
			<h3 class="titlepadding">Dead Lift</h3>
				<input class="text"
					   type="text"
					   name="DEADLIFT"
					   placeholder="<?php echo($replace["DEADLIFT"]); ?>" 
					   value="<?php echo($replace["DEADLIFT"]); ?>"><br>
					   
			<h3 class="titlepadding">Backsquat</h3>
				<input class="text"
					   type="text"
					   name="BACKSQUAT"
					   placeholder="<?php echo($replace["BACKSQUAT"]); ?>" 
					   value="<?php echo($replace["BACKSQUAT"]); ?>"><br>
					   
			<button name="UPDATE_WEEK" type="submit" value="REAL" class="goodbutton">Update Week <?php echo($selectedWeek); ?></button>
		</form><br>
	</div>
</div>
<div class="pseudobody">
	<h1>Editing: <?php echo($row["NAME"]); ?></h1>
	<div class="center">
		<form method="post">
			<input type="hidden"
				   name="EDIT"
				   value="<?php echo($_POST["EDIT"]); ?>">
			<?php
			if($error !== "") {echo("<span>$error</span>");}
			if($editError !== "") {echo("<span>$editError</span>");}
			if($editSuccess !== "") {echo("<p style=\"color:green;\">$editSuccess</p>");}
			?> 
			<h3 class="titlepadding">Name</h3>
				<input class="text" 		
					   type="text" 		
					   name="NAME" 
					   placeholder="<?php echo($row["NAME"]); ?>" 
					   value="<?php echo($row["NAME"]); ?>"><br>
			
			<h3 class="titlepadding">Student ID</h3>
				<input class="text"
					   type="text"
					   name="STUDENT_ID"
					   placeholder="<?php echo($row["STUDENT_ID"]); ?>" 
					   value="<?php echo($row["STUDENT_ID"]); ?>"><br>
					   
			<h3 class="titlepadding">Gender</h3>
			<select name='GENDER' class="classestext">
<?php
if($row["GENDER"] == "F") {
?> 
				<option value='M' style="width: 100%;">Male</option>
				<option value='F' style="width: 100%;" selected>Female</option>
<?php
} else {
?> 
				<option value='M' style="width: 100%;" selected>Male</option>
				<option value='F' style="width: 100%;">Female</option>
<?php
}
?>
			</select>
					   
			<h3 class="titlepadding">Original Dead Lift MAX</h3>
				<input class="text"
					   type="number"
					   name="BASE_BENCH"
					   placeholder="<?php echo($row["BASE_BENCH"]); ?>" 
					   value="<?php echo($row["BASE_BENCH"]); ?>"><br>
					   
			<h3 class="titlepadding">Original Bench MAX</h3>
				<input class="text"
					   type="number"
					   name="BASE_DEADLIFT"
					   placeholder="<?php echo($row["BASE_DEADLIFT"]); ?>" 
					   value="<?php echo($row["BASE_DEADLIFT"]); ?>"><br>
					   
			<h3 class="titlepadding">Original Squat MAX</h3>
				<input class="text"
					   type="number"
					   name="BASE_BACKSQUAT"
					   placeholder="<?php echo($row["BASE_DEADLIFT"]); ?>" 
					   value="<?php echo($row["BASE_DEADLIFT"]); ?>"><br>
					   
			<h3 class="titlepadding">Post Test Dead Lift MAX (Set to 0 for Not Entered)</h3>
				<input class="text"
					   type="number"
					   name="BASE_BENCH"
					   placeholder="<?php echo($row["POST_BENCH"]); ?>" 
					   value="<?php echo($row["POST_BENCH"]); ?>"><br>
					   
			<h3 class="titlepadding">Post Test Bench MAX (Set to 0 for Not Entered)</h3>
				<input class="text"
					   type="number"
					   name="POST_DEADLIFT"
					   placeholder="<?php echo($row["POST_DEADLIFT"]); ?>" 
					   value="<?php echo($row["POST_DEADLIFT"]); ?>"><br>
					   
			<h3 class="titlepadding">Post Test Squat MAX (Set to 0 for Not Entered)</h3>
				<input class="text"
					   type="number"
					   name="POST_BACKSQUAT"
					   placeholder="<?php echo($row["POST_DEADLIFT"]); ?>" 
					   value="<?php echo($row["POST_DEADLIFT"]); ?>"><br>

			<div class="padding"><input type="submit"	value="Edit Student!" class="goodbutton"></div>
		</form><br>
	</div>
</div>
<html>

			<!-- BEGIN PAGE HEAD -->
<head>
			<!-- META TAGS AND TITLE -->
	<meta charset="utf-8">
	<title>AnaTwitics - Latest Trends on Twitter</title>
			<!-- IMPORT CSS FILES FROM LOCAL AND ONLINE HOSTS -->
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Ubuntu:regular,bold&subset=Latin">
			<!-- CUSTOM STYLE FOR THIS HTML PAGE -->
	<style>
		body {
			font-family: Ubuntu;
			background-color: #2B2B2B;
			width: 100%;
			height: 100%;
			margin: 0;
			padding: 0;
			color: whitesmoke;
		}
	/* TOP NAVIGATION BAR - GREEN BAR AT TOP OF PAGE  */

		.topnav {
   			background-color: #5CDB95;
			overflow: hidden;
			width: 100%;
			height: 60px;
			padding-top: 15px;
		}

	/* MAIN HEADING FORMAT */

		.mainheading {
			text-align: left;
			font-size: 48px;
			margin-left: 5%;
		}

		.subheading {
		font-size: 1em;
		margin-left: 4%;
		}

	/*TIME RANGE SELECTOR AT THE TOP OF THE PAGE */
		.timerange {
			float: right;
			margin-top: 0%;
			margin-right: 45%;
		}

		.timerangetext {
			float: right;
			margin-top: 0%;
		}

	/* SEARCHBAR AT THE TOP OF THE PAGE */

		.topnav input[type=text] {
    			float: right;
    			border-style: inset;
    			font-size: 17px;
				margin-top: -3%;
		}

	/* SEARCH BUTTON AND SVG ICON */
		#search-button {
			float: right;
 			width: 50px;
  			height: 28px;
			margin-right: 41.2%;
			margin-top: -3%;
			background-color: whitesmoke;
		}
    
		#search-button svg {
  			width: 25px;
  			height: 25px;
		}

		.chartarea {
			max-width: 55%;
			margin-left: 25%;
		}

		a{
			color: #5CDB95;
		}
	</style>

			<!-- CUSTOM SCRIPTS FOR THIS PAGE -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>		

</head>
<body>
</body>
</html>
<?php	
if(isset($_POST["search"])){
	$server = "fontbonne.cedmpeumguez.us-east-2.rds.amazonaws.com";
	$username = "fontbonne";
	$password = "fontbonne";
	$dbname = "AnaTwitics";
	$port = 55976;
	session_start();
	$conn=mysqli_connect($server,$username,$password,$dbname,$port) or die("Unable to connect to database...");
	$stmt = $conn->prepare("SELECT * FROM KeywordSearches WHERE Keyword = ?");
	$stmt->bind_param("s",$_POST["search"]);
	$stmt->execute();
	$result = $stmt->get_result();
	$url ="main.html";
	$range = $_POST["range"];
	$label = "";
	if($range=="1Day")
	{
		$label = 'labels: ["1day"],'	;
	}
	else if($range=="3days")
	{
		$label = 'labels: ["1 Day","3 Days"],'	;
	}
	else if($range =="5days")
	{
		$label = 'labels: ["1 Day","3 Days","5 Days"],'	;
	}
	else if($range == "1week")
	{
		$label = 'labels: ["1 Day", "3 Days", "5 Days", "7 Days"],'	;
	}
	else if($range == "2weeks")
	{
		$label = 'labels: ["1 Day","3 Days","5 Days","7 Days","14 Days"],';
	}
	else{
		$label = 'THIS DOESNT WORK';
	}
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$data1 = $row["1DayData"];
				$data2 = $row["3DayData"];
				$data3 = $row["5DayData"];
				$data4 = $row["1WeekData"];
				$data5 = $row["2WeekData"];
				//echo '<script>alert("The '.$range.' data for this keyword is: '.$data3.'");</script>'.'<br>';
				echo "Thanks for participating in the beta test for our application! ";
				echo "<a  href=$url>Return to main page</a>";
				echo 
				'<html><body>
				<div class="topnav" style="height: 10.5%;">
					<div style = "margin-top:-2.25%;">
						<form action="search.php" method="POST">
						<a href="main.html" style = "text-decoration: none; color: whitesmoke;"><h1>AnaTwitics</h1></a>
						<input type="text" name="search" placeholder="Search for..." style="margin-right: 45.2%; border-radius: 9px;">
						<button onclick = "generateChart" id="search-button" style="border-radius: 6px;">
								<svg id="search-icon" class="search-icon" viewBox="0 0 24 24">
								<path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 					19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
								<path d="M0 0h24v24H0z" fill="none"/>
								</svg>
						 </button>
						<select name = "range" class="timerange">
							<option value="1day">1 Day</option>
							<option value="3days">3 Days</option>
							<option value="5days">5 Days</option>
							<option value="1week">1 Week</option>
							<option value="2weeks">2 Weeks</option>	
						</select>
						<span class="timerangetext">Time Range:</span>
						<span class="subheading">Discover What\'s Trending on Twitter!</span>
						</form>
					</div>
				</div>
						
						<!--THIS IS THE ACTUAL CHART INFORMATION AND SCRIPT TO CREATE IT ON THE WEBPAGE -->
				<div class="chartarea" style="background-color: #343A40;">
					<canvas id="line-chart" width="500" height="450"></canvas>	
				</div>
					<script type="text/javascript">
						new Chart(document.getElementById("line-chart"), {
						  type: "line",
						  data: {
							'.$label.'
							datasets: [{ 
								data: ['.$data1.','.$data2.','.$data3.','.$data4.','.$data5.'],
								label: "'.$_POST['search'].'",
								borderColor: "#3e95cd",
								fill: false
							  }
							]
						  },
						  options: {
							title: {
							  display: true,
							  text: "'.$_POST['search'].'"
							}
						  }
						});
					</script>
			</body></html>';
			}
		}
		else{
			echo '<script>alert("Oops, we couldn\'t find that keyword! Maybe try searching a different one (we recommend: FontbonneUniversity)");</script>';
			echo "<a  href=$url>Return to main page</a>";
		}
		$conn->close();
}
else{
	echo '<html> 
	<div class="topnav" style="height: 10.5%;">
		<div style = "margin-top:-2.25%;">
			<form action="search.php" method="POST">
			<a href="main.html" style = "text-decoration: none; color: whitesmoke;"><h1>AnaTwitics</h1></a>
			<input type="text" name="search" placeholder="Search for..." style="margin-right: 45.2%; border-radius: 9px;">
			<button onclick = "generateChart" id="search-button" style="border-radius: 6px;">
    				<svg id="search-icon" class="search-icon" viewBox="0 0 24 24">
        			<path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 					19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
        			<path d="M0 0h24v24H0z" fill="none"/>
    				</svg>
 			</button>
			<select name = "range" class="timerange">
				<option value="1day">1 Day</option>
				<option value="3days">3 Days</option>
				<option value="5days">5 Days</option>
				<option value="1week">1 Week</option>
				<option value="2weeks">2 Weeks</option>	
			</select>
			<span class="timerangetext">Time Range:</span>
			<span class="subheading">Discover What\'s Trending on Twitter!</span>
			</form>
		</div>
	</div>
	</html>';
	exit;
}
?>
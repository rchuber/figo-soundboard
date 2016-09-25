<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Figo - Puppeteer Interface</title>

	<!-- Latest compiled and minified Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


	<style>
	#phrasesContainer .btn {
		margin: 10px;
	}
	header {
		background: #EEE;
		margin-bottom: 20px;
	}
	header input {
		margin: 20px 0;
	}
	header h3 {
		margin: 30px 0;
	}
	</style>


</head>
<body>

	<header>
		<div class="container">
			<div class="row">
				<div class="col-sm-8"><h3 style="">Figo Puppeteer Interface</h3></div>
				<div class="col-sm-4"><input class="form-control input-lg" type="text" placeholder="Guest Name" id="guestName" /></div>
			</div>
		</div>
	</header>


	<div class="container">

		<div class="well">

			<div class="row">
				<div class="col-sm-2"><button type="button" id="successButton" class="btn btn-lg btn-block btn-default">🎵</button></div>
				<div class="col-sm-8">
					<input class="form-control input-lg" type="text" placeholder="Custom Text" id="customText" />
				</div>
				<div class="col-sm-2">
					<button class="btn btn-lg btn-default btn-block" id="speakCustomText">Speak 🔊</button>
				</div>
			</div>
		</div>

		<div id="phrasesContainer"></div>

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="http://code.responsivevoice.org/responsivevoice.js"></script>
		<script>

		$(document).ready(function(){ 
			
			responsiveVoice.setDefaultVoice("UK English Female");

			/*
			* Load the phrases from the phrases.json file
			*/
			var html = "";
			$.getJSON( "phrases.json", function( data ) {
				var categories = [];
				$.each( data, function( key, val ) {
					// Create a button for each phrase grouped by category
					html += '<div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">' + key + '</h3></div><div class="panel-body">';
					$.each( val, function( phrasekey, phrase ) {
						html += '<button type="button" class="speakable btn btn-lg btn-default" data-original="'+ escape(phrase.phrase) +'">' + phrase.phrase + '</button>';
					});
					html += '';
					html += '</div></div>';

				});
				// Add all of the phrase buttons to the document
				$("#phrasesContainer").html(html);

				// Replace the name placeholders {name} with something more readable 
				//(that doesn't get spoken just in case no name is entered)
				var defaultName = ". . . ";
				$(".speakable").each(function(){
					var originalText = unescape($(this).attr("data-original"));
					var newText = originalText.replace('{name}',defaultName);
					$(this).text(newText);
				});


				$(".speakable").each(function(){
					$(this).click(function(){
						responsiveVoice.speak($(this).text());
					});
				});

			});

			/*
			Load the success audio sound
			*/
			var chime = new Audio('success.mp3');
			// Play the chime when the proper button is clicked
			$("#successButton").click(function(){
				chime.pause()
				chime.currentTime = 0
				chime.play();

			})
			
			// Every time the guest name input field is changed, update the buttons
			// hold the original value in an html attribute to make this easy
			$('#guestName').on('input', function() {
				var newName = $(this).val();
				$(".speakable").each(function(){
					var originalText = unescape($(this).attr("data-original"));
					var newText = originalText.replace('{name}',newName);
					$(this).text(newText);
				});
			});

			// If enter is pressed inside the custom text field, or the speak button is pressed
			// speak the text in the field, clear the field, and put the cursor back in it
			$("#customText").on("keyup",function(e) {
				if (e.keyCode === 13) {
					responsiveVoice.speak($("#customText").val());
					$("#customText").val("");
					$("#customText").focus();
				}
			});
			$("#speakCustomText").on("click",function(){
				responsiveVoice.speak($("#customText").val());
				$("#customText").val("");
				$("#customText").focus();
			});


		});
</script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</body>
</html>
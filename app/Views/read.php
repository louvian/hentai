<!DOCTYPE html>
<html>
<head>
	<title><?php print $dt['title']; ?></title>
</head>
<body>
<center>
<div id="bound"><a href="#"><img src="" id="sacred"></a></div>
</center>
<script type="text/javascript">
	var pages = <?php print (int) $dt['info']['Pages']; ?>, 
		pointer = 1;
		bound = document.getElementById('bound'),
		sacred = document.getElementById('sacred');
	sacred.src = "https://static.mieinstance.cf/hentai/pururin/<?php print $dt['id']; ?>/" + pointer + ".jpg";
	sacred.addEventListener('click', function () {
		if (pointer === pages) {
			pointer = 0;
		} else {
			pointer++;
		}
		sacred.src = "https://static.mieinstance.cf/hentai/pururin/<?php print $dt['id']; ?>/" + (pointer) + ".jpg";
	});
</script>
</body>
</html>
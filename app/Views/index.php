<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="Hentai, Doujinshi, Manga, Free, Porn, Anime, Sex, Cartoon">
    <meta property="og:locale" content="en_US"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="Free Online Hentai Manga and Doujinshi Reader"/>
	<title>Hentai</title>
</head>
<body>
<center>
<div id="bound"></div>
</center>
<script type="text/javascript">
	function buildData(data)
	{
		var x, r = "<table>", i = 0, wd;
		for (x in data) {
			wd = "<td><a href=\"http://hentai.mieinstance.cf/api.php?read="+data[x]['id']+"\"><img alt=\""+data[x]['title']+"\" src=\"http://static.mieinstance.cf/hentai/pururin/"+data[x]['id']+"/cover.jpg\"></a></td>";
			if (i % 5 === 0 || i === 0 ) {
				r += (i > 0 ? "</tr>" : "") + "<tr>" + wd;
			} else {
				r += wd;
			}
		}
		return r;
	}
	var ch = new XMLHttpRequest();
		ch.onreadystatechange = function () {
			if (this.readyState === 4) {
				document.getElementById('bound').innerHTML = buildData(JSON.parse(this.responseText));
			}
		};
		ch.open("GET", "http://hentai.mieinstance.cf/api.php?page=newest");
		ch.send(null);
</script>
</body>
</html>
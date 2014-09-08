<html>
<head>
	<title>My MVC Framework</title>
	<link rel="stylesheet" href="<?php echo PATH_CSS_PAGE ?>bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo PATH_CSS_PAGE ?>font-awesome.min.css">
</head>
<body>
	<div id="container">
		<?php
			if(isset($template)) include_once BASE_VIEW . $template;
		?>
	</div>
</body>
</html>
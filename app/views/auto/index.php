<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Auto Module</title>
	<link rel="stylesheet" type="text/css" href="/public/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/public/css/font-awesome.min.css">
	<script type="text/javascript" src="public/js/jquery.1.10.2.min.js"></script>
	<style type="text/css">
		.container {
			padding-top: 30px;
		}
	</style>
</head>
<body>
	<div class="container">
		<form class="form form-horizontal" method="GET" action="">
			<div class="form-group">
				<div class="col-sm-2 control-label">Table name:</div>
				<div class="col-sm-6">
					<input class="form-control" type="text" name="table"></input>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 col-sm-offset-2">
					<input type="hidden" name="action" value="update">
					<button type="submit" class="btn btn-primary">Make</button>
				</div>
			</div>
		</form>

		<form method="POST" action="/auto-post-step-2">
			<input type="hidden" name="table" value="<?php echo Input::get('table') ?>">
		<?php foreach($rowFields as $key => $field) : ?>
			<div class="form-group row" id="control-<?php echo $key ?>">
				<input type="hidden" name="field_names[]" value="<?php echo $field['Field'] ?>">
				<div class="col-sm-2"><?php echo $field['Field'] ?></div>
				<div class="col-sm-3">
					<input class="form-control" name="field_titles[]" required></input>
				</div>
				<div class="col-sm-6">
					<select class="form-control" name="field_controls[]" required>
						<option value="1">--Chọn kiểu control--</option>
						<option value="1">Text</option>
						<option value="2">Password</option>
						<option value="3">Hidden</option>
						<option value="4">Select</option>
						<option value="5">Textarea</option>
						<option value="6">Checkbox</option>
						<option value="7">Option</option>
						<option value="8">File</option>
						<option value="9">Date</option>
					</select>
				</div>
				<div class="col-sm-1">
					<button data-target="#control-<?php echo $key ?>" class="ignore-control"><i class="fa fa-trash-o"></i></button>
				</div>
			</div>
		<?php endforeach; ?>
			<div class="form-group">
				<div class="col-sm-2 col-sm-offset-2">
					<button type="submit" class="btn btn-primary">Update</button>
				</div>
			</div>
		</form>
	</div>

	<script type="text/javascript">
		$(function() {
			$('.ignore-control').click(function() {
				var $this = $(this);
				$($this.data('target')).remove();
				return false;
			});
		});
	</script>
</body>
</html>
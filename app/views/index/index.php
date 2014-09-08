<table class="table table-hover">
	<thead>
		<th>ID</th>
		<th>Name</th>
		<th>Email</th>
		<th>Edit</th>
		<th>Active</th>
		<th>Del</th>
	</thead>
	<tbody>
		<?php foreach($users as $user) : ?>
		<tr>
			<td><?php echo $user['id'] ?></td>
			<td><?php echo $user['full_name'] ?></td>
			<td><?php echo $user['email'] ?></td>
			<th><a href="" class="fa fa-edit"></a></th>
			<th><a href="" class="fa fa-square-o"></a></th>
			<th><a href="" class="fa fa-trash-o"></a></th>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php include('db_connect.php');?>

<div class="container-fluid">
	
	<br><br><div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
			<form action="" id="manage-product">
				<div class="card">
					<div class="card-header">
						   <h5>Product Form</h5>
				  	</div>
					<div class="card-body">
							<input type="hidden" name="id">
							<div class="form-group">
								<label class="control-label">Item Code</label>
								<input type="text" class="form-control form-control-sm" name="item_code">
								<small><i>Leave this blank to auto-generate a code.</i></small>
							</div>
							<div class="form-group">
								<label class="control-label">Name</label>
								<input type="text" class="form-control form-control-sm" name="name">
							</div>
							<div class="form-group">
								<label class="control-label">Description</label>
								<textarea name="description" id="description" cols="30" rows="4" class="form-control form-control-sm"></textarea>
							</div>
							<div class="form-group">
								<label class="control-label">Material Type</label>
								<select name="size" id="size" class="custom-select custom-select-sm">
									<option>Cotton</option>
									<option>Sitan</option>
									<option>Rayon</option>
									<option>Silk</option>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label">Price</label>
								<input type="number" class="form-control form-control-sm text-right" name="price">
							</div>
					</div>
							
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12">
								<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Save</button>
								<button class="btn btn-sm btn-default col-sm-3" type="button" onclick="$('#manage-product').get(0).reset()"> Cancel</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<b><h5>Product List</h5></b>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">S.no</th>
									<th class="text-center">Item Code</th>
									<th class="text-center">Product Info.</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$product = $conn->query("SELECT * FROM items order by id asc");
								while($row=$product->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										<p><b><?php echo $row['item_code'] ?></b></p>
									</td>
									<td class="">
										<p>Name: <b><?php echo $row['name'] ?></b></p>
										<p>Price: <b><?php echo number_format($row['price'],2) ?></b></p>
										<p>Material type: <b><?php echo $row['size'] ?></b></p>
										<p>Description: <b><?php echo $row['description'] ?></b></p>
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-primary edit_product" type="button" data-json='<?php echo json_encode($row) ?>'>Edit</button>
										<button class="btn btn-sm btn-danger delete_product" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p {
		margin:unset;
	}
	.custom-switch{
		cursor: pointer;
	}
	.custom-switch *{
		cursor: pointer;
	}
</style>
<script>
	$('#manage-product').on('reset',function(){
		$('input:hidden').val('')
		$('.select2').val('').trigger('change')
	})
	
	$('#manage-product').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_product',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully added",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
				else if(resp==2){
					alert_toast("Data successfully updated",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	})
	$('.edit_product').click(function(){
		start_load()
		var data = $(this).attr('data-json');
			data = JSON.parse(data)
		var cat = $('#manage-product')
		cat.get(0).reset()
		cat.find("[name='id']").val(data.id)
		cat.find("[name='item_code']").val(data.item_code)
		cat.find("[name='name']").val(data.name)
		cat.find("[name='description']").val(data.description)
		cat.find("[name='price']").val(data.price)
		cat.find("[name='size']").val(data.size)
		end_load()
	})
	$('.delete_product').click(function(){
		_conf("Are you sure to delete this product?","delete_product",[$(this).attr('data-id')])
	})
	function delete_product($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_product',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
	$('table').dataTable()
</script>
@if(!empty($errors))
	<div class="ui grid">
		<div class="eight wide column centered">
			<h4>Form Errors</h4>
			
			<div class="ui red segment">
			  <div class="ui relaxed divided list">
				  @foreach ($errors as $row)
		            	@foreach($row as $msg)
		                	<div class="item">
			                	<div class="content">
				                	<div class="header">{{ $msg }}</div>
			                	</div>
		                	</div>
		                @endforeach
		            @endforeach
			  </div>
			</div>
		</div>
	</div>
@endif
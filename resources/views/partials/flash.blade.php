@if(!empty($success))
    <div class="ui success message">
        <div class="header">Good news everyone!</div>
		<p>{{ $success }}</p>
    </div>
@endif

@if(session('success'))
    <div class="ui success message">
    	<div class="header">Good news everyone!</div>
		<p>{{ session('success') }}</p>
    </div>
@endif

@if(!empty($error))
    <div class="ui error message">
    	<div class="header">Whoops, we got a problem!</div>
		<p>{{ $error }}</p>
    </div>
@endif

@if(session('error'))
    <div class="ui error message">
    	<div class="header">Whoops, we got a problem!</div>
		<p>{{ session('error') }}</p>
    </div>
@endif
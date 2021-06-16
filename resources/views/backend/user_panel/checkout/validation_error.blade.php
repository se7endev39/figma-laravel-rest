@extends('layouts.app')

@section('content')
<div class="container-fluid mt-10">
	<div class="row">
		<div class="col-lg-6 offset-lg-3">
			<div class="card">
				<div class="card-body">
					@foreach($validation_errors->all() as $err)
						<div class="alert alert-danger">
							<p>{{ $err }}</p>
						</div>
					@endforeach
				</div>
		    </div>
		</div>
	</div>
</div>
@endsection

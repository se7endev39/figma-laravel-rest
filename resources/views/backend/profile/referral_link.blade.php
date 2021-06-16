@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="link"></i></div>
				<span>{{ _lang('My Referral Link') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title panel-title">{{ _lang('My Referral Link') }}</h4>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Referral Link') }}</label>
								<input type="text" class="form-control" value="{{ url('register?ref=' . md5(Auth::id())) }}" readonly="true">
							</div>
						</div>	
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>
@endsection


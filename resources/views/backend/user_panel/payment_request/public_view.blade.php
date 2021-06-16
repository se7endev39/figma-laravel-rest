<!DOCTYPE html>
<html>
	<head>
		<title>{{ _lang('Payment Request') }}</title>
	</head>
	<link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
	<style type="text/css">
	   body{background-color:#e2e2e2;font-family:Poppins,sans-serif}#container{max-width:600px;padding:40px 60px;margin:auto;background:#f3f3f3;border-top:4px solid #34495e}#company-name{display:inline-block;font-size:32px;}#receipt{font-size:18px}#receipt td{padding:4px 10px 4px 0}#footer{max-width:600px;padding:30px 60px;margin:auto;background:#d4d4d4;color:#545454}#footer p{line-height:12px}.btn-view{background:#0072ff;text-decoration:none; padding:8px 15px;color:#fff; border-radius:5px; margin-top:10px; display:inline-block; font-size: 16px; margin-top: 20px;}
	</style>
	<body>
	   
	   <div id="container">
			<h2 id="company-name">{{ get_option('company_name') }}</h2>
			
			<h2>{{ _lang('Payment Request Details') }}</h2>
			<table id="receipt">
			    <tr>
				  <td><b>{{ _lang('Sender Name') }} : </b></td><td>{{ $paymentrequest->sender->first_name.' '.$paymentrequest->sender->last_name }}</td>
				</tr>
				<tr>
				  <td><b>{{ _lang('Sender Email') }} : </b></td><td>{{ $paymentrequest->sender->email }}</td>
				</tr>
				<tr>
				  <td><b>{{ _lang('Description') }} : </b></td><td>{{ $paymentrequest->description }}</td>
				</tr>
				<tr>				
				  <td><b>{{ _lang('Status') }} : </b></td><td>{{ ucwords($paymentrequest->status) }}</td>
				</tr>
				<tr>				
				  <td><b>{{ _lang('Amount') }} : </b></td><td>{{ $paymentrequest->account->account_type->currency->name.' '.$paymentrequest->amount }}</td>
				</tr>
			</table>
			
			@if($paymentrequest->status != 'complete')
				<a href="{{ url('user/payment_request/pay/' . encrypt($paymentrequest->id)) }}" class="btn-view">{{ _lang('Pay Now') }}</a>
		    @endif
		</div>
		
		<div id="footer" style="text-align:center">
	       <img src="{{ get_logo() }}" style="max-width:150px"/>
		   <p>{!! get_option('copyright') !!}</p>
	    </div>
	</body>
</html>

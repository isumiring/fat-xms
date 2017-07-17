Hi {{ $seller_info['name'] }},<br/><br/>

You have new order/booking:<br/><br/> 
<table border="1">
	<thead>
		<tr>
			<th>Name</th>
			<th>Start Date</th>
			<th>Start Time</th>
			<th>Finish Date</th>
			<th>Finish Time</th>
			<th>Cost</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>{{ $data_item['name'] }}</td>
			<td>{{ $data['order_date'] }}</td>
			<td>{{ $data['order_start_time'] }} - {{ $data['order_end_time'] }}</td>
			<td>{{ $data['finish_date'] }}</td>
			<td>{{ $data['finish_start_time'] }} - {{ $data['finish_end_time'] }}</td>
			<td>{{ $data['cost'] }}</td>
		</tr>
	</tbody>
</table>
<p>
	<strong>NOTE:</strong><br/>
	{{ $data['note'] }}
</p>
<table class="table table-bordered table-striped">
    <thead>
    <tr>
    		<th>Income Date </th>
		<th>Station Id </th>
		<th>Category Id </th>
		<th>Amount </th>
		<th>Description </th>
		<th>&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    @foreach($records as $record)
    <tr>	 	<td> {{$record->income_date }} </td>
	 	<td> {{$record->station_id }} </td>
	 	<td> {{$record->category_id }} </td>
	 	<td> {{$record->amount }} </td>
	 	<td> {{$record->description }} </td>
	<td><a class="btn btn-secondary" href="{{route('income.show',$record->id)}}">
    <span class="fa fa-eye"></span>
</a><a class="btn btn-secondary" href="{{route('income.edit',$record->id)}}">
    <span class="fa fa-pencil"></span>
</a>
<form onsubmit="return confirm('Are you sure you want to delete?')"
      action="{{route('income.destroy',$record->id)}}"
      method="post"
      style="display: inline">
    {{csrf_field()}}
    {{method_field('DELETE')}}
    <button type="submit" class="btn btn-secondary cursor-pointer">
        <i class="text-danger fa fa-remove"></i>
    </button>
</form></td></tr>

    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td colspan="3">
            {{{$records->render()}}}
        </td>
    </tr>
    </tfoot>
</table>
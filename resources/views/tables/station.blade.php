<table class="table table-bordered table-striped">
    <thead>
    <tr>
    		<th>Category Id </th>
		<th>Name </th>
		<th>Code </th>
		<th>Company Id </th>
		<th>Manager Id </th>
		<th>Location </th>
		<th>Latitude </th>
		<th>Longitude </th>
		<th>Phone </th>
		<th>Opening Hours </th>
		<th>Is Active </th>
		<th>Is Deleted </th>
		<th>&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    @foreach($records as $record)
    <tr>	 	<td> {{$record->category_id }} </td>
	 	<td> {{$record->name }} </td>
	 	<td> {{$record->code }} </td>
	 	<td> {{$record->company_id }} </td>
	 	<td> {{$record->manager_id }} </td>
	 	<td> {{$record->location }} </td>
	 	<td> {{$record->latitude }} </td>
	 	<td> {{$record->longitude }} </td>
	 	<td> {{$record->phone }} </td>
	 	<td> {{$record->opening_hours }} </td>
	 	<td> {{$record->is_active }} </td>
	 	<td> {{$record->is_deleted }} </td>
	<td><a class="btn btn-secondary" href="{{route('stations.show',$record->id)}}">
    <span class="fa fa-eye"></span>
</a><a class="btn btn-secondary" href="{{route('stations.edit',$record->id)}}">
    <span class="fa fa-pencil"></span>
</a>
<form onsubmit="return confirm('Are you sure you want to delete?')"
      action="{{route('stations.destroy',$record->id)}}"
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
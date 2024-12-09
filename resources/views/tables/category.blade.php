<table class="table table-bordered table-striped">
    <thead>
    <tr>
    		<th>Name </th>
		<th>Slug </th>
		<th>Description </th>
		<th>Parent Id </th>
		<th>&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    @foreach($records as $record)
    <tr>	 	<td> {{$record->name }} </td>
	 	<td> {{$record->slug }} </td>
	 	<td> {{$record->description }} </td>
	 	<td> {{$record->parent_id }} </td>
	<td><a class="btn btn-secondary" href="{{route('categories.show',$record->id)}}">
    <span class="fa fa-eye"></span>
</a><a class="btn btn-secondary" href="{{route('categories.edit',$record->id)}}">
    <span class="fa fa-pencil"></span>
</a>
<form onsubmit="return confirm('Are you sure you want to delete?')"
      action="{{route('categories.destroy',$record->id)}}"
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
<div class="card card-default">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-9">
                <a href="{{route('stations.show',$record->id)}}"> {{$record->id}}</a>
            </div>
            <div class="col-sm-3 text-right">
                <div class="btn-group">
                    <a class="btn btn-secondary" href="{{route('stations.edit',$record->id)}}">
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
</form>
                </div>
            </div>
        </div>
    </div>
    <div class="card-block">
        <table class="table table-bordered table-striped">
            <tbody>
            		<tr>
			<th>Category Id</th>
			<td>{{$record->category_id}}</td>
		</tr>
		<tr>
			<th>Name</th>
			<td>{{$record->name}}</td>
		</tr>
		<tr>
			<th>Code</th>
			<td>{{$record->code}}</td>
		</tr>
		<tr>
			<th>Company Id</th>
			<td>{{$record->company_id}}</td>
		</tr>
		<tr>
			<th>Manager Id</th>
			<td>{{$record->manager_id}}</td>
		</tr>
		<tr>
			<th>Location</th>
			<td>{{$record->location}}</td>
		</tr>
		<tr>
			<th>Latitude</th>
			<td>{{$record->latitude}}</td>
		</tr>
		<tr>
			<th>Longitude</th>
			<td>{{$record->longitude}}</td>
		</tr>
		<tr>
			<th>Phone</th>
			<td>{{$record->phone}}</td>
		</tr>
		<tr>
			<th>Opening Hours</th>
			<td>{{$record->opening_hours}}</td>
		</tr>
		<tr>
			<th>Is Active</th>
			<td>{{$record->is_active}}</td>
		</tr>
		<tr>
			<th>Is Deleted</th>
			<td>{{$record->is_deleted}}</td>
		</tr>

            </tbody>
        </table>
    </div>
</div>

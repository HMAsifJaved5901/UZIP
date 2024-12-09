<div class="card card-default">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-9">
                <a href="{{route('companies.show',$record->id)}}"> {{$record->id}}</a>
            </div>
            <div class="col-sm-3 text-right">
                <div class="btn-group">
                    <a class="btn btn-secondary" href="{{route('companies.edit',$record->id)}}">
    <span class="fa fa-pencil"></span>
</a>
                    <form onsubmit="return confirm('Are you sure you want to delete?')"
      action="{{route('companies.destroy',$record->id)}}"
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
			<th>Company Code</th>
			<td>{{$record->company_code}}</td>
		</tr>
		<tr>
			<th>Company Name</th>
			<td>{{$record->company_name}}</td>
		</tr>
		<tr>
			<th>Company Address</th>
			<td>{{$record->company_address}}</td>
		</tr>
		<tr>
			<th>Company Logo</th>
			<td>{{$record->company_logo}}</td>
		</tr>
		<tr>
			<th>Is Active</th>
			<td>{{$record->is_active}}</td>
		</tr>

            </tbody>
        </table>
    </div>
</div>

<form action="{{isset($route)?$route:route('stations.store')}}" method="POST" >
    {{csrf_field()}}
    <input type="hidden" name="_method" value="{{isset($method)?$method:'POST'}}"/>
        <div class="form-group">
        <label for="category_id">Category Id</label>
        <input type="number" class="form-control {{ $errors->has('category_id') ? ' is-invalid' : '' }}" name="category_id" id="category_id" value="{{old('category_id',$model->category_id)}}" placeholder="" required="required" >
          @if($errors->has('category_id'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('category_id') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="name" value="{{old('name',$model->name)}}" placeholder="" maxlength="100" required="required" >
          @if($errors->has('name'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('name') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="code">Code</label>
        <input type="text" class="form-control {{ $errors->has('code') ? ' is-invalid' : '' }}" name="code" id="code" value="{{old('code',$model->code)}}" placeholder="" maxlength="100" required="required" >
          @if($errors->has('code'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('code') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="company_id">Company Id</label>
        <input type="number" class="form-control {{ $errors->has('company_id') ? ' is-invalid' : '' }}" name="company_id" id="company_id" value="{{old('company_id',$model->company_id)}}" placeholder="" required="required" >
          @if($errors->has('company_id'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('company_id') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="manager_id">Manager Id</label>
        <input type="number" class="form-control {{ $errors->has('manager_id') ? ' is-invalid' : '' }}" name="manager_id" id="manager_id" value="{{old('manager_id',$model->manager_id)}}" placeholder="" required="required" >
          @if($errors->has('manager_id'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('manager_id') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="location">Location</label>
        <input type="text" class="form-control {{ $errors->has('location') ? ' is-invalid' : '' }}" name="location" id="location" value="{{old('location',$model->location)}}" placeholder="" maxlength="255" required="required" >
          @if($errors->has('location'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('location') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="latitude">Latitude</label>
        <input type="text" class="form-control {{ $errors->has('latitude') ? ' is-invalid' : '' }}" name="latitude" id="latitude" value="{{old('latitude',$model->latitude)}}" placeholder="" required="required" >
          @if($errors->has('latitude'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('latitude') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="longitude">Longitude</label>
        <input type="text" class="form-control {{ $errors->has('longitude') ? ' is-invalid' : '' }}" name="longitude" id="longitude" value="{{old('longitude',$model->longitude)}}" placeholder="" required="required" >
          @if($errors->has('longitude'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('longitude') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="phone">Phone</label>
        <input type="text" class="form-control {{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" id="phone" value="{{old('phone',$model->phone)}}" placeholder="" maxlength="15" >
          @if($errors->has('phone'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('phone') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="opening_hours">Opening Hours</label>
        <input type="text" class="form-control {{ $errors->has('opening_hours') ? ' is-invalid' : '' }}" name="opening_hours" id="opening_hours" value="{{old('opening_hours',$model->opening_hours)}}" placeholder="" maxlength="50" >
          @if($errors->has('opening_hours'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('opening_hours') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="is_active">Is Active</label>
        <input type="number" class="form-control {{ $errors->has('is_active') ? ' is-invalid' : '' }}" name="is_active" id="is_active" value="{{old('is_active',$model->is_active)}}" placeholder="" >
          @if($errors->has('is_active'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('is_active') }}</strong>
    </div>
  @endif 
    </div>

<div class="form-check">
    <input class="form-check-input {{ $errors->has('is_deleted') ? ' is-invalid' : '' }}" type="checkbox" value="1"  name="is_deleted"
           id="is_deleted">
    <label class="form-check-label" for="is_deleted">
        Is Deleted
    </label>
      @if($errors->has('is_deleted'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('is_deleted') }}</strong>
    </div>
  @endif
</div>


    <div class="form-group text-right ">
        <input type="reset" class="btn btn-default" value="Clear"/>
        <input type="submit" class="btn btn-primary" value="Save"/>

    </div>
</form>
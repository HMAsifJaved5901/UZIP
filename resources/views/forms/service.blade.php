<form action="{{isset($route)?$route:route('services.store')}}" method="POST" >
    {{csrf_field()}}
    <input type="hidden" name="_method" value="{{isset($method)?$method:'POST'}}"/>
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
        <label for="description">Description</label>
        <input type="text" class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" id="description" value="{{old('description',$model->description)}}" placeholder="" maxlength="255" >
          @if($errors->has('description'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('description') }}</strong>
    </div>
  @endif 
    </div>

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
        <label for="is_active">Is Active</label>
        <input type="number" class="form-control {{ $errors->has('is_active') ? ' is-invalid' : '' }}" name="is_active" id="is_active" value="{{old('is_active',$model->is_active)}}" placeholder="" >
          @if($errors->has('is_active'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('is_active') }}</strong>
    </div>
  @endif 
    </div>


    <div class="form-group text-right ">
        <input type="reset" class="btn btn-default" value="Clear"/>
        <input type="submit" class="btn btn-primary" value="Save"/>

    </div>
</form>
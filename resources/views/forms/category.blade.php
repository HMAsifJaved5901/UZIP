<form action="{{isset($route)?$route:route('categories.store')}}" method="POST" >
    {{csrf_field()}}
    <input type="hidden" name="_method" value="{{isset($method)?$method:'POST'}}"/>
        <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="name" value="{{old('name',$model->name)}}" placeholder="" maxlength="255" required="required" >
          @if($errors->has('name'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('name') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="slug">Slug</label>
        <input type="text" class="form-control {{ $errors->has('slug') ? ' is-invalid' : '' }}" name="slug" id="slug" value="{{old('slug',$model->slug)}}" placeholder="" maxlength="255" required="required" >
          @if($errors->has('slug'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('slug') }}</strong>
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
        <label for="parent_id">Parent Id</label>
        <input type="number" class="form-control {{ $errors->has('parent_id') ? ' is-invalid' : '' }}" name="parent_id" id="parent_id" value="{{old('parent_id',$model->parent_id)}}" placeholder="" >
          @if($errors->has('parent_id'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('parent_id') }}</strong>
    </div>
  @endif 
    </div>


    <div class="form-group text-right ">
        <input type="reset" class="btn btn-default" value="Clear"/>
        <input type="submit" class="btn btn-primary" value="Save"/>

    </div>
</form>
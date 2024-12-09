<form action="{{isset($route)?$route:route('lookup_values.store')}}" method="POST" >
    {{csrf_field()}}
    <input type="hidden" name="_method" value="{{isset($method)?$method:'POST'}}"/>
        <div class="form-group">
        <label for="type">Type</label>
        <input type="text" class="form-control {{ $errors->has('type') ? ' is-invalid' : '' }}" name="type" id="type" value="{{old('type',$model->type)}}" placeholder="" maxlength="100" required="required" >
          @if($errors->has('type'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('type') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="value">Value</label>
        <input type="text" class="form-control {{ $errors->has('value') ? ' is-invalid' : '' }}" name="value" id="value" value="{{old('value',$model->value)}}" placeholder="" maxlength="100" required="required" >
          @if($errors->has('value'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('value') }}</strong>
    </div>
  @endif 
    </div>

<div class="form-group">
    <label for="description">Description</label>
    <textarea id="description" name="description" class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" rows="3">{{old('description',$model->description)}}</textarea>
      @if($errors->has('description'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('description') }}</strong>
    </div>
  @endif
</div> 


    <div class="form-group text-right ">
        <input type="reset" class="btn btn-default" value="Clear"/>
        <input type="submit" class="btn btn-primary" value="Save"/>

    </div>
</form>
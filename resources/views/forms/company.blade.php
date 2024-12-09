<form action="{{isset($route)?$route:route('companies.store')}}" method="POST" >
    {{csrf_field()}}
    <input type="hidden" name="_method" value="{{isset($method)?$method:'POST'}}"/>
        <div class="form-group">
        <label for="company_code">Company Code</label>
        <input type="text" class="form-control {{ $errors->has('company_code') ? ' is-invalid' : '' }}" name="company_code" id="company_code" value="{{old('company_code',$model->company_code)}}" placeholder="" required="required" >
          @if($errors->has('company_code'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('company_code') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="company_name">Company Name</label>
        <input type="text" class="form-control {{ $errors->has('company_name') ? ' is-invalid' : '' }}" name="company_name" id="company_name" value="{{old('company_name',$model->company_name)}}" placeholder="" maxlength="255" required="required" >
          @if($errors->has('company_name'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('company_name') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="company_address">Company Address</label>
        <input type="text" class="form-control {{ $errors->has('company_address') ? ' is-invalid' : '' }}" name="company_address" id="company_address" value="{{old('company_address',$model->company_address)}}" placeholder="" maxlength="255" >
          @if($errors->has('company_address'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('company_address') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="company_logo">Company Logo</label>
        <input type="text" class="form-control {{ $errors->has('company_logo') ? ' is-invalid' : '' }}" name="company_logo" id="company_logo" value="{{old('company_logo',$model->company_logo)}}" placeholder="" maxlength="255" >
          @if($errors->has('company_logo'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('company_logo') }}</strong>
    </div>
  @endif 
    </div>

<div class="form-check">
    <input class="form-check-input {{ $errors->has('is_active') ? ' is-invalid' : '' }}" type="checkbox" value="1"  name="is_active"
           id="is_active">
    <label class="form-check-label" for="is_active">
        Is Active
    </label>
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
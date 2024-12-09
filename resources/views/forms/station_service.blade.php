<form action="{{isset($route)?$route:route('station_services.store')}}" method="POST" >
    {{csrf_field()}}
    <input type="hidden" name="_method" value="{{isset($method)?$method:'POST'}}"/>
        <div class="form-group">
        <label for="station_id">Station Id</label>
        <input type="number" class="form-control {{ $errors->has('station_id') ? ' is-invalid' : '' }}" name="station_id" id="station_id" value="{{old('station_id',$model->station_id)}}" placeholder="" required="required" >
          @if($errors->has('station_id'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('station_id') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="service_id">Service Id</label>
        <input type="number" class="form-control {{ $errors->has('service_id') ? ' is-invalid' : '' }}" name="service_id" id="service_id" value="{{old('service_id',$model->service_id)}}" placeholder="" required="required" >
          @if($errors->has('service_id'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('service_id') }}</strong>
    </div>
  @endif 
    </div>


    <div class="form-group text-right ">
        <input type="reset" class="btn btn-default" value="Clear"/>
        <input type="submit" class="btn btn-primary" value="Save"/>

    </div>
</form>
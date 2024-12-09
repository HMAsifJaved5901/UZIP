<form action="{{isset($route)?$route:route('income.store')}}" method="POST" >
    {{csrf_field()}}
    <input type="hidden" name="_method" value="{{isset($method)?$method:'POST'}}"/>
    <div class="form-group">
    <label for="income_date">Income Date</label>
    <div class="input-group">
        <input type="date" class="form-control {{ $errors->has('income_date') ? ' is-invalid' : '' }}" name="income_date" id="income_date"
               value="{{old('income_date',$model->income_date)}}"
               placeholder="" required="required" >
        <div class="input-group-addon">
            <label for="income_date" class="fa fa-calendar">
            </label>
        </div>
    </div>
      @if($errors->has('income_date'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('income_date') }}</strong>
    </div>
  @endif
</div>

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
        <label for="category_id">Category Id</label>
        <input type="number" class="form-control {{ $errors->has('category_id') ? ' is-invalid' : '' }}" name="category_id" id="category_id" value="{{old('category_id',$model->category_id)}}" placeholder="" required="required" >
          @if($errors->has('category_id'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('category_id') }}</strong>
    </div>
  @endif 
    </div>

    <div class="form-group">
        <label for="amount">Amount</label>
        <input type="text" class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}" name="amount" id="amount" value="{{old('amount',$model->amount)}}" placeholder="" required="required" >
          @if($errors->has('amount'))
    <div class="invalid-feedback">
        <strong>{{ $errors->first('amount') }}</strong>
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


    <div class="form-group text-right ">
        <input type="reset" class="btn btn-default" value="Clear"/>
        <input type="submit" class="btn btn-primary" value="Save"/>

    </div>
</form>
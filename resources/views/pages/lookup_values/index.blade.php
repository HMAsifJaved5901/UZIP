@extends('layouts.app')
@section('breadcrumb')
<li class="breadcrumb-item">
    lookup_values
</li>
@endsection
@section('header')
<h3><i class="fa fa-list"></i> lookup_values </h3>
@endsection
@section('tools')
<a class="btn btn-secondary" href="{{route('lookup_values.create')}}">
    <span class="fa fa-plus"></span>
</a>
@endsection

@section('content')
<div class="row">
    @foreach($records as $record)
    <div class="col-sm-6">
        @include('cards.lookup_value')
    </div>
    @endforeach
</div>
{!! $records->render() !!}
@endSection
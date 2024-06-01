@extends('layouts.master')

@section('title', 'Главная')

@section('content')
    <h1>Все товары</h1>

    <div class="form-group row">
    <div class="col-xs-3">
        @include('layouts.filters', ['route' => route('index')]);
    </div>
    <div class="col-xs-9">
        @foreach($products as $product)
            @include('layouts.card', compact('product'))
        @endforeach
    </div>
	{{$products->withQueryString()->links(('pagination::bootstrap-4'))}}
	</div>
@endsection

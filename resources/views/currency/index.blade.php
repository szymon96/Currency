@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <index :currencies="{{ json_encode($currencies) }}"></index>
@endsection
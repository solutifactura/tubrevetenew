@extends('tenant.layouts.app')

@section('content')

    <tenant-persons-index :type="{{ json_encode($type) }}" :api_service_token="{{ json_encode($api_service_token) }}"></tenant-persons-index>

@endsection
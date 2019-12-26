@extends('tenant.layouts.app')

@section('content')

    <tenant-documents-note :document_affected="{{ json_encode($document_affected) }}"></tenant-documents-note>

@endsection
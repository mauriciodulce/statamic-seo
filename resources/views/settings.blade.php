@extends('layout')

@section('content')

    <script>
        Statamic.Publish = {
            contentData: {!! json_encode($content_data) !!}
        };
    </script>

    <publish title="{{ $title }}"
             extra="{{ json_encode($extra) }}"
             fieldset-name="{{ $fieldset }}"
             content-type="addon"
    ></publish>

@endsection

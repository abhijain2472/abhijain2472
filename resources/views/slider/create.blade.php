@extends('layout')

@isset($slider['slider_id'])
    @php
    foreach ($slider as $key => $value) {
        $slid[$key] = $value;
    }
    @endphp
@endisset

@section('page-title', "$addText Slider")
@section('page-heading', "$addText Slider")
@section('page-description', $description)

@section('active-main-slider', 'active')
@section('active-main-slider-div', 'show')
@section('active-main-slider-add', 'active')

@section('admin-card-header')
    <div class="row justify-content-between">
        <div class="col-auto">
            <span>{{ $addText }} Slider Form</span>
        </div>
    </div>
@endsection

@section('admin-card-body')
    @if (Session::get('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> {{Session::get('success')}}
        </div>
    @endif

    @if (Session::get('fail'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Error!</strong> {{Session::get('fail')}}
        </div>
    @endif
    <div class="row justify-content-between">
        <div class="col-12">
            <form action="/post-form-slider/{{ $slid['slider_id'] }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row align-items-center validate" data-validation="required" validation-msg="Slider title is required.">
                    <div class="col-2">
                        <label for="slider_title">Slider Title</label>
                    </div>
                    <div class="col">
                        <input type="text" name="slider_title" id="slider_title" class="form-control" value="{{ $slid['slider_title'] }}">
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-2"></div>
                    @if ($slid['slider_image'])
                        {!! $slid['slider_image'] !!}
                    @endif
                </div>

                <div class="form-group row align-items-center">
                    <div class="col-2">
                        <label for="slider_image">Slider Image</label>
                    </div>
                    <div class="col">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="slider_image" name="slider_image">
                            <label class="custom-file-label" for="slider_image">Choose file</label>
                        </div>
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <div class="col-2">
                        <label for="slider_message">Slider Message</label>
                    </div>
                    <div class="col">
                        <textarea name="slider_message" id="slider_message" class="form-control">{{ $slid['slider_message'] }}</textarea>
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <div class="col-2">
                        <label for="sort_order">Sort Order</label>
                    </div>
                    <div class="col-auto">
                        <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ $slid['sort_order'] }}">
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <div class="col-2">
                        <label>Status</label>
                    </div>
                    <div class="col">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="status" name="status" {{ $slid['status'] }}>
                            <label class="custom-control-label" for="status"></label>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-auto">
                        <input type="submit" value="{{ $addText }} Slider" class="btn btn-outline-primary">
                        <input type="reset" class="btn btn-outline-secondary">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('internal-js')
    <script>
        $(document).ready(function () {
            $('#slider_image').change(function(event) {
                if($(this).val() == "") {
                    $("img#previewImg").fadeIn("fast").attr('src', "");
                    $("#previewDiv").hide();
                }
                var tmppath = URL.createObjectURL(event.target.files[0]);
                $("img#previewImg").fadeIn("fast").attr('src',URL.createObjectURL(event.target.files[0]));
            });
        });
    </script>
@endsection

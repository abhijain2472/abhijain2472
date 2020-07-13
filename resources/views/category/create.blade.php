@extends('layout')

@isset($category['category_id'])
    @php
    foreach ($category as $key => $value) {
        $cat[$key] = $value;
    }
    @endphp
@endisset

@section('page-title', "$addText Category")
@section('page-heading', "$addText Category")
@section('page-description', $description)

@section('active-main-category', 'active')
@section('active-main-category-div', 'show')
@section('active-main-category-add', 'active')

@section('admin-card-header')
    <div class="row justify-content-between">
        <div class="col-auto">
            <span>{{ $addText }} Category Form</span>
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
            <form action="/post-form-category/{{ $cat['category_id'] }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row align-items-center validate" data-validation="required" validation-msg="Category title is required.">
                    <div class="col-2">
                        <label for="name">Category Title</label>
                    </div>
                    <div class="col">
                        <input type="text" name="name" id="name" class="form-control" value="{{ $cat['name'] }}">
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-2"></div>
                    @if ($cat['icon'])
                        {!! $cat['icon'] !!}
                    @endif
                </div>

                <div class="form-group row align-items-center">
                    <div class="col-2">
                        <label for="icon">Category Icon</label>
                    </div>
                    <div class="col">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="icon" name="icon">
                            <label class="custom-file-label" for="icon">Choose file</label>
                        </div>
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <div class="col-2">
                        <label for="sort_order">Sort Order</label>
                    </div>
                    <div class="col-auto">
                        <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ $cat['sort_order'] }}">
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <div class="col-2">
                        <label>Status</label>
                    </div>
                    <div class="col">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="status" name="status" {{ $cat['status'] }}>
                            <label class="custom-control-label" for="status"></label>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-auto">
                        <input type="submit" value="{{ $addText }} Category" class="btn btn-outline-primary">
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
            $('#icon').change(function(event) {
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

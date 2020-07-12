@extends('layout')

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
    <div class="row justify-content-between">
        <div class="col-12">
            <form action="/opst-form-category" method="POST">
                <div class="form-group row align-items-center">
                    <div class="col-2">
                        <label>Category Title</label>
                    </div>
                    <div class="col">
                        <input type="text" name="name" id="name" class="form-control">
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <div class="col-2">
                        <label>Category Title</label>
                    </div>
                    <div class="col">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="icon" name="icon">
                            <label class="custom-file-label" for="icon">Choose file</label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
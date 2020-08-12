@extends('admin.master.index')
@section('style')
    <!-- begin::tagsinput -->
    <link rel="stylesheet" href="/admin/assets/vendors/tagsinput/bootstrap-tagsinput.css" type="text/css">
    <!-- end::tagsinput -->
    <!-- begin::select2 -->
    <link rel="stylesheet" href="/admin/assets/vendors/select2/css/select2.min.css" type="text/css">
    <!-- end::select2 -->
@endsection
@section('script')
    <!-- begin::input mask -->
    <script src="/admin/assets/vendors/tagsinput/bootstrap-tagsinput.js"></script>
    <script src="/admin/assets/js/examples/tagsinput.js"></script>
    <!-- end::input mask -->

    <!-- begin::select2 -->
    <script src="/admin/assets/vendors/select2/js/select2.min.js"></script>
    <script src="/admin/assets/js/examples/select2.js"></script>
    <!-- end::select2 -->
@endsection
@section('header')
    <div class="row">
        <div class="col-12">
            @if ($errors->any())
                <h5 style="color: #ffffff;background-color: #f50000;font-size: 12px;border-bottom:1px solid #000;margin-bottom: 0;border-radius: 4px 4px 0 0;padding:10px 10px;">
                    اطلاعات وارد شده ناقص بوده</h5>
                <div style="background-color: #3f51b5;border-top:1px solid #000;padding: 10px;border-radius:0 0 4px 4px;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li style="color: #ffffff;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
    <div>
        <h3>ویرایش دسته مقالات و اخبار</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">فهرست</li>
                <li class="breadcrumb-item"><a href="{{route('category_article.index')}}">دسته مقالات و اخبار</a></li>
                <li class="breadcrumb-item">ویرایش</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="card">
        <h5 class="card-header">ویرایش</h5>
        <div class="card-body">
            <form action="{{route('category_article.update',$categoryArticle->id)}}" method="post" class="needs-validation" novalidate=""
                  autocomplete="off">
                @csrf
                @method('patch')
                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <label for="validationCustom01">عنوان دسته</label>
                        <input type="text" class="form-control" id="validationCustom01" required="" name="title" value="{{$categoryArticle->title}}">
                        <div class="valid-feedback">
                            صحیح است!
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="validationCustom04">نامک</label>
                        <input type="text" class="form-control" id="validationCustom04" placeholder="نامک" required=""
                               name="slug" value="{{$categoryArticle->slug}}">
                        <div class="valid-feedback">
                            صحیح است!
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="validationCustom03">زیردسته</label>
                            <select class="form-control js-example-basic-single" dir="rtl" name="parent_id">
                                <option selected value="0">سردسته</option>
                                @foreach($category_articles as $item)
                                    @if($categoryArticle->id != $item->id)
                                    <option value="{{$item->id}}" @if($categoryArticle->parent_id == $item->id) selected @endif>{{$item->title}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="validationCustom03">زبان</label>
                            <select class="form-control js-example-basic-single" dir="rtl" name="lang">
                                <option @if($categoryArticle->lang == "fa") selected @endif value="fa">فارسی</option>
                                <option @if($categoryArticle->lang == "en") selected @endif value="en">انگلیسی</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="validationCustom02">برچسب</label>
                        <input type="text" class="form-control tagsinput" value="{{$categoryArticle->tags}}" name="tags">
                        <div class="valid-feedback">
                            صحیح است!
                        </div>
                    </div>
                </div>
                <div class="form-row form-group">
                    <div class="custom-control custom-checkbox custom-checkbox-success">
                        <input type="checkbox" class="custom-control-input" id="customCheck" checked name="status">
                        <label class="custom-control-label" for="customCheck">این دسته بندی مقالات و اخبار نمایش داده
                            شود؟</label>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">ذخیره درخواست</button>
            </form>
        </div>
    </div>
    <script>
                @if(session('error'))
        var error = "{{session('error')}}";
        Swal.fire({
            icon: 'error',
            title: 'ناموفق',
            text: error,
        });
                @elseif(session('message'))
        var message = "{{session('message')}}";
        Swal.fire({
            icon: 'success',
            title: 'موفقیت',
            text: message,
        });
        @endif
    </script>
    <script>
        //  Form Validation
        window.addEventListener('load', function () {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
            forms.submit();
        }, false);
        $(document).ready(function () {
            $('.parents').select2();
        });
    </script>
@endsection

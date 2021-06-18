@extends('template')
@section('content_header')

@endsection
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Product add</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="">Category</label>
                            <select class="form-control">
                                <option value="">-----------------</option>
                                @foreach($cate_list AS $cat_l)
                                    <optgroup label="{{$cat_l->c_name}}"></optgroup>

                                    @if($cat_l->children)
                                        @foreach($cat_l->children AS $child)
                                            <option value="{{$child->id}}">{{$child->c_name}}</option>
                                        @endforeach
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /.container-fluid -->
@endsection

@extends('template')
@section('content_header')

@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Categories</h5>
                    </div>
                    <div class="card-body">

                        <p class="card-text">
                            Some quick example text to build on the card title and make up the bulk of the card's
                            content.
                        </p>
                        <div class="card-option">
                            <a href="" class="btn btn-success">Add New</a>
                        </div>

                        <table class="table table-bordered table-hover dataTable">
                            <thead>
                            <tr>
                                <th>Category name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($category_list AS $cate_list)
                            <tr>
                                <td>{{$cate_list->c_name}}</td>
                                <td><a class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a></td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection

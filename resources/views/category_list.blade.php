@extends('template')
@section('content_header')

@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Categories</h5>

                        <div class="card-tools">

                            <a href="javascript:0" class="btn btn-primary" onclick="showAddNewView();">Add New</a>
                        </div>
                    </div>
                    <div class="card-body">

                        <table class="table table-bordered table-hover dataTable" id="category_tbl">
                            <thead>
                            <tr>
                                <th>Category name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
{{--                            @php echo"<pre>"; print_r($category_list); @endphp--}}
                            @foreach($category_list AS $cate_list)
                                <tr>
                                    <td>{{$cate_list->c_name}}</td>
                                    <td>
                                        @if($cate_list->is_active)
                                            <span class="text-white badge badge-success">Active</span>
                                        @else
                                            <span class="text-white badge badge-warning">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-primary btn-sm" onclick="getEditFormData('{{$cate_list->id}}')"><i class="fa fa-edit"></i></a>
                                        <a class="btn btn-primary btn-sm" onclick="removeCategory('{{$cate_list->id}}')"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>

                                @if ($cate_list->children)

                                        @foreach ($cate_list->children as $child)
                                            <tr>
                                                <td>{{ $cate_list->c_name.'/'.$child->c_name }}</td>
                                                <td>
                                                    @if($cate_list->is_active)
                                                        <span class="text-white badge badge-success">Active</span>
                                                    @else
                                                        <span class="text-white badge badge-warning">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a class="btn btn-primary btn-sm" onclick="getEditFormData('{{$cate_list->id}}')"><i class="fa fa-edit"></i></a>
                                                    <a class="btn btn-primary btn-sm" onclick="removeCategory('{{$cate_list->id}}')"><i class="fa fa-trash"></i></a>
                                                </td>
                                            </tr>

                                        @endforeach
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.row -->

        <!-- add Modal -->
        <div class="modal fade" id="add-category-model" tabindex="-1" data-backdrop="static" role="dialog"  aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal3">Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group" >
                                <label for="recipient-name" class="form-control-label">Parent Category</label>
                                <select class="form-control" id="parent_cate_drop_id">
                                    <option value=""></option>
                                    @foreach($parent_cate_list AS $pc_list)
                                        <option value="{{$pc_list->id}}">{{$pc_list->c_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Category Name:</label>
                                <input type="text" class="form-control" id="category_name">
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="submitAddNewForm(0)">Submit</button>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /.container-fluid -->

    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}" crossorigin="anonymous" />

    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>

    <script>
        var is_new = true;
        var cate_id = '';
        $(document).ready(function () {

            dataTable();
        });
        function dataTable() {

            $('#category_tbl').DataTable();
        }

        function showAddNewView() {

            $('#add-category-model').modal('show');
        }
        function clearFormFields() {

            $('#parent_cate_drop_id').val('');
            $('#category_name').val('');
            is_new = true;
        }

        function submitAddNewForm(id) {
            var url = '';
            if(is_new === true)
                url = '{{route('submitNewCategoryData')}}';
            else{
                id = cate_id;
                url = '{{route('submitEditCategoryData')}}';
            }

            $.ajax({
                type: "post",
                url: url,
                data:{
                    '_token':'{{csrf_token()}}',
                    'category_id': id,
                    'parent_cate_id':$.trim($('#parent_cate_drop_id').val()),
                    'category_name':$.trim($('#category_name').val())
                },
                dataType: 'json',
                success: function (data) {

                    if(data.status){

                        $('#add-category-model').modal('hide');
                        clearFormFields();
                        alert('success');

                        setTimeout(function () {
                            location.reload();
                        },300);
                    }else{
                        alert('Error = '+data.message);
                    }

                },
                error: function(data){

                    var errors = data.responseJSON.message;
                    if(typeof data.responseJSON.message == "undefined") errors = (data.responseJSON[0].message);

                    var errorList = "";

                    if(typeof data.responseJSON.message == "undefined"){
                        errorList +=  data.responseJSON[0].message + '';
                    }else {
                        $.each(errors, function (i, error) {
                            errorList += '' + error + '';
                        })
                    }
                    errorList +=""

                    alert('Need attention! '+errorList);
                }
            });
        }

        function getEditFormData(id) {
            cate_id = id;
            $.ajax({
                type: "get",
                url: '/get-edit-category-data/'+id,
                dataType: 'json',
                success: function (data) {

                    if(data.dataCount > 0){

                        clearFormFields();
                        is_new = false;
                        $('#parent_cate_drop_id').val(data.data[0].parent_id);
                        $('#category_name').val(data.data[0].c_name);
                        $('#add-category-model').modal('show');

                    }else{
                        alert('Error = '+data.message);
                    }

                },
                error: function(data){

                    var errors = data.responseJSON.message;
                    if(typeof data.responseJSON.message == "undefined") errors = (data.responseJSON[0].message);

                    var errorList = "";

                    if(typeof data.responseJSON.message == "undefined"){
                        errorList +=  data.responseJSON[0].message + '';
                    }else {
                        $.each(errors, function (i, error) {
                            errorList += '' + error + '';
                        })
                    }
                    errorList +=""

                    alert('Need attention! '+errorList);
                }
            });
        }

        function removeCategory(id) {

            var prom_val = confirm('Are you sure! you want to inactive this?')
            if(prom_val) {
                $.post("{{route('inactivateCategory')}}", {
                    '_token': '{{csrf_token()}}',
                    'category_id': id
                }, function (res) {

                    var json_val = res;

                    if (json_val['status']) {

                        setTimeout(function () {
                            location.reload();
                        }, 300);
                    } else {

                        alert('Error = ' + data.message);
                    }
                });
            }
        }
    </script>
@endsection

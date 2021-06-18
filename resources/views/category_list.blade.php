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
                            @foreach($category_list AS $cate_list)
                                <tr>
                                    <td>
                                        @if(empty($cate_list->parent_id))
                                            {{$cate_list->c_name}}
                                        @else
                                            @php echo $cate_list->subCateName.'<sub>sub</sub>' @endphp

                                        @endif

                                    </td>
                                    <td>
                                        @if($cate_list->is_active)
                                            <span class="text-white badge badge-success">Active</span>
                                        @else
                                            <span class="text-white badge badge-warning">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                                        <a class="btn btn-primary btn-sm" onclick="removeCategory('{{$cate_list->id}}')"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
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
                        <button type="button" class="btn btn-primary" onclick="submitAddNewForm()">Submit</button>
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
        $(document).ready(function () {

            dataTable();
        });
        function dataTable() {

            $('#category_tbl').DataTable();
            /*if ( $.fn.DataTable.isDataTable('#category_tbl') ) {
                $('#category_tbl').DataTable().destroy();
            }

            $('#category_tbl tbody').empty();

            var tbl = $('#category_tbl').DataTable( {
                // dom: "Bfrtip",
                paging: true,
                searchDelay: 350,
                pageLength: 10,
                "ordering": false,
                ajax: function ( data, callback, settings ) {

                    $.ajax({
                        url: "{{route('getCategoryDataTableList')}}",
                        dataType: 'text',
                        type: 'post',
                        contentType: 'application/x-www-form-urlencoded',
                        data: {
                            RecordsStart: data.start,
                            PageSize: data.length,
                            SearchTerm: data.search,
                            '_token':'{{csrf_token()}}',
                        },
                        beforeSend: function (jqXHR, settings) {

                            // self.StartLoading();
                        },
                        success: function( data, textStatus, jQxhr ){

                            var json_val = JSON.parse(data);
                            // console.log(json_val.TotalRecords);
                            setTimeout( function () {
                                callback({
                                    // draw: data.draw,
                                    data: json_val.Data,
                                    recordsTotal:  json_val.TotalRecords,
                                    recordsFiltered:  json_val.RecordsFiltered
                                });
                            }, 50 );
                        },
                        error: function( jqXhr, textStatus, errorThrown ){
                        }
                    });
                },
                serverSide: true,
                columns: [
                    { data: "c_name" },
                    {"sClass": "text-center",
                        mRender: function (data, type, row) {
                            if(row.is_active == 1)
                                return '<span class="text-white badge badge-success">Active</span>'
                            else if(row.is_active == 0)
                                return '<span class="text-white badge badge-warning">Inactive</span>'
                        }
                    },
                    {
                        data: "id", "sClass": "text-center", render: function ( data, type, row ) {
                            return '<i data-toggle="tooltip" data-placement="bottom" title="edit" class="fas fa-edit" style="cursor: pointer;" onclick="showEditView('+data+')"></i>' +
                                '<i data-toggle="tooltip" data-placement="bottom" title="Inactive" class="fas fa-eye-slash" style="cursor: pointer;margin-left: 10px;" onclick="showConfirmDialog('+data+');"></i>';
                        }
                    }
                ]
            } );*/
        }

        function showAddNewView() {

            $('#add-category-model').modal('show');
        }
        function clearFormFields() {

            $('#parent_cate_drop_id').val('');
            $('#category_name').val('');
        }

        function submitAddNewForm() {
            $.ajax({
                type: "post",
                url: '{{route('submitNewCategoryData')}}',
                data:{
                    '_token':'{{csrf_token()}}',
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
                        },350);
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
                        }, 350);
                    } else {

                        alert('Error = ' + data.message);
                    }
                });
            }
        }
    </script>
@endsection

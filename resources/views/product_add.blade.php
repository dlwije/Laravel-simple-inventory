@extends('template')
@section('content_header')

@endsection
@section('content')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

    <div class="container-fluid" >
        <div class="card" id="product_add_view">
            <div class="card-header">
                <h5 class="card-title">Product add</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" class="form-control" data-bind="value: pro_name">
                        </div>
                        <div class="form-group">
                            <label class="">Category</label>
                            <select class="form-control" id="category_drop_id" multiple >
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
                        <div class="form-group">
                            <label> Product Prices</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Lot No</label>
                            <input type="text" class="form-control" data-bind="value: lot_no">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Qty</label>
                            <input type="number" step="0.1" class="form-control" data-bind="value: pro_qty">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" step="0.1" class="form-control" data-bind="value: pro_price">
                        </div>
                    </div>
                    <div class="col-md-2" style="margin-top: 35px;">
                        <button type="button" class="btn btn-primary btn-sm" onclick="productAdd.ivm.addToColPriceGrid()"><i class="fas fa-plus"></i></button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Lot No</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody data-bind="foreach: product_price_list">
                            <tr>
                                <td data-bind="text: lot_no_grid"></td>
                                <td data-bind="text: pro_qty_grid"></td>
                                <td data-bind="text: pro_price_grid"></td>
                                <td class="text-center" ><i class="fas fa-trash" style="cursor: pointer;color: red;" data-bind="click: $parent.RemoveColPriceGridRow.bind($data, $index())"></i></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>File</label>
                            <input type="file" class="form-control" name="pro_photo" id="pro_photo" data-bind="fileInput: fileData2">
                        </div>
                    </div>
                    <div class="col-md-1" style="margin-top: 35px;">
                        <button class="btn btn-primary btn-sm" onclick="productAdd.ivm.addToPhotosGrid()" data-toggle="tooltip" data-placement="top" title="Add new document"><i class="fas fa-plus"></i></button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-responsive table-striped table-condensed">
                            <thead>
                            <tr>
                                <th>File</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody data-bind="foreach: product_photo_list">
                            <tr>
                                <td data-bind="text: photoDummy"></td>

                                <td class="text-center" ><i class="fas fa-trash" style="cursor: pointer;color: red;" data-bind="click: $parent.RemovePhotoGridRow.bind($data, $index())"></i></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-primary btn-sm" onclick="productAdd.ivm.submitProduct();">Submit</button>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /.container-fluid -->

    <script src="{{asset('/plugins/select2/js/select2.full.min.js')}}"></script>
    <link rel="stylesheet" href="https://rawgit.com/adrotec/knockout-file-bindings/master/knockout-file-bindings.css" crossorigin="anonymous" />
    <script src="https://rawgit.com/adrotec/knockout-file-bindings/master/knockout-file-bindings.js" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function () {
            // $('.select2').select2()

            //Initialize Select2 Elements
            $('#category_drop_id').select2({
                theme: 'bootstrap4'
            })
            productAdd.init();
        });

        productAdd = {

            ivm: null,
            init: function () {
                productAdd.ivm = new productAdd.productAddViewModel();
                ko.applyBindings(productAdd.ivm, $('#product_add_view')[0]);
                productAdd.ivm.designInit();
            },
            productAddViewModel: function () {

                var self = this;

                self.is_new = ko.observable(true);
                self.product_id = ko.observable("");
                self.category_ids = ko.observable("");
                self.pro_name = ko.observable("");

                self.lot_no = ko.observable("");
                self.pro_qty = ko.observable("");
                self.pro_price = ko.observable("");
                self.product_price_list = ko.observableArray("");

                self.photoFile = ko.observable("");
                self.photoFileFileObject = ko.observable("");
                self.photoFileDummy = ko.observable("");
                self.photoFileFormData = ko.observable("");
                self.product_photo_list = ko.observableArray("");

                self.fileData2 = ko.observable({
                    dataURL: ko.observable(),
                    text: ko.observable(),
                    file: ko.observable(),
                    // base64String: ko.observable(),
                });

                self.designInit = function () {

                    self.fileData2().dataURL.subscribe(function(dataURL){
                        self.photoFile(dataURL);
                    });
                    self.fileData2().file.subscribe(function(file){
                        self.photoFileFileObject(file);
                        self.photoFileDummy(file.name);
                        // console.log(file.get(0).files)
                    });
                }

                self.addToPhotosGrid = function () {

                    if(!self.photoFieldsValidation()){ return; }

                    var data = new FormData();
                    var files = $("#pro_photo").get(0).files;
                    data.append("file", files[0]);

                    self.photoFileFormData(data);

                    self.product_photo_list.push(new gridPhotoListRows());
                    self.clearPhotoFields();
                }

                self.photoFieldsValidation = function () {

                    if((self.photoFile() === "") || (typeof self.photoFile() === "undefined")){

                        alert('Required / Please fill the photo fields first!');
                        return false;
                    }
                    return true;
                }

                var gridPhotoListRows = function (obj) {
                    var item = this;

                    if(typeof obj ==="undefined"){

                        item.photo = (typeof self.photoFile() === "undefined" || self.photoFile() == null) ? ko.observable("") : ko.observable(self.photoFile());
                        item.photoDummy = (typeof self.photoFileDummy() === "undefined" || self.photoFileDummy() == null) ? ko.observable("") : ko.observable(self.photoFileDummy());
                        item.fileObject = (typeof self.photoFileFileObject() === "undefined" || self.photoFileFileObject() == null) ? ko.observable("") : ko.observable(self.photoFileFileObject());
                        item.fileFormData = (typeof self.photoFileFormData() === "undefined" || self.photoFileFormData() == null) ? ko.observable("") : ko.observable(self.photoFileFormData());
                        item.fileDbId = ko.observable("");
                    }

                };
                self.RemovePhotoGridRow = function (indexNo,rowData) {

                    self.product_photo_list.remove(rowData);
                }
                self.clearPhotoFields = function () {

                    self.photoFile("");
                    self.photoFileFileObject("");
                    self.photoFileDummy("");
                    self.photoFileFormData("");
                }

                self.submitProduct = function () {
                    self.category_ids($('#category_drop_id').val());

                    $.ajax({
                        type: "post",
                        url: '{{route('submitProductData')}}',
                        data:{
                            '_token':'{{csrf_token()}}',
                            'pro_id':self.product_id(),
                            'pro_name':self.pro_name(),
                            'category_ids':self.category_ids(),
                            'pro_price_list':ko.toJSON(self.product_price_list),
                            'pro_photo_list':ko.toJSON(self.product_photo_list),
                        },
                        dataType: 'json',
                        success: function (data) {
                            // hideLoading();

                            if(data.status){

                                alert('Success / '+data.message);
                                self.clearAllFields();
                            }else{
                                alert('Error / '+data.message);
                            }

                        },
                        error: function(data){

                            var errors = data.responseJSON.message;
                            if(typeof data.responseJSON.message == "undefined") errors = (data.responseJSON[0].message);

                            var errorList = "";

                            if(typeof data.responseJSON.message == "undefined"){
                                errorList += '' + data.responseJSON[0].message + '';
                            }else {
                                $.each(errors, function (i, error) {
                                    errorList += '' + error + '';
                                })
                            }
                            errorList +=""

                            alert('Need attention! / '+errorList);

                        }
                    });
                }

                self.addToColPriceGrid = function () {

                    // if(self.lot_no() != null){ alert('Please add some values'); return;}
                    self.product_price_list.push(new gridColPriceListRows());
                    self.clearPriceFields();
                }

                var gridColPriceListRows = function (obj) {
                    var item = this;

                    if(typeof obj ==="undefined"){

                        item.lot_no_grid = (typeof self.lot_no() === "undefined" || self.lot_no() == null) ? ko.observable("") : ko.observable(self.lot_no());
                        item.pro_qty_grid = (typeof self.pro_qty() === "undefined" || self.pro_qty() == null) ? ko.observable("") : ko.observable(self.pro_qty());
                        item.pro_price_grid = (typeof self.pro_price() === "undefined" || self.pro_price() == null) ? ko.observable("") : ko.observable(self.pro_price());
                        item.cf_id = ko.observable("");
                    }else{

                    }
                };

                self.RemoveColPriceGridRow = function (indexNo,rowData) {

                    self.product_price_list.remove(rowData);
                }

                self.clearPriceFields = function () {

                    self.lot_no("");
                    self.pro_qty("");
                    self.pro_price("");
                }

                self.clearAllFields = function () {
                    self.clearPriceFields();
                    self.clearPhotoFields();

                    appSelectEmpty('category_drop_id');
                    self.category_ids("");
                    self.pro_name("");
                    self.product_price_list.removeAll();
                    self.product_photo_list.removeAll();

                }
            }
        }
    </script>
@endsection

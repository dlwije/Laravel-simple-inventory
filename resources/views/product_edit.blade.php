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
                <h5 class="card-title">Product edit</h5>
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
                                    @foreach($cate_edit_list AS $cat_e_l)

                                        @if($cat_l->children)
                                            @foreach($cat_l->children AS $child)
{{--                                                @foreach($cate_edit_list AS $cat_e_l)--}}
                                                    <option value="{{$child->id}}" @if($child->id == $cat_e_l->category_id) selected @endif>{{$child->c_name}}</option>
{{--                                                @endforeach--}}
                                            @endforeach
                                        @endif
                                    @endforeach
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
                    <div class="col-md-12">
                        <button class="btn btn-primary btn-sm" onclick="productAdd.ivm.submitProduct();">Submit</button>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /.container-fluid -->

    <script src="{{asset('/plugins/select2/js/select2.full.min.js')}}"></script>
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
                productAdd.ivm.getProductPriceList();
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

                self.pro_name('{{$pro_data[0]->p_name}}');
                self.product_id('{{$pro_data[0]->id}}');

                self.getProductPriceList = function () {

                    $.ajax({
                        type: "post",
                        url: '{{route('getProductPriceList')}}',
                        data:{
                            '_token':'{{csrf_token()}}',
                            'pro_id':self.product_id(),
                        },
                        dataType: 'json',
                        success: function (data) {

                            self.clearPriceFields();
                            if(data.dataCount > 0){

                                $.each(data.data, function (i,item) {
                                    self.product_price_list.push(new gridColPriceListRows(item));
                                });
                            }
                        },
                        error: function(data){
                            hideLoading();

                            var errors = data.responseJSON.message;
                            if(typeof data.responseJSON.message == "undefined") errors = (data.responseJSON[0].message);

                            var errorList = "<ul>";

                            if(typeof data.responseJSON.message == "undefined"){
                                errorList += '<li class="text-center text-danger">' + data.responseJSON[0].message + '</li>';
                            }else {
                                $.each(errors, function (i, error) {
                                    errorList += '<li class="text-center text-danger">' + error + '</li>';
                                })
                            }
                            errorList +="</ul>"

                            sweetAlertMsg(
                                'Need attention!',
                                errorList,
                                'warning'
                            );
                        }
                    });

                }

                self.submitProduct = function () {
                    self.category_ids($('#category_drop_id').val());

                    $.ajax({
                        type: "post",
                        url: '{{route('submitEditProductData')}}',
                        data:{
                            '_token':'{{csrf_token()}}',
                            'pro_id':self.product_id(),
                            'pro_name':self.pro_name(),
                            'category_ids':self.category_ids(),
                            'pro_price_list':ko.toJSON(self.product_price_list),
                        },
                        dataType: 'json',
                        success: function (data) {
                            // hideLoading();

                            if(data.status){

                                alert('Success / '+data.message);
                                self.clearAllFields();
                                location.reload();
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

                        item.lot_no_grid = (typeof obj.lot_no === "undefined" || obj.lot_no == null) ? ko.observable("") : ko.observable(obj.lot_no);
                        item.pro_qty_grid = (typeof obj.product_qty === "undefined" || obj.product_qty == null) ? ko.observable("") : ko.observable(obj.product_qty);
                        item.pro_price_grid = (typeof obj.product_price === "undefined" || obj.product_price == null) ? ko.observable("") : ko.observable(obj.product_price);
                        item.cf_id = (typeof obj.id === "undefined" || obj.id == null) ? ko.observable("") : ko.observable(obj.id);
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

                    appSelectEmpty('category_drop_id');
                    self.category_ids("");
                    self.pro_name("");
                    self.product_price_list.removeAll();

                }
            }
        }
    </script>
@endsection

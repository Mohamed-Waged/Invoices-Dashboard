@extends('layouts.master')

@section('title','المنتجات')

@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الاعدادت</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ المنتجات</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show text-center w-50 mx-auto" role="alert">
            <strong>{{ session()->get('message') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger text-center w-50 mx-auto">
            <ul style="list-style-type:none">
                @foreach ($errors->all() as $error)
                <li>* {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- row -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <div class="col-sm-6 col-md-4 col-xl-3">
                            <a class="modal-effect btn btn-primary btn-block" data-effect="effect-scale" data-toggle="modal" href="#exampleModal">إضافة منتج</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example1" class="table key-buttons text-md-nowrap text-center" data-page-length='50'>
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">اسم المنتج</th>
                                    <th class="border-bottom-0">اسم القسم</th>
                                    <th class="border-bottom-0">ملاحظات</th>
                                    <th class="border-bottom-0">العمليات</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product )
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->section->name }}</td>
                                    <td>{{ $product->description ?? '---' }}</td>
                                    <td>
                                        <button class="btn btn-outline-success btn-sm"
                                            data-name="{{ $product->name }}" data-id="{{ $product->id }}"
                                            data-section_name="{{ $product->section->name }}"
                                            data-description="{{ $product->description }}" data-toggle="modal"
                                            data-target="#edit_Product">تعديل
                                        </button>

                                        <button class="btn btn-outline-danger btn-sm " data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}" data-toggle="modal" data-target="#modaldemo9">حذف
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Product Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">اضافة منتج</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{route('products.store')}}" method="post">
                        @csrf

                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">اسم المنتج</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <label class="my-1 mr-2" for="section_id">القسم</label>
                            <select name="section_id" id="section_id" class="form-control" required>
                                <option value="" selected disabled> --حدد القسم--</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>

                            <div class="form-group">
                                <label for="description">ملاحظات</label>
                                <textarea class="form-control" id="description" name="description" rows="3" style='resize:none;'></textarea>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">تاكيد</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End Add Product Modal -->

        <!-- Edit Product Modal -->
        <div class="modal fade" id="edit_Product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">تعديل منتج</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action='{{ route('products.update','id') }}' method="post">
                        @csrf
                        @method('PUT')

                        <div class="modal-body">
                            <div class="form-group">
                                <label for="title">اسم المنتج :</label>
                                <input type="hidden" class="form-control" name="id" id="id" value="">
                                <input type="text" class="form-control" name="name" id="name" >
                            </div>

                            <label class="my-1 mr-2" for="section_name">القسم</label>
                            <select name="section_name" id="section_name" class="custom-select my-1 mr-sm-2" required>
                                @foreach ($sections as $section)
                                    {{-- <option {{$product->section_id == $section->id ? 'selected' : ''}} value='{{$section->id}}'>{{ $section->name }}</option> --}}
                                    <option>{{ $section->name }}</option>
                                    @endforeach
                            </select>

                            <div class="form-group">
                                <label for="des">ملاحظات :</label>
                                <textarea name="description" cols="20" rows="5" id='description' class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">تعديل </button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End Edit Product Modal -->

        <!-- Delete Product Modal -->  
        <div class="modal fade" id="modaldemo9" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">حذف المنتج</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('products.destroy','id') }}" method="post">
                        @csrf
                        @method('delete')

                        <div class="modal-body">
                            <p>هل انت متاكد من عملية الحذف ؟</p><br>
                            <input type="hidden" name="id" id="id" value="">
                            <input class="form-control" name="name" id="name" type="text" readonly>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                            <button type="submit" class="btn btn-danger">تاكيد</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End Dellete Product Modal -->    

    </div>
    <!-- row closed -->

    </div>
    <!-- Container closed -->

    </div>
    <!-- main-content closed -->
@endsection

@section('js')
	<!-- Internal Data tables -->
	<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
	<script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
	<!--Internal  Datatable js -->
	<script src="{{URL::asset('assets/js/table-data.js')}}"></script>
	<!-- Internal Prism js-->
	<script src="{{URL::asset('assets/plugins/prism/prism.js')}}"></script>

	<script>
		// Edit
		$('#edit_Product').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var name = button.data('name')
            var section_name = button.data('section_name')
            var id = button.data('id')
            var description = button.data('description')
            var modal = $(this)
            modal.find('.modal-body #name').val(name);
            modal.find('.modal-body #section_name').val(section_name);
            modal.find('.modal-body #description').val(description);
            modal.find('.modal-body #id').val(id);
        })

		// Delete
		$('#modaldemo9').on('show.bs.modal', function(event) {
			var button = $(event.relatedTarget)
			var id = button.data('id')
			var name = button.data('name')
			var modal = $(this)

			modal.find('.modal-body #id').val(id);
			modal.find('.modal-body #name').val(name);
		})
	</script>

@endsection
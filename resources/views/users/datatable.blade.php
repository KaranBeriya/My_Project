@extends('layouts.public')

@section('title', 'User List - DataTables')

@push('style')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    #users-table_wrapper .dataTables_paginate .paginate_button {
        background-color: #f0f0f0 !important;
        color: #007bff !important;
        border: 1px solid #007bff !important;
        border-radius: 4px !important;
        padding: 4px 10px !important;
        margin: 0 2px !important; 
        cursor: pointer !important;
        transition: all 0.3s ease-in-out !important;
    }

    #users-table_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: #007bff !important;
        color: white !important;
    }

    #users-table_wrapper .dataTables_paginate .paginate_button.current {
        background-color: #007bff !important;
        color: white !important;
        border: 1px solid #007bff !important;
    }
</style>
@endpush

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Yajra DataTables - Users List</h2>

    @if(session('success'))
        <div class="alert alert-success py-1 px-2">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger py-1 px-2">{{ session('error') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="users-table">
            <thead class="table-dark text-center">
                <tr>
                    <th style="width: 40px;">Id</th>
                    <th style="min-width: 120px;">Name</th>
                    <th style="min-width: 180px;">Email</th>
                    <th style="min-width: 150px;">Contact</th>
                    <th style="min-width: 120px;">Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('script')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("users.datatable") }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'contact', name: 'contact' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            dom: '<"d-flex justify-content-between align-items-center mb-2"lf>rt<"d-flex justify-content-between align-items-center mt-2"ip>'
        });
    });
</script>
@endpush

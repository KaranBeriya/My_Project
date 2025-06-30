@extends('layouts.public')

@section('title', 'User List - DataTables')

@push('style')
<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" />
<style>
    .table {
        font-size: 13px;
    }

    .btn-sm {
        padding: 2px 8px;
        font-size: 12px;
    }

    #users-table_wrapper {
        padding: 10px;
    }

    .dataTables_filter input,
    .dataTables_length select {
        height: 30px;
        font-size: 13px;
    }

    .dataTables_wrapper .row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .dataTables_paginate .paginate_button {
        padding: 3px 8px !important;
        font-size: 12px;
    }

    .table-responsive {
        overflow-x: auto;
    }
</style>
@endpush

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Yajra DataTables - Users List</h2>

    <!-- Flash Messages -->
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
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("users.datatable") }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'contact', name: 'contact' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            dom: '<"d-flex justify-content-between align-items-center mb-2"lf>rt<"d-flex justify-content-between align-items-center mt-2"ip>'
            // l = length dropdown (left), f = filter (right), t = table, i = info, p = pagination
        });
    });
</script>
@endpush

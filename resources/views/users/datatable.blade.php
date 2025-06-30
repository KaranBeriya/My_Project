@extends('layouts.public')

@section('title', __('messages.users_list'))

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Laravel 11 Yajra DataTables - Users List</h2>

    <!-- Success/Error Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered" id="users-table">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>{{ __('messages.name') }}</th>
                <th>{{ __('messages.email') }}</th>
                <th>{{ __('messages.created_at') }}</th>
                <th width="180px">{{ __('messages.action') }}</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('script')
<!-- DataTables JS + Config -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
$(document).ready(function () {
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('users.datatable') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush

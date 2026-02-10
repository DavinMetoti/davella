@extends('pages.layout')

@section('main')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Users Management</h1>
    <a href="{{ route('users.create') }}" class="btn-primary hover:bg-opacity-90 px-4 py-2 rounded-lg transition duration-200">
        <i class="fas fa-plus mr-2"></i>Add New User
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <table class="display datatable custom-table" data-url="{{ route('users.api') }}">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
@endsection
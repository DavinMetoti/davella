@extends('pages.layout')

@section('main')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Units Management</h1>
    <a href="{{ route('units.create') }}" class="btn-primary hover:bg-opacity-90 px-4 py-2 rounded-lg transition duration-200">
        <i class="fas fa-plus mr-2"></i>Add New Unit
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <table class="display datatable custom-table" data-url="{{ route('units.api') }}">
        <thead>
            <tr>
                <th>Cluster</th>
                <th>Name</th>
                <th>Unit Code</th>
                <th>House Type</th>
                <th>Land Area</th>
                <th>Building Area</th>
                <th>Price</th>
                <th>Progress</th>
                <th>Status</th>
                <th>Coordinates</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
@endsection
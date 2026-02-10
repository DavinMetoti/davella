@extends('pages.layout')

@section('main')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Clusters Management</h1>
    <a href="{{ route('clusters.create') }}" class="btn-primary hover:bg-opacity-90 px-4 py-2 rounded-lg transition duration-200">
        <i class="fas fa-plus mr-2"></i>Add New Cluster
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <table class="display datatable custom-table min-h-full" data-url="{{ route('clusters.api') }}">
        <thead>
            <tr>
                <th>Name</th>
                <th>Site Plan</th>
                <th>Address</th>
                <th>Price Range</th>
                <th>Units</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
@endsection
@extends('pages.layout')

@section('main')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Reservations Management</h1>
    <a href="{{ route('reservations.create') }}" class="btn-primary hover:bg-opacity-90 px-4 py-2 rounded-lg transition duration-200">
        <i class="fas fa-plus mr-2"></i>Add New Reservation
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <table class="display datatable custom-table" data-url="{{ route('reservations.api') }}">
        <thead>
            <tr>
                <th>Code</th>
                <th>Customer</th>
                <th>Unit</th>
                <th>Sales</th>
                <th>Price</th>
                <th>Booking Fee</th>
                <th>DP Plan</th>
                <th>Status</th>
                <th>Reservation Date</th>
                <th>Expired At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
@endsection
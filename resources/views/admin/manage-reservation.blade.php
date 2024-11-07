@extends('layouts.admin')

@section('title', 'Manage Reservations')

@section('content')
<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800">Reservations</h2>
    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <!-- Reservations Table -->
        <table class="min-w-full bg-white divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wide">Name</th>
                    <th scope="col" class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wide">Guests</th>
                    <th scope="col" class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wide">Table Number</th>
                    <th scope="col" class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wide">Status</th>
                    <th scope="col" class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wide">Selected Date</th>
                    <th scope="col" class="py-3 px-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @php
                    // Filter reservations to exclude completed and done ones
                    $filteredReservations = $reservations->filter(function($reservation) {
                        return !in_array($reservation->status, ['Completed', 'Done']);
                    });
                @endphp

                @if($filteredReservations->isEmpty())
                    <!-- No Reservations Found Message -->
                    <tr>
                        <td colspan="6" class="py-4 px-4 text-center text-sm text-gray-600">
                            No reservations found.
                        </td>
                    </tr>
                @else
                    <!-- Display Non-Completed and Non-Done Reservations -->
                    @foreach($filteredReservations as $reservation)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-800">{{ $reservation->name }}</td>
                        <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-800">{{ $reservation->guests }}</td>
                        <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-800">{{ $reservation->table_number }}</td>
                        <td class="py-4 px-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full 
                                {{ $reservation->status === 'Completed' ? 'bg-green-100 text-green-700' : ($reservation->status === 'Pending' ? 'bg-yellow-100 text-yellow-700' : ($reservation->status === 'Cancelled' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700')) }}">
                                {{ $reservation->status }}
                            </span>
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-800">
                            {{ \Carbon\Carbon::parse($reservation->date)->format('d-m-Y') }}
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap">
                            <div class="flex justify-between items-center space-x-4">
                                <div class="flex space-x-1">
                                    @if($reservation->status === 'Pending')
                                        <button type="button" onclick="showEditModal({{ $reservation->id }}, '{{ $reservation->status }}')" class="text-blue-600 hover:text-blue-800 transition duration-150 focus:outline-none" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @else
                                        <button type="button" class="text-gray-400 cursor-not-allowed" title="Cannot Edit Completed Reservations" disabled>
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endif

                                    <!-- Delete Button with custom modal -->
                                    <button type="button" onclick="confirmDelete({{ $reservation->id }})" class="text-red-600 hover:text-red-800 transition duration-150 focus:outline-none" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                                <!-- See Details Button -->
                                <button type="button" onclick="showModal({{ $reservation->id }})" class="text-gray-600 hover:text-gray-800 transition duration-150 focus:outline-none ml-4" title="See Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Structure for Viewing Details -->
<div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center transition-opacity duration-300 opacity-0 invisible z-50">
    <div class="bg-white w-11/12 max-w-md mx-auto rounded-lg shadow-lg transform transition-transform duration-300 scale-95 overflow-hidden">
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Reservation Details</h3>
            <button onclick="closeModal()" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <!-- Modal Content -->
        <div id="modalContent" class="p-4 max-h-[60vh] overflow-y-auto">
            <!-- Dynamic content will be injected here -->
        </div>
        <!-- Modal Footer -->
        <div class="flex justify-end p-4 border-t">
            <button onclick="closeModal()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500">Close</button>
        </div>
    </div>
</div>

<!-- Modal Structure for Editing Status -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center transition-opacity duration-300 opacity-0 invisible z-50">
    <div class="bg-white w-11/12 md:max-w-md mx-auto rounded-lg shadow-lg transform transition-transform duration-300 scale-95">
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Edit Reservation Status</h3>
            <button onclick="closeEditModal()" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <!-- Modal Content -->
        <div class="p-6">
            <form id="editStatusForm" method="POST" action="{{ route('reservation.update.status') }}">
                @csrf
                <input type="hidden" id="reservationId" name="reservationId" value="">
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500">
                        <option value="Pending">Pending</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option> <!-- New Cancel option -->
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center transition-opacity duration-300 opacity-0 invisible z-50">
    <div class="bg-white w-11/12 md:max-w-md mx-auto rounded-lg shadow-lg transform transition-transform duration-300 scale-95">
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Confirm Delete</h3>
            <button onclick="closeDeleteModal()" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <!-- Modal Content -->
        <div class="p-6">
            <p class="text-sm text-gray-700">Are you sure you want to delete this reservation?</p>
        </div>
        <!-- Modal Footer -->
        <div class="flex justify-end p-4 border-t">
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteModal()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 mr-2">Cancel</button>
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500">Delete</button>
            </form>
        </div>
    </div>
</div>

<!-- Larger Modal Structure for Viewing Screenshot -->
<div id="screenshotModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center transition-opacity duration-300 opacity-0 invisible z-50">
    <div class="bg-white w-11/12 max-w-2xl mx-auto rounded-lg shadow-lg transform transition-transform duration-300 scale-95 overflow-hidden">
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Payment Screenshot</h3>
            <button onclick="closeScreenshotModal()" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <!-- Modal Content -->
        <div id="screenshotContent" class="p-4 max-h-[70vh] overflow-y-auto flex justify-center items-center">
            <!-- Dynamic content for screenshot will be injected here -->
        </div>
    </div>
</div>

<script>
    function showModal(reservationId) {
        const reservation = @json($reservations).find(r => r.id === reservationId);
        
        if (reservation) {
            const modalContent = `
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <p class="flex items-center mb-2">
                            <i class="fas fa-id-badge text-gray-500 mr-2"></i>
                            <strong>ID:</strong> ${reservation.id}
                        </p>
                        <p class="flex items-center mb-2">
                            <i class="fas fa-user text-gray-500 mr-2"></i>
                            <strong>Name:</strong> ${reservation.name}
                        </p>
                        <p class="flex items-center mb-2">
                            <i class="fas fa-envelope text-gray-500 mr-2"></i>
                            <strong>Email:</strong> ${reservation.email}
                        </p>
                        <p class="flex items-center mb-2">
                            <i class="fas fa-phone text-gray-500 mr-2"></i>
                            <strong>Contact:</strong> ${reservation.contact}
                        </p>
                        <p class="flex items-center mb-2">
                            <i class="fas fa-users text-gray-500 mr-2"></i>
                            <strong>Guests:</strong> ${reservation.guests}
                        </p>
                        <p class="flex items-center mb-2">
                            <i class="fas fa-table text-gray-500 mr-2"></i>
                            <strong>Table Number:</strong> ${reservation.table_number}
                        </p>
                        <p class="flex items-center mb-2">
                            <i class="fas fa-info-circle text-gray-500 mr-2"></i>
                            <strong>Status:</strong> ${reservation.status}
                        </p>
                        <p class="flex items-center mb-2">
                            <i class="fas fa-calendar-alt text-gray-500 mr-2"></i>
                            <strong>Selected Date:</strong> ${new Date(reservation.date).toLocaleDateString()}
                        </p>
                        <p class="flex items-center mb-2">
                            <i class="fas fa-calendar-day text-gray-500 mr-2"></i>
                            <strong>Booked Date:</strong> ${new Date(reservation.created_at).toLocaleDateString()}
                        </p>
                        ${reservation.request_reason ? `<p class="flex items-center mb-2">
                            <i class="fas fa-comment-alt text-gray-500 mr-2"></i>
                            <strong>Request:</strong> ${reservation.request_reason}
                        </p>` : ''}
                        ${reservation.screenshot ? `<p class="flex items-center mb-2">
                            <i class="fas fa-file-image text-gray-500 mr-2"></i>
                            <strong>Payment Screenshot:</strong>
                        </p>
                        <div class="flex justify-center">
                            <img src="/storage/${reservation.screenshot}" alt="Payment Screenshot" class="max-w-full max-h-[300px] rounded-lg shadow-md border border-gray-200 cursor-pointer" onclick="showScreenshotModal('/storage/${reservation.screenshot}')">
                        </div>` : `<p class="flex items-center mb-2">
                            <i class="fas fa-file-image text-gray-500 mr-2"></i>
                            <strong>Payment Screenshot:</strong> No Screenshot Available
                        </p>`}
                    </div>
                </div>
            `;

            document.getElementById('modalContent').innerHTML = modalContent;
            const modal = document.getElementById('detailsModal');
            modal.classList.remove('opacity-0', 'invisible'); // Show the modal
            modal.classList.add('opacity-100', 'visible'); // Make it fully visible
            modal.querySelector('.transform').classList.add('scale-100'); // Smoothly open the modal
        }
    }

    function closeModal() {
        const modal = document.getElementById('detailsModal');
        modal.classList.add('opacity-0', 'invisible'); // Hide the modal
        modal.classList.remove('opacity-100', 'visible'); // Ensure it's properly hidden
        modal.querySelector('.transform').classList.remove('scale-100'); // Smoothly close the modal
    }

    function showEditModal(id, currentStatus) {
        document.getElementById('reservationId').value = id; // Set reservation ID
        document.getElementById('status').value = currentStatus; // Set current status
        const modal = document.getElementById('editModal');
        modal.classList.remove('opacity-0', 'invisible'); // Show the modal
        modal.classList.add('opacity-100', 'visible'); // Make it fully visible
        modal.querySelector('.transform').classList.add('scale-100'); // Smoothly open the modal
    }

    function closeEditModal() {
        const modal = document.getElementById('editModal');
        modal.classList.add('opacity-0', 'invisible'); // Hide the modal
        modal.classList.remove('opacity-100', 'visible'); // Ensure it's properly hidden
        modal.querySelector('.transform').classList.remove('scale-100'); // Smoothly close the modal
    }

    function confirmDelete(reservationId) {
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = `/admin/reservations/${reservationId}`; // Set the form action
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('opacity-0', 'invisible'); // Show the modal
        modal.classList.add('opacity-100', 'visible'); // Make it fully visible
        modal.querySelector('.transform').classList.add('scale-100'); // Smoothly open the modal
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('opacity-0', 'invisible'); // Hide the modal
        modal.classList.remove('opacity-100', 'visible'); // Ensure it's properly hidden
        modal.querySelector('.transform').classList.remove('scale-100'); // Smoothly close the modal
    }

    function showScreenshotModal(imageSrc) {
        document.getElementById('screenshotContent').innerHTML = `<img src="${imageSrc}" alt="Payment Screenshot" class="max-w-full max-h-[65vh] rounded shadow-lg">`;
        const modal = document.getElementById('screenshotModal');
        modal.classList.remove('opacity-0', 'invisible'); // Show the modal
        modal.classList.add('opacity-100', 'visible'); // Make it fully visible
        modal.querySelector('.transform').classList.add('scale-100'); // Smoothly open the modal
    }

    function closeScreenshotModal() {
        const modal = document.getElementById('screenshotModal');
        modal.classList.add('opacity-0', 'invisible'); // Hide the modal
        modal.classList.remove('opacity-100', 'visible'); // Ensure it's properly hidden
        modal.querySelector('.transform').classList.remove('scale-100'); // Smoothly close the modal
    }
</script>
@endsection

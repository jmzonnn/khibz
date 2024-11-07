@extends('layouts.admin')

@section('title', 'Reservation History')

@section('content')
<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800">Reservation History</h2>

    <!-- Search and Filter Section -->
    <div class="flex items-center justify-between mb-4">
        <!-- Filter for Number of Rows -->
        <div class="flex items-center">
            <label for="rowsPerPage" class="mr-2 text-sm text-gray-600">Show</label>
            <select id="rowsPerPage" class="border border-gray-300 rounded p-1" onchange="fetchReservations()">
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="all">All</option>
            </select>
        </div>

        <!-- Search Input -->
        <div class="flex items-center">
            <input type="text" id="search" class="border border-gray-300 rounded p-1 mr-2" placeholder="Search..." oninput="fetchReservations()">
        </div>
    </div>

    <!-- Scrollable Table for Reservation History -->
    <div class="overflow-y-auto rounded-lg border border-gray-200" style="max-height: 500px;" id="reservationTable">
        <!-- Table content will be injected here by JavaScript -->
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

<!-- Modal Structure for Viewing Payment Screenshot -->
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
    function fetchReservations() {
        const searchQuery = document.getElementById('search').value;
        const rowsPerPage = document.getElementById('rowsPerPage').value;

        fetch(`{{ route('admin.reservations.history') }}?search=${searchQuery}&rowsPerPage=${rowsPerPage}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById('reservationTable').innerHTML = data;
        })
        .catch(error => console.error('Error:', error));
    }

    function showModal(reservationId) {
        const reservation = @json($historyReservations->items()).find(r => r.id === reservationId);
        
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

    // Initial fetch to populate the table
    document.addEventListener('DOMContentLoaded', fetchReservations);
</script>
@endsection

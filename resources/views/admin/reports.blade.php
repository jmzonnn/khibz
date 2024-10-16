@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
<div class="bg-white p-8 rounded-lg shadow mb-8">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800">Completed Reservations Report</h2>

    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center">
            <label for="rowsPerPage" class="mr-2 text-sm text-gray-600">Show</label>
            <select id="rowsPerPage" class="border border-gray-300 rounded p-1" onchange="handleSearch()">
                <option value="10" {{ request('rowsPerPage') == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('rowsPerPage') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('rowsPerPage') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('rowsPerPage') == 100 ? 'selected' : '' }}>100</option>
            </select>
        </div>

        <div class="flex items-center">
            <input type="text" id="search" class="border border-gray-300 rounded p-1 mr-2" placeholder="Search..." value="{{ request('search') }}" oninput="handleSearch()">
            
            <label for="startDate" class="mr-2 text-sm text-gray-600">From</label>
            <input type="date" id="startDate" class="border border-gray-300 rounded p-1 mr-2" value="{{ request('startDate') }}" onchange="handleSearch()">
            <label for="endDate" class="mr-2 text-sm text-gray-600">To</label>
            <input type="date" id="endDate" class="border border-gray-300 rounded p-1 mr-2" value="{{ request('endDate') }}" onchange="handleSearch()">

            <button onclick="generateReport()" class="ml-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Generate Report</button>
        </div>

        <button onclick="markReservationsAsDone()" class="ml-4 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">Mark as Done</button>
    </div>

    <div id="tableContainer" class="overflow-x-auto rounded-lg border border-gray-200">
        @include('admin.partials.reservations_table', ['completedReservations' => $completedReservations])
    </div>
</div>

<div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center transition-opacity duration-300 opacity-0 invisible z-50">
    <div class="bg-white w-11/12 max-w-md mx-auto rounded-lg shadow-lg transform transition-transform duration-300 scale-95 overflow-hidden">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Reservation Details</h3>
            <button onclick="closeModal()" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="modalContent" class="p-4 max-h-[60vh] overflow-y-auto"></div>
        <div class="flex justify-end p-4 border-t">
            <button onclick="closeModal()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500">Close</button>
        </div>
    </div>
</div>

<div id="screenshotModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center transition-opacity duration-300 opacity-0 invisible z-50">
    <div class="bg-white w-11/12 max-w-2xl mx-auto rounded-lg shadow-lg transform transition-transform duration-300 scale-95 overflow-hidden">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Payment Screenshot</h3>
            <button onclick="closeScreenshotModal()" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="screenshotContent" class="p-4 max-h-[70vh] overflow-y-auto flex justify-center items-center"></div>
    </div>
</div>

<div id="confirmDoneModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center transition-opacity duration-300 opacity-0 invisible z-50">
    <div class="bg-white w-11/12 max-w-md mx-auto rounded-lg shadow-lg transform transition-transform duration-300 scale-95 overflow-hidden">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Confirm Mark as Done</h3>
            <button onclick="closeConfirmModal()" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4">
            <p>Are you sure you want to mark all completed reservations as done?</p>
        </div>
        <div class="flex justify-end p-4 border-t">
            <button onclick="closeConfirmModal()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">Cancel</button>
            <button onclick="confirmMarkAsDone()" class="bg-green-500 text-white px-4 py-2 ml-2 rounded hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">Confirm</button>
        </div>
    </div>
</div>

<script>
    const completedReservations = @json($completedReservations->items());

    function handleSearch() {
        const searchQuery = document.getElementById('search').value;
        const rowsPerPage = document.getElementById('rowsPerPage').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        const url = new URL(window.location.href);
        url.searchParams.set('search', searchQuery);
        url.searchParams.set('rowsPerPage', rowsPerPage);
        if (startDate) url.searchParams.set('startDate', startDate);
        if (endDate) url.searchParams.set('endDate', endDate);

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(data => {
            document.querySelector('#tableContainer').innerHTML = data;
        })
        .catch(error => console.error('Error:', error));
    }

    function generateReport() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    let filteredReservations = completedReservations;

    if (startDate || endDate) {
        filteredReservations = completedReservations.filter(reservation => {
            const reservationDate = new Date(reservation.date);
            const start = startDate ? new Date(startDate) : null;
            const end = endDate ? new Date(endDate) : null;

            if (start && end) {
                return reservationDate >= start && reservationDate <= end;
            } else if (start) {
                return reservationDate >= start;
            } else if (end) {
                return reservationDate <= end;
            }
            return true;
        });
    }

    let printContents = `
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="/images/logokhibz.png" alt="Khibz Lounge Logo" style="width: 150px; height: auto;">
            <p style="font-size: 18px; font-weight: bold; margin-top: 10px;">SUPERKHIBZ CORP.</p>
            <p style="font-size: 14px;">Maharlika Highway, Brgy. 1 - B San Pablo City Laguna, 4000</p>
            <h2 style="margin-top: 20px; color: red; font-size: 24px;">RESERVATION REPORT</h2>
        </div>
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Guests</th>
                    <th>Guest Reason</th>
                    <th>Table Number</th>
                    <th>Selected Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
    `;

    filteredReservations.forEach(reservation => {
        printContents += `
            <tr>
                <td>${reservation.name}</td>
                <td>${reservation.email}</td>
                <td>${reservation.contact}</td>
                <td>${reservation.guests}</td>
                <td>${reservation.request_reason}</td>
                <td>${reservation.table_number}</td>
                <td>${new Date(reservation.date).toLocaleDateString()}</td>
                <td>Completed</td>
            </tr>
        `;
    });

    printContents += `
            </tbody>
        </table>

        <div style="margin-top: 60px; display: flex; justify-content: space-between; align-items: flex-start;">
            <div style="text-align: center;">
                <p><strong>PREPARED BY:</strong></p>
                <p>__________________________</p>
                <p>Signature Over Printed Name</p>
                <p>Position:__________________</p>
                <p>Date:_____________________</p>
            </div>
            <div style="text-align: center;">
                <p><strong>APPROVED BY:</strong></p>
                <p>__________________________</p>
                <p>Signature Over Printed Name</p>
                <p>Position:__________________</p>
                <p>Date:_____________________</p>
            </div>
        </div>
    `;

    const printWindow = window.open('', '_blank');
    printWindow.document.open();
    printWindow.document.write(`
        <html>
            <head>
                <title>Completed Reservations Report</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                        padding: 0;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    th, td {
                        border: 1px solid #ddd;
                        padding: 8px;
                        text-align: left;
                    }
                    th {
                        background-color: #f4f4f4;
                    }
                    h1, h2, p {
                        margin: 0;
                        padding: 0;
                    }
                    @media print {
                        body {
                            margin: 0;
                            padding: 0;
                        }
                        .page-break { 
                            page-break-after: always; 
                        }
                    }
                </style>
            </head>
            <body>
                ${printContents}
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}


    function markReservationsAsDone() {
        const modal = document.getElementById('confirmDoneModal');
        modal.classList.remove('opacity-0', 'invisible');
        modal.classList.add('opacity-100', 'visible');
        modal.querySelector('.transform').classList.add('scale-100');
    }

    function confirmMarkAsDone() {
        fetch('{{ route("reservation.markDone") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to update reservations.');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function showModal(reservationId) {
        const reservation = completedReservations.find(r => r.id == reservationId);

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
                            <strong>Request Reason:</strong> ${reservation.request_reason}
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
            modal.classList.remove('opacity-0', 'invisible');
            modal.classList.add('opacity-100', 'visible');
            modal.querySelector('.transform').classList.add('scale-100');
        } else {
            console.log('Reservation not found');
        }
    }

    function closeModal() {
        const modal = document.getElementById('detailsModal');
        modal.classList.add('opacity-0', 'invisible');
        modal.classList.remove('opacity-100', 'visible');
        modal.querySelector('.transform').classList.remove('scale-100');
    }

    function showScreenshotModal(imageSrc) {
        document.getElementById('screenshotContent').innerHTML = `<img src="${imageSrc}" alt="Payment Screenshot" class="max-w-full max-h-[65vh] rounded shadow-lg">`;
        const modal = document.getElementById('screenshotModal');
        modal.classList.remove('opacity-0', 'invisible');
        modal.classList.add('opacity-100', 'visible');
        modal.querySelector('.transform').classList.add('scale-100');
    }

    function closeScreenshotModal() {
        const modal = document.getElementById('screenshotModal');
        modal.classList.add('opacity-0', 'invisible');
        modal.classList.remove('opacity-100', 'visible');
        modal.querySelector('.transform').classList.remove('scale-100');
    }

    function closeConfirmModal() {
        const modal = document.getElementById('confirmDoneModal');
        modal.classList.add('opacity-0', 'invisible');
        modal.classList.remove('opacity-100', 'visible');
        modal.querySelector('.transform').classList.remove('scale-100');
    }
</script>
@endsection

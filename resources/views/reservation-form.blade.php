<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khibz Lounge - Reservation Form</title>
    @vite('resources/css/app.css')
    <style>
        .modal, .request-modal {
            display: none;
            position: fixed;
            z-index: 30;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease-out forwards;
        }
        .modal-content, .request-modal-content {
            position: relative;
            padding: 2rem;
            border-radius: 15px;
            background-color: #fff;
            max-width: 500px;
            width: 90%;
            animation: scaleUp 0.4s ease-out forwards;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        .close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            color: #aaa;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        .close:hover {
            color: #333;
        }
        .modal-header {
            font-weight: bold;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #333;
        }
        .modal-body p {
            margin-bottom: 1rem;
            font-size: 1rem;
            color: #666;
        }
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        .button {
            padding: 0.5rem 1.2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .button-edit {
            background-color: #f3f4f6;
            color: #333;
        }
        .button-edit:hover {
            background-color: #e5e7eb;
        }
        .button-confirm {
            background-color: #007bff;
            color: #fff;
        }
        .button-confirm:hover {
            background-color: #0056b3;
        }
        .button-request {
            background-color: #ffc107;
            color: #333;
        }
        .button-request:hover {
            background-color: #e5b806;
        }
        .card {
            display: none;
            overflow: hidden;
            position: fixed;
            z-index: 40;
            text-align: center;
            border-radius: 15px;
            max-width: 400px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            animation: bounceIn 0.6s ease-out forwards;
        }
        .div_image_v {
            background: #47c9a2;
            border-bottom: none;
            position: relative;
            text-align: center;
            margin: -20px -20px 0;
            border-radius: 15px 15px 0 0;
            padding: 35px;
        }
        .dismiss {
            position: absolute;
            right: 10px;
            top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem;
            background-color: #fff;
            color: black;
            border: 2px solid #D1D5DB;
            font-size: 1rem;
            font-weight: 300;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            transition: .3s ease;
            cursor: pointer;
        }
        .dismiss:hover {
            background-color: #ee0d0d;
            border: 2px solid #ee0d0d;
            color: #fff;
        }
        .header {
            padding: 1.25rem 1rem 1rem;
        }
        .image {
            display: flex;
            margin-left: auto;
            margin-right: auto;
            background-color: #e2feee;
            flex-shrink: 0;
            justify-content: center;
            align-items: center;
            width: 3rem;
            height: 3rem;
            border-radius: 9999px;
            animation: pulsate .8s linear infinite;
        }
        .image svg {
            color: #0afa2a;
            width: 2rem;
            height: 2rem;
        }
        .content {
            margin-top: 0.75rem;
            text-align: center;
        }
        .title {
            color: #066e29;
            font-size: 1.2rem;
            font-weight: 600;
            line-height: 1.5rem;
        }
        .message {
            margin-top: 0.5rem;
            color: #595b5f;
            font-size: 0.9rem;
            line-height: 1.4rem;
        }
        @keyframes pulsate {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }
        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: translate(-50%, -50%) scale(0.8);
            }
            60% {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1.05);
            }
            100% {
                transform: translate(-50%, -50%) scale(1);
            }
        }
        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }
        @keyframes scaleUp {
            0% {
                transform: scale(0.8);
            }
            100% {
                transform: scale(1);
            }
        }
    </style>
</head>
<body class="bg-white text-white">

@include('layouts._navbar')

<div class="bg-black text-white py-12">
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold text-center mb-4">MAKE A RESERVATION</h1>
        <p class="text-center mb-8">Join us for an evening of great food, crafted cocktails, and lively atmosphere at our restobar. Whether you're planning a casual night out or a special celebration, reserving your table ensures you won't miss out on our unique blend of flavor and fun. Reserve now and let us take care of the rest!</p>

        <div class="bg-white text-black p-8 rounded shadow-lg mx-auto max-w-md">
            <h2 class="text-xl font-bold mb-4 text-center">Fill Out Reservation Details</h2>
            <form method="POST" action="{{ route('reservation.submit') }}" enctype="multipart/form-data" id="reservationForm">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-semibold mb-2">Name:</label>
                    <input type="text" id="name" name="name" required class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-semibold mb-2">Email:</label>
                    <input type="email" id="email" name="email" required class="w-full p-2 border border-gray-300 rounded" oninput="validateEmail()">
                    <span id="emailError" class="text-red-500 text-sm"></span>
                </div>
                <div class="mb-4">
                    <label for="contact" class="block text-sm font-semibold mb-2">Phone Number:</label>
                    <input type="text" id="contact" name="contact" required class="w-full p-2 border border-gray-300 rounded">
                    <span id="phoneError" class="text-red-500 text-sm"></span>
                </div>
                <div class="mb-4">
                    <label for="guests" class="block text-sm font-semibold mb-2">Number of Guests:</label>
                    <input type="number" id="guests" name="guests" min="1" max="12" required class="w-full p-2 border border-gray-300 rounded" oninput="checkGuestLimit()">
                    <span id="guestError" class="text-red-500 text-sm"></span>
                </div>
                
                <!-- Updated Additional Requests Section -->
                <div class="mb-4">
                    <label for="additionalRequest" class="block text-sm font-semibold mb-2">Requests:</label>
                    <textarea id="additionalRequest" name="additionalRequest" rows="3" placeholder="(Optional)" class="w-full p-2 border border-gray-300 rounded text-gray-500"></textarea>
                </div>
                
                <div class="mb-4">
                    <label for="screenshot" class="block text-sm font-semibold mb-2">Screenshot of Payment:</label>
                    <div class="mb-2 p-3 bg-gray-100 text-gray-700 rounded">
                        You may send/transfer your downpayment to our GCash.<br>
                        <strong>Eliseo C.</strong><br>
                        09150647460
                    </div>
                    <input type="file" id="screenshot" name="screenshot" required accept="image/*" onchange="checkFileSize(event)" class="w-full p-2 border border-gray-300 rounded">
                    <span id="fileError" class="text-red-500 text-sm"></span>
                </div>
                
                <input type="hidden" id="selectedTable" name="selectedTable" value="{{ $selectedTable }}">
                <input type="hidden" id="date" name="date" value="{{ $selectedDate }}">

                <div class="flex justify-between mt-4">
                    <button type="button" onclick="history.back()" class="bg-gray-500 text-white py-2 px-4 rounded">Back</button>
                    <button type="button" onclick="openModal()" class="bg-black text-white py-2 px-4 rounded">Reserve</button>
                </div>
            </form>

            <div id="confirmationModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <div class="modal-header">Confirm Your Reservation</div>
                    <div class="modal-body">
                        <p>Please confirm your reservation details before proceeding:</p>
                        <ul>
                            <li><strong>Name:</strong> <span id="confirmName"></span></li>
                            <li><strong>Email:</strong> <span id="confirmEmail"></span></li>
                            <li><strong>Phone:</strong> <span id="confirmPhone"></span></li>
                            <li><strong>Number of Guests:</strong> <span id="confirmGuests"></span></li>
                            <li><strong>Selected Table:</strong> <span id="confirmTable"></span></li>
                            <li><strong>Selected Date:</strong> <span id="confirmDate"></span></li>
                            <li><strong>Requests:</strong> <span id="confirmRequest"></span></li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button onclick="closeModal()" class="button button-edit">Edit</button>
                        <button onclick="submitForm()" class="button button-confirm">Confirm and Reserve</button>
                    </div>
                </div>
            </div>

            <div id="successMessage" class="card">
                <button class="dismiss" type="button" onclick="closeSuccessMessage()">×</button>
                <div class="header">
                    <div class="div_image_v">
                        <div class="image">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 7L9.00004 18L3.99994 13" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="content">
                        <span class="title">Reservation Confirmed</span>
                        <p class="message">Thank you for your reservation! We look forward to welcoming you.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts._footer')

<script>
    function checkFileSize(event) {
        const file = event.target.files[0];
        const maxSize = 2 * 1024 * 1024;
        const fileError = document.getElementById('fileError');

        if (!file) {
            fileError.textContent = 'Screenshot of payment is required.';
            return false;
        } else if (file.size > maxSize) {
            fileError.textContent = 'The file size exceeds the maximum allowed size of 2MB.';
            return false;
        } else {
            fileError.textContent = '';
            return true;
        }
    }

    function validatePhoneNumber() {
        const phoneInput = document.getElementById('contact');
        const phoneError = document.getElementById('phoneError');
        const phonePattern = /^(09|\+639)\d{9}$/;

        if (!phonePattern.test(phoneInput.value)) {
            phoneError.textContent = 'Please enter a valid Philippine phone number (e.g., 09123456789 or +639123456789).';
            return false;
        } else {
            phoneError.textContent = '';
            return true;
        }
    }

    function validateEmail() {
        const emailInput = document.getElementById('email');
        const emailError = document.getElementById('emailError');
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        if (!emailPattern.test(emailInput.value)) {
            emailError.textContent = 'Please enter a valid email address.';
            return false;
        } else {
            emailError.textContent = '';
            return true;
        }
    }

    function checkGuestLimit() {
        const guestInput = document.getElementById('guests');
        const guestError = document.getElementById('guestError');
        
        if (parseInt(guestInput.value) > 12) {
            guestError.textContent = 'The maximum number of guests is 12.';
            guestInput.value = 12;
        } else {
            guestError.textContent = '';
        }
    }

    function openModal() {
        if (!validatePhoneNumber() || !checkFileSize({ target: document.getElementById('screenshot') }) || !validateEmail()) return;

        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const contact = document.getElementById('contact').value;
        const guests = document.getElementById('guests').value;
        const selectedTable = document.getElementById('selectedTable').value;
        const date = document.getElementById('date').value;
        const additionalRequest = document.getElementById('additionalRequest').value;

        document.getElementById('confirmName').textContent = name;
        document.getElementById('confirmEmail').textContent = email;
        document.getElementById('confirmPhone').textContent = contact;
        document.getElementById('confirmGuests').textContent = guests;
        document.getElementById('confirmTable').textContent = selectedTable;
        document.getElementById('confirmDate').textContent = date;
        document.getElementById('confirmRequest').textContent = additionalRequest || 'None';

        document.getElementById('confirmationModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('confirmationModal').style.display = 'none';
    }

    function submitForm() {
        document.getElementById('confirmationModal').style.display = 'none';
        document.getElementById('successMessage').style.display = 'block';
        setTimeout(() => {
            document.getElementById('reservationForm').submit();
        }, 2000);
    }

    function closeSuccessMessage() {
        document.getElementById('successMessage').style.display = 'none';
    }
</script>

</body>
</html>

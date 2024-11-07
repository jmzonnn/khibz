<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khibz Lounge - Reservation</title>
    @vite('resources/css/app.css')
    <style>
        /* Animation for fadeInUp */
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Applying the animation */
        .fadeInUp {
            animation: fadeInUp 1s ease-out forwards;
        }

        /* Background Gradient */
        .bg-gradient {
            background: linear-gradient(135deg, #1c1c1c 0%, #333333 100%);
        }

        /* Enhanced Shadow */
        .shadow-enhanced {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        /* Button Hover Effect */
        .btn-hover:hover {
            background-color: #e60000;
            transition: background-color 0.3s ease-in-out;
        }

        /* No Events Section */
        .no-events {
            text-align: center;
            font-size: 1rem;
            color: #ff6666;
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            opacity: 0;
            animation: fadeInUp 1s ease-in-out 0.5s forwards;
        }

        /* Styling for event section */
        .upcoming-events {
            max-width: 900px;
            margin: 0 auto;
            margin-bottom: 40px;
        }

        .event-card {
            background-color: #fff;
            color: #000;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s;
            text-align: center;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .event-image {
            width: 100%;
            height: auto;
            border-radius: 12px;
            object-fit: cover;
            margin-bottom: 15px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        /* Reservation Form Styling */
        .reservation-form {
            max-width: 350px;
            padding: 30px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            color: #000;
            margin: 40px auto;
        }

        .reservation-form h2 {
            font-size: 1.75rem;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        .reservation-form label {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .reservation-form input[type="date"] {
            font-size: 1.1rem;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
        }

        .reservation-form button {
            font-size: 1.2rem;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            margin-top: 15px;
            transition: background-color 0.3s ease-in-out;
        }

        /* Event Description Styling */
        .upcoming-events h2 {
            font-size: 2.2rem;
            text-align: center;
            margin-bottom: 20px;
            color: #ff6666;
            letter-spacing: 1px;
        }

        .event-description {
            font-size: 0.95rem;
            color: #555;
            text-align: center;
        }

        /* Responsive Image Grid Below */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .gallery-item {
            height: 250px;
            background-position: center;
            background-size: cover;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .reservation-container {
                flex-direction: column;
            }

            .reservation-form, .upcoming-events {
                max-width: 100%;
                margin-bottom: 30px;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('date');
            const today = new Date().toISOString().split('T')[0];
            dateInput.setAttribute('min', today);
        });
    </script>
</head>
<body class="bg-gradient text-white">

@include('layouts._navbar')

<div class="bg-black text-white py-12 fadeInUp">
    <div class="container mx-auto fadeInUp">
        <h1 class="text-4xl font-extrabold text-center mb-6 tracking-wide">MAKE A RESERVATION</h1>
        <p class="text-center mb-10 text-lg leading-relaxed">Join us for an evening of great food, crafted cocktails, and a lively atmosphere at our restobar. Whether you're planning a casual night out or a special celebration, reserving your table ensures you won't miss out on our unique blend of flavor and fun. Reserve now and let us take care of the rest!</p>

        <!-- Upcoming Events Section First -->
        <div class="upcoming-events">
            <h2 class="text-3xl font-bold mb-6 text-center">UPCOMING EVENTS</h2>
            @if($events->isEmpty())
                <!-- Display when there are no upcoming events -->
                <div class="no-events"><h3 class="text-2xl font-semibold mb-4">No Upcoming Events</h3>
                    <p>Stay tuned for our future events. Keep checking back for updates!</p></div>
            @else
                @foreach($events as $event)
                <div class="event-card">
                    <div class="image-container">
                        <img src="{{ asset('storage/' . $event->image) }}" alt="Event Image" class="event-image">
                    </div>
                    
                    <!-- Styled Event Title -->
                    <h2 class="text-4xl font-extrabold text-center mb-4 tracking-wider">{{ strtoupper($event->event_date->format('F j, Y')) }} RESERVATION</h2>
                
                    <p class="text-center text-lg mb-4">
                        {!! nl2br(e($event->description)) !!}
                    </p>
                </div>
                @endforeach
            @endif
        </div>

        <!-- Reservation Form Section at the Bottom -->
        <div class="reservation-form mx-auto">
            <h2 class="text-2xl font-bold">SELECT A DATE</h2>
            <form method="GET" action="{{ route('reservation.details') }}">
                <div class="mb-6">
                    <label for="date" class="block text-lg font-semibold mb-2">Date:</label>
                    <input type="date" id="date" name="date" required>
                </div>
                <button type="submit" class="bg-black text-white w-full py-3 rounded-lg font-semibold btn-hover">Next</button>
            </form>
        </div>

        <!-- Image Gallery Below -->
        <div class="gallery-grid fadeInUp">
            <div class="gallery-item" style="background-image: url('{{ asset('images/bacardi.jpg') }}');"></div>
            <div class="gallery-item" style="background-image: url('{{ asset('images/KhibzLogo.png') }}');"></div>
            <div class="gallery-item" style="background-image: url('{{ asset('images/Cuervo.jpg') }}');"></div>
        </div>
    </div>
</div>

@include('layouts._footer')
</body>
</html>

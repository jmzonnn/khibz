<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Khibz Lounge</title>
    @vite('resources/css/app.css') <!-- Include your compiled CSS -->
</head>
<body class="bg-gray-100">


    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white p-8 rounded shadow-lg max-w-md w-full">
            <h2 class="text-2xl font-bold text-center mb-6">Login to Khibz Lounge</h2>

            <!-- Display Validation Errors -->
            @if ($errors->any())
                <div class="bg-red-100 text-red-600 p-4 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium mb-2">Email Address</label>
                    <input type="email" id="email" name="email" required class="w-full p-2 border border-gray-300 rounded" placeholder="Enter your email">
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium mb-2">Password</label>
                    <input type="password" id="password" name="password" required class="w-full p-2 border border-gray-300 rounded" placeholder="Enter your password">
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center">
                    <button type="submit" class="bg-black text-white py-2 px-4 rounded hover:bg-gray-800 transition duration-200">Login</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>

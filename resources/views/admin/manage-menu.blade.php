@extends('layouts.admin')

@section('title', 'Manage Menu')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-lg mb-10">
    <!-- Header: Actions Section -->
    <div class="flex justify-between items-center mb-8">
        <!-- Button to view all categories -->
        <a href="{{ route('categories.index') }}" class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white px-6 py-3 rounded-lg shadow-md font-semibold flex items-center space-x-2 transition ease-in-out duration-300 hover:from-blue-600 hover:to-indigo-600 hover:shadow-lg">
            <i class="fas fa-list"></i>
            <span>View All Categories</span>
        </a>
        
        <!-- Add button for new menu items -->
        <a href="{{ route('menu-items.create') }}" class="bg-gradient-to-r from-green-400 to-green-600 text-white px-6 py-3 rounded-lg shadow-md font-semibold flex items-center space-x-2 transition ease-in-out duration-300 hover:from-green-500 hover:to-green-700 hover:shadow-lg">
            <i class="fas fa-plus"></i>
            <span>Add New Menu Item</span>
        </a>
    </div>

    <!-- Display the menu items grouped by category -->
    @foreach ($categories as $category)
        <div class="mb-10">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">{{ $category->name }}</h3>

            @if ($category->menuItems->isEmpty())
                <p class="text-gray-500 italic">No items available in this category. Please add some.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">ID</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Name</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Price</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Image</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($category->menuItems as $item)
                                <tr class="hover:bg-gray-50 transition ease-in-out duration-150">
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $item->id }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $item->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">₱{{ number_format($item->price, 2) }}</td>
                                    <td class="px-6 py-4">
                                        @if ($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-16 h-16 object-cover rounded-lg shadow-sm">
                                        @else
                                            <span class="text-gray-500">No Image</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 flex space-x-4">
                                        <!-- Edit Button -->
                                        <a href="{{ route('menu-items.edit', $item->id) }}" class="flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-blue-400 to-blue-600 text-white rounded-lg shadow-md transition ease-in-out duration-200 hover:from-blue-500 hover:to-blue-700">
                                            <i class="fas fa-edit"></i>
                                            <span>Edit</span>
                                        </a>
                                        
                                        <!-- Delete Form Button with SweetAlert2 confirmation -->
                                        <form action="{{ route('menu-items.destroy', $item->id) }}" method="POST" class="inline-block delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-red-400 to-red-600 text-white rounded-lg shadow-md transition ease-in-out duration-200 hover:from-red-500 hover:to-red-700 delete-btn">
                                                <i class="fas fa-trash-alt"></i>
                                                <span>Delete</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endforeach
</div>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('form');

                // Show SweetAlert confirmation
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit the form if confirmed
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection

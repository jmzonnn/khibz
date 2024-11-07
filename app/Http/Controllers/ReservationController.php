<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    public function index()
    {
        // Fetch all events, regardless of date
        $events = Event::all();

        return view('reservation', compact('events'));
    }



    public function details(Request $request)
    {
        $selectedDate = $request->input('date');

        // Fetch reserved tables for the selected date (Pending status)
        $reservedTables = Reservation::whereDate('date', $selectedDate)
            ->whereIn('status', ['Pending', 'Completed'])
            ->pluck('table_number')
            ->toArray();


        // Fetch completed tables for the selected date (Completed status)
        $completedTables = Reservation::whereDate('date', $selectedDate)
            ->where('status', 'Completed')
            ->pluck('table_number')
            ->toArray();

        // dd($reservedTables);

        return view('reservation-details', compact('reservedTables', 'completedTables', 'selectedDate'));
    }

    // Show the reservation form with selected table and date
    public function form(Request $request)
    {
        $selectedTable = $request->query('selectedTable');
        $selectedDate = $request->query('date');
        return view('reservation-form', compact('selectedTable', 'selectedDate'));
    }

    public function submit(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'selectedTable' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email',
            'contact' => 'required|string',
            'guests' => 'nullable|integer|min:0|max:12',
            'screenshot' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'date' => 'required|date',
            'additionalRequest' => 'nullable|string|max:255' // Expect 'additionalRequest' instead of 'requestReason'
        ]);
    
        // Store the screenshot and generate the path
        $screenshotPath = $validated['screenshot']->store('screenshots', 'public');
    
        // Determine the guests count
        $guestsCount = $request->input('guests') !== null ? $request->input('guests') : 0;
    
        // Store the reservation in the database
        Reservation::create([
            'status' => 'Pending',
            'name' => $validated['name'],
            'email' => $validated['email'],
            'contact' => $validated['contact'],
            'table_number' => $validated['selectedTable'],
            'guests' => $guestsCount,
            'payment_reference' => $request->input('refNo'),
            'screenshot' => $screenshotPath,
            'date' => $validated['date'],
            'request_reason' => $validated['additionalRequest'] ?? null // Use 'additionalRequest'
        ]);
    
        return redirect()->route('reservation.index')->with('success', 'Reservation submitted successfully! Your table is now pending.');
    }
    


    // Update the reservation status
    public function updateStatus(Request $request)
    {
        $reservation = Reservation::find($request->input('reservationId'));

        if ($reservation) {
            // Update the status
            $reservation->status = $request->input('status');
            $reservation->save();

            return redirect()->route('admin.reservations')->with('success', 'Reservation status updated successfully!');
        }

        return redirect()->route('reservation.index')->with('error', 'Reservation not found.');
    }

    // Delete a reservation
    public function delete($id)
    {
        $reservation = Reservation::find($id);

        if ($reservation) {
            $reservation->delete();
            return redirect()->route('admin.reservations')->with('success', 'Reservation deleted successfully!');
        }

        return redirect()->route('admin.reservations')->with('error', 'Reservation not found.');
    }

    // Mark all completed reservations as done
    public function markReservationsAsDone()
    {
        // Get all completed reservations
        $reservations = Reservation::where('status', 'Completed')->get();

        if ($reservations->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No completed reservations found.']);
        }

        foreach ($reservations as $reservation) {
            // Update each reservation status to 'Done'
            $reservation->status = 'Done';
            $reservation->save();
        }

        return response()->json(['success' => true, 'message' => 'All completed reservations have been moved to history.']);
    }

    public function history(Request $request)
    {
        $query = Reservation::query();

        // Handle search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('contact', 'like', "%{$search}%")
                ->orWhere('table_number', 'like', "%{$search}%");
        }

        // Handle rows per page
        $rowsPerPage = $request->input('rowsPerPage', 10);
        if ($rowsPerPage === 'all') {
            $historyReservations = $query->where('status', 'Done')->get();
        } else {
            $historyReservations = $query->where('status', 'Done')->paginate($rowsPerPage);
        }

        if ($request->ajax()) {
            return view('admin.partials.history_table', compact('historyReservations'))->render();
        }

        return view('admin.history', compact('historyReservations'));
    }
}
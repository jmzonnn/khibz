<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khibz Lounge - Reservation Details</title>
    @vite('resources/css/app.css')
    <style>
        .floor-plan {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            max-width: 100%;
            margin: 20px auto;
        }

        .table-cell {
            border: 2px solid #333;
            padding: 20px 0;
            text-align: center;
            cursor: pointer;
            width: 50px;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .selected {
            background-color: red;
            color: white;
        }

        .reserved {
            background-color: gray;
            color: white;
            cursor: not-allowed;
        }

        .completed {
            position: relative;
            cursor: not-allowed;
        }

        .completed::after {
            content: 'X';
            color: red;
            font-weight: bold;
            font-size: 1.5em;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .floor-plan-image {
            max-width: 100%;
            height: auto;
            margin: 20px auto;
            display: block;
        }

        #tableSelection {
            display: none;
        }

        .error {
            color: red;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: none;
            /* Hide error by default */
        }

        .floor-plan-container,
        .upper-floor-plan-container {
            display: grid;
            grid-template-columns: repeat(14, 1fr);
            grid-template-rows: repeat(12, 1fr);
            gap: 10px;
            width: 45vw;
            height: 90vh;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px;
        }

        .stage {
            grid-column: 5 / span 5;
            grid-row: 1 / span 2;
            background-color: #000;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
        }

        .soundtech-booth,
        .bar,
        .cr,
        .stairs {
            background-color: #000;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.5em;
            font-weight: bold;
            padding: 10px;
        }

        .soundtech-booth {
            grid-column: 2 / span 3;
            grid-row: 1 / span 2;
        }

        .bar {
            grid-column: 12 / span 1;
            grid-row: 3 / span 3;
        }

        .cr {
            grid-column: 12 / span 1;
            grid-row: 6 / span 2;
        }

        .stairs.left {
            grid-column: 3 / span 2;
            grid-row: 7 / span 2;
        }

        .stairs.bottom-left {
            grid-column: 3 / span 2;
            grid-row: 11 / span 1;
        }

        .stairs.bottom-right {
            grid-column: 11 / span 2;
            grid-row: 11 / span 1;
        }

        /* Seats */
        .seat {
            background-color: #000;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.8em;
            font-weight: bold;
            padding: 10px;
        }

        /* Center the seats A1-A28 */
        .a1 {
            grid-column: 5;
            grid-row: 3;
        }

        .a2 {
            grid-column: 6;
            grid-row: 3;
        }

        .a3 {
            grid-column: 7;
            grid-row: 3;
        }

        .a4 {
            grid-column: 8;
            grid-row: 3;
        }

        .a5 {
            grid-column: 9;
            grid-row: 3;
        }

        .a6 {
            grid-column: 10;
            grid-row: 3;
        }

        .a7 {
            grid-column: 5;
            grid-row: 4;
        }

        .a8 {
            grid-column: 6;
            grid-row: 4;
        }

        .a9 {
            grid-column: 7;
            grid-row: 4;
        }

        .a10 {
            grid-column: 8;
            grid-row: 4;
        }

        .a11 {
            grid-column: 9;
            grid-row: 4;
        }

        .a12 {
            grid-column: 10;
            grid-row: 4;
        }

        .a13 {
            grid-column: 5;
            grid-row: 5;
        }

        .a14 {
            grid-column: 6;
            grid-row: 5;
        }

        .a15 {
            grid-column: 9;
            grid-row: 5;
        }

        .a16 {
            grid-column: 10;
            grid-row: 5;
        }

        .a17 {
            grid-column: 5;
            grid-row: 6;
        }

        .a18 {
            grid-column: 6;
            grid-row: 6;
        }

        .a19 {
            grid-column: 7;
            grid-row: 6;
        }

        .a20 {
            grid-column: 8;
            grid-row: 6;
        }

        .a21 {
            grid-column: 9;
            grid-row: 6;
        }

        .a22 {
            grid-column: 10;
            grid-row: 6;
        }

        .a23 {
            grid-column: 5;
            grid-row: 7;
        }

        .a24 {
            grid-column: 6;
            grid-row: 7;
        }

        .a25 {
            grid-column: 7;
            grid-row: 7;
        }

        .a26 {
            grid-column: 8;
            grid-row: 7;
        }

        .a27 {
            grid-column: 9;
            grid-row: 7;
        }

        .a28 {
            grid-column: 10;
            grid-row: 7;
        }

        /* ME Sections */
        .me {
            background-color: #ffc107;
            color: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.8em;
            font-weight: bold;
            padding: 10px;
        }

        .me1 {
            grid-column: 3 / span 1;
            grid-row: 3 / span 2;
        }

        .me2 {
            grid-column: 3 / span 1;
            grid-row: 5 / span 2;
        }

        .me3 {
            grid-column: 5 / span 2;
            grid-row: 10 / span 1;
        }

        .me4 {
            grid-column: 7 / span 2;
            grid-row: 10 / span 1;
        }

        .me5 {
            grid-column: 9 / span 2;
            grid-row: 10 / span 1;
        }

        .me6 {
            grid-column: 6 / span 2;
            grid-row: 9 / span 1;
        }

        .me7 {
            grid-column: 8 / span 2;
            grid-row: 9 / span 1;
        }

        /* Upper Floor Plan Styles */
        .upper-floor-plan-container {
            display: grid;
            grid-template-columns: repeat(15, 1fr);
            grid-template-rows: repeat(12, 1fr);
            gap: 10px;
            width: 90vw;
            height: 90vh;
            position: relative;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #333;
            /* Dark background for upper floor */
        }

        .md,
        .mc,
        .mb {
            background-color: #ffc107;
            color: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.5em;
            font-weight: bold;
            padding: 10px;
        }

        .mb-cross-out {
            background-color: #ff4d4d;
            color: #fff;
        }

        .stage2 {
            grid-column: 5 / span 8;
            grid-row: 1 / span 2;
            background-color: #000;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
        }

        /* Upper floor */
        .stairs.top-left2 {
            grid-column: 2 / span 3;
            grid-row: 12 / span 1;
        }

        .stairs.top-right2 {
            grid-column: 14 / span 2;
            grid-row: 12 / span 1;
        }

        .stairs.bottom-right2 {
            grid-column: 12 / span 2;
            grid-row: 25 / span 2;
        }

        .stairs.bottom-left2 {
            grid-column: 15 / span 1;
            grid-row: 13 / span 4;
        }

        /* MD Sections */
        .md1 {
            grid-column: 3 / span 1;
            grid-row: 8 / span 2;
        }

        .md2 {
            grid-column: 3 / span 1;
            grid-row: 5 / span 2;
        }

        .md3 {
            grid-column: 3 / span 1;
            grid-row: 2 / span 2;
        }

        /* MC Sections */
        .mc1 {
            grid-column: 13 / span 1;
            grid-row: 11 / span 2;
        }

        .mc2 {
            grid-column: 14 / span 1;
            grid-row: 8 / span 2;
        }

        .mc3 {
            grid-column: 14 / span 1;
            grid-row: 5 / span 2;
        }

        .mc4 {
            grid-column: 14 / span 1;
            grid-row: 2 / span 2;
        }

        .mb1 {
            grid-column: 3 / span 1;
            grid-row: 15 / span 4;
        }

        .mb2 {
            grid-column: 6 / span 3;
            grid-row: 15 / span 1;
        }

        .mb3 {
            grid-column: 9 / span 3;
            grid-row: 15 / span 1;
        }

        .mb4 {
            grid-column: 3 / span 2;
            grid-row: 25 / span 1;
        }

        .mb5 {
            grid-column: 5 / span 3;
            grid-row: 25 / span 1;

        }

        .mb6 {
            grid-column: 8 / span 3;
            grid-row: 25 / span 1;
        }

        .cross-out {
            position: relative;
            color: #ccc;
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            padding: 5px;
        }

        .cross-out::before {
            content: "X";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 25px;
            color: #ff6666;
            font-weight: bold;
        }

        .mb ..cross-out {
            position: relative;
            color: #ccc !important;
            opacity: 0.6;
            background-color: rgba(0, 0, 0, 0.1) !important;
            border-radius: 4px;
            padding: 5px;
        }





        /* Center Block */
        .center {
            grid-column: 5 / span 8;
            grid-row: 3 / span 11;
            background-color: #fff;
            border: 5px solid #333;
        }

        /* More Responsive Styling for Ground Floor */
        @media (max-width: 1024px) {
            .floor-plan-wrapper {
                display: flex;
                flex-direction: column;
                /* Stack floor plans vertically on smaller screens */
                align-items: center;
                /* Center-align the content */
            }

            .floor-plan-container {
                width: 55vw;
                /* Further reduce width */
                height: 50vh;
                /* Further reduce height */
                padding: 5px;
                /* Minimal padding for tighter layout */
                margin: 5px 0;
                /* Reduced margin */
            }

            .table-cell {
                width: 30px;
                /* Slightly larger table cell size */
                height: 30px;
                font-size: 0.6em;
                /* Increase font size for better readability */
                padding: 3px;
                /* More padding within table cells */
            }

            .stage {
                grid-column: 1 / span 4;
                /* Make stage even smaller */
                font-size: 0.6em;
                /* Smaller font size for stage text */
                padding: 3px;
                /* Less padding inside stage */
            }

            .seat,
            .me {
                font-size: 0.6em;
                /* Slightly larger font for seats and ME sections */
                padding: 2px;
                /* Slightly more padding inside seats */
            }

            /* Adjust dropdown styling for smaller screens */
            #tableType {
                width: 100%;
                /* Make the dropdown full-width */
                font-size: 0.9em;
                /* Adjust font size for readability */
                padding: 10px;
                /* More padding for better usability */
                margin-bottom: 12px;
                /* Space below the dropdown */
            }
        }

        @media (max-width: 768px) {
            .floor-plan-container {
                width: 70vw;
                /* Smaller width for medium screens */
                height: 55vh;
                /* Adjust height to be more compact */
                padding: 3px;
                /* Minimal padding */
                margin: 3px 0;
                /* Minimal margin */
            }

            .table-cell {
                width: 35px;
                /* Larger table cell size for smaller screens */
                height: 35px;
                font-size: 0.7em;
                /* Increase font size for compact screens */
                padding: 4px;
                /* Slightly more padding for table cells */
            }

            .stage {
                grid-column: 1 / span 5;
                /* Adjust stage width */
                font-size: 0.5em;
                /* Further reduce font size */
                padding: 2px;
                /* Less padding inside the stage */
            }

            .seat,
            .me {
                font-size: 0.5em;
                /* Slightly larger font for readability */
                padding: 2px;
                /* Slightly more padding for compact display */
            }

            /* Adjust dropdown styling for smaller screens */
            #tableType {
                width: 100%;
                /* Full width */
                font-size: 1em;
                /* Larger font size for better visibility */
                padding: 14px;
                /* Increase padding for touch-friendly interaction */
                margin-bottom: 16px;
                /* More margin below dropdown */
                border-radius: 8px;
                /* Slightly rounded corners for a better look */
            }
        }

        @media (max-width: 480px) {
            .floor-plan-container {
                width: 85vw;
                /* Near full width for smallest screens */
                height: 40vh;
                /* Reduce height significantly */
                padding: 2px;
                /* Minimal padding */
                margin: 2px 0;
                /* Minimal margin for compact layout */
            }

            .table-cell {
                width: 40px;
                /* Larger size for smallest devices */
                height: 40px;
                font-size: 0.8em;
                /* Slightly larger font size */
                padding: 5px;
                /* More padding for better touch targets */
            }

            .stage {
                grid-column: 5 / span 4;
                /* Keep stage small and centered */
                font-size: 0.4em;
                /* Reduce font size to a minimum */
                padding: 1px;
                /* Minimal padding for stage */
            }

            .seat,
            .me {
                font-size: 0.5em;
                /* Increase font size for seats and ME sections */
                padding: 2px;
                /* Slightly more padding for better usability */
            }

            .error {
                font-size: 0.6rem;
                /* Further reduce error message font size */
            }

            /* Adjust dropdown styling for smallest screens */
            #tableType {
                width: 100%;
                /* Full width */
                font-size: 1.1em;
                /* Slightly larger font size */
                padding: 16px;
                /* Increase padding for easier selection */
                margin-bottom: 18px;
                /* More margin for better spacing */
                border: 2px solid #ccc;
                /* Better border for visibility */
                border-radius: 8px;
                /* Rounded corners for improved aesthetics */
            }

            .soundtech-booth,
            .bar,
            .cr,
            .stairs {
                font-size: .4rem;
            }
        }

        /* More Responsive Styling for Upper Floor Plan */
        @media (max-width: 1024px) {
            .upper-floor-plan-container {
                grid-template-columns: repeat(10, 1fr);
                /* Reduce the number of columns */
                grid-template-rows: repeat(8, 1fr);
                /* Reduce the number of rows */
                width: 60vw;
                /* Reduce width */
                height: 60vh;
                /* Reduce height */
                padding: 10px;
                /* Less padding */
                margin: 5px auto;
                /* Center the upper floor plan */
            }
            .stage {
                grid-column: 5 / span 4;
            }

            .stage2,
            .md,
            .mc,
            .mb {
                font-size: 0.4em;
                /* Reduce font size */
                padding: 5px;
                /* Less padding inside the elements */
            }

            .stairs,
            .center {
                font-size: 0.4em;
                /* Smaller font for stairs and center block */
            }
        }

        @media (max-width: 768px) {
            .upper-floor-plan-container {
                grid-template-columns: repeat(8, 1fr);
                /* Fewer columns for compact view */
                grid-template-rows: repeat(6, 1fr);
                /* Fewer rows */
                width: 80vw;
                /* Increase width for better visibility */
                height: 50vh;
                /* Reduce height */
                padding: 5px;
                /* Minimal padding */
                margin: 3px auto;
                /* Center the upper floor plan */
            }

            .stage {
                grid-column: 5 / span 4;
            }
            
            .stage2 {
                grid-column: 6 / span 5;
                /* Adjust to fit new grid */
                font-size: 0.35em;
                /* Smaller font */
                padding: 2px;
                /* Less padding */
            }

            .md,
            .mc,
            .mb {
                font-size: 0.35em;
                /* Reduce font size further */
                padding: 3px;
                /* Minimal padding */
            }
        }

        @media (max-width: 480px) {
            .upper-floor-plan-container {
                grid-template-columns: repeat(6, 1fr);
                /* Minimal columns for smallest view */
                grid-template-rows: repeat(5, 1fr);
                /* Minimal rows */
                width: 90vw;
                /* Near full width */
                height: 40vh;
                /* Reduced height */
                padding: 2px;
                /* Minimal padding */
                margin: 2px auto;
                /* Center the upper floor plan */
            }

            .stage2 {
                grid-column: 5 / span 8;
                /* Adjust to fit new grid */
                font-size: 0.3em;
                /* Minimum font size */
                padding: 1px;
                /* Minimal padding */
            }

            .md,
            .mc,
            .mb {
                font-size: 0.3em;
                /* Further reduce font size */
                padding: 1px;
                /* Minimal padding */
            }

            .stairs,
            .center {
                font-size: 0.3em;
                /* Further reduce font size */
                padding: 1px;
                /* Minimal padding */
            }

            .md1 {
                grid-column: 3 / span 1;
                grid-row: 10 / span 5;
            }

            .md2 {
                grid-column: 3 / span 1;
                grid-row: 5 / span 5;
            }

            .md3 {
                grid-column: 3 / span 1;
                grid-row: 2 / span 3;
            }

            /* MC Sections */
            .mc1 {
                grid-column: 13 / span 1;
                grid-row: 14 / span 2;
            }

            .mc2 {
                grid-column: 14 / span 1;
                grid-row: 9 / span 5;
            }

            .mc3 {
                grid-column: 14 / span 1;
                grid-row: 5 / span 4;
            }

            .mc4 {
                grid-column: 14 / span 1;
                grid-row: 2 / span 3;
            }

            /* MB Sections */
            .mb1 {
                grid-column: 3 / span 1;
                grid-row: 17 / span 3;
            }

            .mb2 {
                grid-column: 6 / span 1;
                grid-row: 17 / span 1;
            }

            .mb3 {
                grid-column: 10 / span 1;
                grid-row: 17 / span 1;
            }

            .mb4 {
                grid-column: 3 / span 2;
                grid-row: 25 / span 1;
            }

            .mb5 {
                grid-column: 5 / span 3;
                grid-row: 25 / span 1;
            }

            .mb6 {
                grid-column: 8 / span 3;
                grid-row: 25 / span 1;
            }


            .center {
                grid-column: 5 / span 8;
                grid-row: 3 / span 14;
            }

            .stairs.top-left2 {
                grid-column: 3 / span 2;
                grid-row: 15 / span 2;
            }

            .stairs.top-right2 {
                grid-column: 14 / span 2;
                grid-row: 15 / span 1;
            }

            .stairs.bottom-left2 {
                grid-column: 15 / span 1;
                grid-row: 16 / span 2;
            }
        }
    </style>
</head>

<body class="bg-white text-white">

    @include('layouts._navbar')

    <div class="bg-black text-white py-12">
        <div class="container mx-auto">
            <h1 class="text-3xl font-bold text-center mb-4">MAKE A RESERVATION</h1>
            <p class="text-center mb-8">Join us for an evening of great food, crafted cocktails, and lively atmosphere at
                our restobar...</p>

            <div class="bg-white text-black p-8 rounded shadow-lg mx-auto max-w-md">
                <h2 class="text-xl font-bold mb-4 text-center">Selected Date: {{ $selectedDate }}</h2>
                <h2 class="text-xl font-bold mb-4 text-center">SELECT A TABLE TYPE</h2>
                <select id="tableType" class="w-full p-2 border border-gray-300 rounded mb-4">
                    <option value="">Choose Table Type</option>
                    <option value="VIP">VIP Table</option>
                    <option value="Cocktail">Cocktail Table</option>
                </select>

                <div id="requirements" class="mb-4"></div>

                <div id="tableSelection">
                    <h2 class="text-xl font-bold mb-4 text-center">SELECT A TABLE</h2>
                    <p class="text-center text-sm">Click on a table to select it!</p>
                    <div class="floor-plan" id="vipTables" style="display: none;">
                        @foreach (['Me1', 'Me2', 'Me3', 'Me4', 'Me5', 'Me6', 'Me7', 'MB1', 'MB2', 'MB3', 'MB4', 'MB5', 'MB6', 'MC1', 'MC2', 'MC3', 'MC4', 'MD1', 'MD2', 'MD3'] as $vipTable)
                            <div id="table-{{ $vipTable }}"
                                class="table-cell
                             {{ in_array($vipTable, $completedTables) ? 'completed' : '' }}
                             {{ in_array($vipTable, $reservedTables) ? 'reserved' : '' }}"
                                data-table="{{ $vipTable }}">
                                {{ $vipTable }}
                            </div>
                        @endforeach
                    </div>
                    <div class="floor-plan" id="cocktailTables" style="display: none;">
                        @foreach (range(1, 28) as $num)
                            @php $tableId = "A{$num}"; @endphp
                            <div id="table-{{ $tableId }}"
                                class="table-cell
                             {{ in_array($tableId, $completedTables) ? 'completed' : '' }}
                             {{ in_array($tableId, $reservedTables) ? 'reserved' : '' }}"
                                data-table="{{ $tableId }}">
                                {{ $tableId }}
                            </div>
                        @endforeach
                    </div>


                    <input type="hidden" id="selectedTable" name="selectedTable" value="">

                    <form method="GET" action="{{ route('reservation.form') }}"
                        onsubmit="return validateSelection()">
                        <input type="hidden" id="date" name="date" value="{{ $selectedDate }}">
                        <input type="hidden" id="selectedTableInput" name="selectedTable" value="">
                        <div class="flex justify-between mt-4">
                            <button type="button" onclick="history.back()"
                                class="bg-gray-500 text-white py-2 px-4 rounded">Back</button>
                            <button type="submit" class="bg-black text-white py-2 px-4 rounded">Next</button>
                        </div>
                        <p id="selectionError" class="error">Please select a table before proceeding.</p>
                    </form>
                </div>
            </div>

            <!-- Floor Plan Title and Instruction -->
            <div class="mt-12 text-center">
                <h2 class="text-2xl font-bold text-white">FLOOR PLAN</h2>
                <p class="text-white">You may check the table layout for your table reference!</p>
            </div>


            <!-- Wrap the floor plans in a container to control their order -->
            <div class="mt-10 text-center flex justify-center floor-plan-wrapper">

                <!-- GROUND FLOOR Plan -->
                <div class="text-white text-center mb-2">
                    <h3 class="text-xl font-bold">GROUND FLOOR</h3>
                </div>
                <div class="floor-plan-container">
                    <div class="stage">STAGE</div>
                    <div class="soundtech-booth">SOUNDTECH BOOTH</div>
                    <div class="bar">BAR</div>
                    <div class="cr">CR</div>
                    <div class="stairs left">STAIRS</div>
                    <div class="stairs bottom-left">STAIRS</div>
                    <div class="stairs bottom-right">STAIRS</div>

                    <div class="seat a1 {{ in_array('A1', $reservedTables) ? 'cross-out' : '' }}">A1</div>
                    <div class="seat a2 {{ in_array('A2', $reservedTables) ? 'cross-out' : '' }}">A2</div>
                    <div class="seat a3 {{ in_array('A3', $reservedTables) ? 'cross-out' : '' }}">A3</div>
                    <div class="seat a4 {{ in_array('A4', $reservedTables) ? 'cross-out' : '' }}">A4</div>
                    <div class="seat a5 {{ in_array('A5', $reservedTables) ? 'cross-out' : '' }}">A5</div>
                    <div class="seat a6 {{ in_array('A6', $reservedTables) ? 'cross-out' : '' }}">A6</div>
                    <div class="seat a7 {{ in_array('A7', $reservedTables) ? 'cross-out' : '' }}">A7</div>
                    <div class="seat a8 {{ in_array('A8', $reservedTables) ? 'cross-out' : '' }}">A8</div>
                    <div class="seat a9 {{ in_array('A9', $reservedTables) ? 'cross-out' : '' }}">A9</div>
                    <div class="seat a10 {{ in_array('A10', $reservedTables) ? 'cross-out' : '' }}">A10</div>
                    <div class="seat a11 {{ in_array('A11', $reservedTables) ? 'cross-out' : '' }}">A11</div>
                    <div class="seat a12 {{ in_array('A12', $reservedTables) ? 'cross-out' : '' }}">A12</div>
                    <div class="seat a13 {{ in_array('A13', $reservedTables) ? 'cross-out' : '' }}">A13</div>
                    <div class="seat a14 {{ in_array('A14', $reservedTables) ? 'cross-out' : '' }}">A14</div>
                    <div class="seat a15 {{ in_array('A15', $reservedTables) ? 'cross-out' : '' }}">A15</div>
                    <div class="seat a16 {{ in_array('A16', $reservedTables) ? 'cross-out' : '' }}">A16</div>
                    <div class="seat a17 {{ in_array('A17', $reservedTables) ? 'cross-out' : '' }}">A17</div>
                    <div class="seat a18 {{ in_array('A18', $reservedTables) ? 'cross-out' : '' }}">A18</div>
                    <div class="seat a19 {{ in_array('A19', $reservedTables) ? 'cross-out' : '' }}">A19</div>
                    <div class="seat a20 {{ in_array('A20', $reservedTables) ? 'cross-out' : '' }}">A20</div>
                    <div class="seat a21 {{ in_array('A21', $reservedTables) ? 'cross-out' : '' }}">A21</div>
                    <div class="seat a22 {{ in_array('A22', $reservedTables) ? 'cross-out' : '' }}">A22</div>
                    <div class="seat a23 {{ in_array('A23', $reservedTables) ? 'cross-out' : '' }}">A23</div>
                    <div class="seat a24 {{ in_array('A24', $reservedTables) ? 'cross-out' : '' }}">A24</div>
                    <div class="seat a25 {{ in_array('A25', $reservedTables) ? 'cross-out' : '' }}">A25</div>
                    <div class="seat a26 {{ in_array('A26', $reservedTables) ? 'cross-out' : '' }}">A26</div>
                    <div class="seat a27 {{ in_array('A27', $reservedTables) ? 'cross-out' : '' }}">A27</div>
                    <div class="seat a28 {{ in_array('A28', $reservedTables) ? 'cross-out' : '' }}">A28</div>

                    <div class="me me1 {{ in_array('Me1', $reservedTables) ? 'cross-out' : '' }}">ME1</div>
                    <div class="me me2 {{ in_array('Me2', $reservedTables) ? 'cross-out' : '' }}">ME2</div>
                    <div class="me me3 {{ in_array('Me3', $reservedTables) ? 'cross-out' : '' }}">ME3</div>
                    <div class="me me4 {{ in_array('Me4', $reservedTables) ? 'cross-out' : '' }}">ME4</div>
                    <div class="me me5 {{ in_array('Me5', $reservedTables) ? 'cross-out' : '' }}">ME5</div>
                    <div class="me me6 {{ in_array('Me6', $reservedTables) ? 'cross-out' : '' }}">ME6</div>
                    <div class="me me7 {{ in_array('Me7', $reservedTables) ? 'cross-out' : '' }}">ME7</div>

                </div>

                <!-- UPPER FLOOR Plan -->
                <div class="text-white text-center mt-4 mb-2">
                    <h3 class="text-xl font-bold">UPPER FLOOR</h3>
                </div>
                <div class="upper-floor-plan-container">
                    <div class="stage2">STAGE</div>
                    <div class="stairs top-left2">STAIRS</div>
                    <div class="stairs top-right2">STAIRS</div>
                    <div class="stairs bottom-right2">STAIRS</div>
                    <div class="stairs bottom-left2">CR</div>

                    <!-- MD and MC Sections -->
                    <div class="md md1
        {{ in_array('MD1', $reservedTables) ? 'cross-out' : '' }}"
                        style="{{ in_array('MD1', $reservedTables) ? ' color: #fff; text-align: center;' : 'text-align: center;' }}">
                        MD1
                    </div>

                    <div class="md md2
        {{ in_array('MD2', $reservedTables) ? 'cross-out' : '' }}"
                        style="{{ in_array('MD2', $reservedTables) ? ' color: #fff; text-align: center;' : 'text-align: center;' }}">
                        MD2
                    </div>

                    <div class="md md3
        {{ in_array('MD3', $reservedTables) ? 'cross-out' : '' }}"
                        style="{{ in_array('MD3', $reservedTables) ? ' color: #fff; text-align: center;' : 'text-align: center;' }}">
                        MD3
                    </div>

                    <div class="mc mc1
        {{ in_array('MC1', $reservedTables) ? 'cross-out' : '' }}"
                        style="{{ in_array('MC1', $reservedTables) ? ' color: #fff; text-align: center;' : 'text-align: center;' }}">
                        MC1
                    </div>

                    <div class="mc mc2
        {{ in_array('MC2', $reservedTables) ? 'cross-out' : '' }}"
                        style="{{ in_array('MC2', $reservedTables) ? ' color: #fff; text-align: center;' : 'text-align: center;' }}">
                        MC2
                    </div>

                    <div class="mc mc3
        {{ in_array('MC3', $reservedTables) ? 'cross-out' : '' }}"
                        style="{{ in_array('MC3', $reservedTables) ? ' color: #fff; text-align: center;' : 'text-align: center;' }}">
                        MC3
                    </div>

                    <div class="mc mc4
        {{ in_array('MC4', $reservedTables) ? 'cross-out' : '' }}"
                        style="{{ in_array('MC4', $reservedTables) ? ' color: #fff; text-align: center;' : 'text-align: center;' }}">
                        MC4
                    </div>




                    <div class="mb mb1
    {{ in_array('MB1', $reservedTables) ? 'cross-out' : '' }}"
                        style="{{ in_array('MB1', $reservedTables) ? ' color: #fff; text-align: center;' : 'text-align: center;' }}">
                        MB1
                    </div>

                    <div class="mb mb2
    {{ in_array('MB2', $reservedTables) ? 'cross-out' : '' }}"
                        style="{{ in_array('MB2', $reservedTables) ? ' color: #fff; text-align: center;' : 'text-align: center;' }}">
                        MB2
                    </div>

                    <div class="mb mb3
    {{ in_array('MB3', $reservedTables) ? 'cross-out' : '' }}"
                        style="{{ in_array('MB3', $reservedTables) ? ' color: #fff; text-align: center;' : 'text-align: center;' }}">
                        MB3
                    </div>

                    <div class="mb mb4
    {{ in_array('MB4', $reservedTables) ? 'cross-out' : '' }}"
                        style="{{ in_array('MB4', $reservedTables) ? ' color: #fff; text-align: center;' : 'text-align: center;' }}">
                        MB4
                    </div>

                    <div class="mb mb5
    {{ in_array('MB5', $reservedTables) ? 'cross-out' : '' }}"
                        style="{{ in_array('MB5', $reservedTables) ? ' color: #fff; text-align: center;' : 'text-align: center;' }}">
                        MB5
                    </div>

                    <div class="mb mb6
    {{ in_array('MB6', $reservedTables) ? 'cross-out' : '' }}"
                        style="{{ in_array('MB6', $reservedTables) ? ' color: #fff; text-align: center;' : 'text-align: center;' }}">
                        MB6
                    </div>





                    <!-- Center Block -->
                    <div class="center"></div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts._footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableTypeSelect = document.getElementById('tableType');
            const requirementsDiv = document.getElementById('requirements');
            const tableSelectionDiv = document.getElementById('tableSelection');
            const vipTablesDiv = document.getElementById('vipTables');
            const cocktailTablesDiv = document.getElementById('cocktailTables');
            const selectedTableInput = document.getElementById('selectedTableInput');
            const selectionError = document.getElementById('selectionError');

            tableTypeSelect.addEventListener('change', function() {
                const selectedType = this.value;
                if (selectedType === 'VIP') {
                    requirementsDiv.innerHTML = `
                <h3 class="font-bold">VIP Reservation Requirements:</h3>
                <ul>
                    <li>VIP guests must avail 1 bundle to reserve their VIP couch.</li>
                    <li>The VIP couch can accommodate 10-12 people.</li>
                    <li>To secure your reservation, a non-refundable downpayment of Php 1,000 should be settled. This amount will be deducted from the total bill on the day of your reservation.</li>
                    <li>Your table will be held for a duration of 2 hours. If your party has not arrived within this time, we reserve the right to disregard your reservation. (Waiting time starts at 8PM)</li>
                    <li>Entrance fee will be charged to your total bill.</li>
                </ul>
            `;
                    tableSelectionDiv.style.display = 'block';
                    vipTablesDiv.style.display = 'grid'; // Show VIP tables
                    cocktailTablesDiv.style.display = 'none'; // Hide Cocktail tables
                } else if (selectedType === 'Cocktail') {
                    requirementsDiv.innerHTML = `
                <h3 class="font-bold">TABLE COCKTAIL RESERVATION:</h3>
                <ul>
                    <li>Minimum of 2 orders of cocktail towers or 1 hard drink.</li>
                    <li>The VIP couch can accommodate 10-12 people.</li>
                    <li>To secure your reservation, a non-refundable downpayment of Php 1,000 should be settled. This amount will be deducted from the total bill on the day of your reservation.</li>
                    <li>Your table will be held for a duration of 2 hours. If your party has not arrived within this time, we reserve the right to disregard your reservation. (Waiting time starts at 8PM)</li>
                </ul>
            `;
                    tableSelectionDiv.style.display = 'block';
                    vipTablesDiv.style.display = 'none'; // Hide VIP tables
                    cocktailTablesDiv.style.display = 'grid'; // Show Cocktail tables
                } else {
                    requirementsDiv.innerHTML = '';
                    tableSelectionDiv.style.display = 'none';
                    vipTablesDiv.style.display = 'none';
                    cocktailTablesDiv.style.display = 'none';
                }
            });

            const tableCells = document.querySelectorAll('.table-cell');
            let selectedTable = null;

            tableCells.forEach(cell => {
                cell.addEventListener('click', function() {
                    // Prevent selection of tables with 'reserved' or 'completed' status
                    if (this.classList.contains('reserved') || this.classList.contains(
                            'completed')) {
                        return; // Don't allow selecting reserved or completed tables
                    }
                    if (selectedTable) {
                        selectedTable.classList.remove('selected');
                    }
                    this.classList.add('selected');
                    selectedTable = this;
                    selectedTableInput.value = this.getAttribute('data-table');
                    selectionError.style.display = 'none'; // Hide error when a table is selected
                });
            });
        });

        function validateSelection() {
            const selectedTable = document.getElementById('selectedTableInput').value;
            const selectionError = document.getElementById('selectionError');

            if (!selectedTable) {
                selectionError.style.display = 'block'; // Show error message
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }
    </script>

</body>

</html>
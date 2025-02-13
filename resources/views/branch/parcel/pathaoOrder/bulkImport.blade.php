@extends('layouts.branch_layout.branch_layout')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <br>
            <div class="controls">
                <input type="file" id="importCsv" accept=".csv">
                <button onclick="exportCSV()" class="btn btn-primary mr-3" style="white-space: nowrap;">Export CSV</button>
                <button id="save" class="btn btn-danger" style="white-space: nowrap;">Confirm Orders</button>
            </div>

            <div id="hotTable"></div>

        </div>
    </div>
@endsection

@push('script_js')
    <script src="https://cdn.jsdelivr.net/npm/handsontable@15.0.0/dist/handsontable.full.min.js"></script>

    <script>
        // Initialize Handsontable
        var container = document.getElementById('hotTable');
        var hot = new Handsontable(container, {
            data: [
                ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ],
                ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ],
                ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ],
                ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ],
                ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ],
                ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ],
                ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ],
                ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ],
                ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ],
                ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ],
                ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ],
                ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ],
                ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ],
                ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ],
                ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ],
                ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ],
                ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ],
                ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ],
                ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ],
                ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ],
                ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ],
            ],
            colHeaders: [
                'ItemType',
                'StoreName',
                'MerchantOrderId',
                'RecipientName(*)',
                'RecipientPhone(*)',
                'RecipientAddress(*)',
                'RecipientCity(*)',
                'RecipientZone(*)',
                'RecipientArea',
                'AmountToCollect(*)',
                'ItemQuantity',
                'ItemWeight',
                'ItemDesc',
                'SpecialInstruction',
            ],
            width: '100%',
            columns: [{
                    type: 'select',
                    selectOptions: ['Parcel', 'Document'],
                },
                {
                    type: 'autocomplete',
                    source: function(query, process) {
                        fetch(
                                `{{ route('branch.handsontable.merchant-store') }}`
                            ) // ⚠️ FIX: Replace with the actual API URL
                            .then((response) => response.json())
                            .then((data) => {

                                let storeName = data.data.data.map(v => v.store_name)
                                process(storeName); // ✅ Correct API response handling

                            })
                            .catch(error => {
                                console.error('API Fetch Error:', error);
                                process([]); // Prevent breaking Handsontable if API fails
                            });
                    },
                    strict: true,
                },
                {
                    type: 'autocomplete',
                    source: function(query, process) {
                        fetch(
                                `{{ route('branch.handsontable.parcel-invoices') }}`
                            ) // ⚠️ FIX: Replace with the actual API URL
                            .then((response) => response.json())
                            .then((data) => {
                                if (!data || !Array.isArray(data)) {
                                    console.error('Invalid API response:', data);
                                    process([]); // Provide an empty array to avoid errors
                                } else {
                                    process(data); // ✅ Correct API response handling
                                }
                            })
                            .catch(error => {
                                console.error('API Fetch Error:', error);
                                process([]); // Prevent breaking Handsontable if API fails
                            });
                    },
                    strict: true,
                },
                {},
                {},
                {},
                {
                    type: 'autocomplete',
                    source: function(query, process) {

                        let selectedRow = hot.getSelectedLast(); // Get selected row
                        if (!selectedRow) return process([]);

                        let rowIndex = selectedRow[0];
                        let selectedCity = hot.getDataAtCell(rowIndex, 6); // Column 6 = RecipientCity

                        fetch(`{{ route('branch.handsontable.get-cities') }}`) // API to get city list
                            .then(response => response.json())
                            .then(data => {
                                // Map data to display Name and store ID internally
                                citySourceData = data.data.data.map(item => item.city_name);
                                process(citySourceData);
                            })
                            .catch(error => {
                                console.error("Error fetching cities:", error);
                                process([]); // Pass an empty array in case of error
                            });
                    },
                    strict: true,
                },
                {
                    type: 'autocomplete', // 🔹 Zone selection (dependent on city)
                    source: function(query, process) {
                        let selectedRow = hot.getSelectedLast(); // Get selected row
                        if (!selectedRow) return process([]);

                        let rowIndex = selectedRow[0];
                        let selectedCity = hot.getDataAtCell(rowIndex, 6); // Column 6 = RecipientCity

                        if (!selectedCity) return process([]);

                        fetch(
                                `{{ route('branch.handsontable.get-zones') }}?city_name=${encodeURIComponent(selectedCity)}`
                            )
                            .then(response => response.json())
                            .then(data => {
                                // Map data to display Name and store ID internally
                                zone = data.data.data.map(item => item.zone_name);

                                process(zone);
                            })
                            .catch(error => {
                                console.error("Error fetching zones:", error);
                                process([]);
                            });
                    },
                    strict: true,
                },
                {
                    type: 'autocomplete', // 🔹 Zone selection (dependent on city)
                    source: function(query, process) {
                        let selectedRow = hot.getSelectedLast(); // Get selected row
                        if (!selectedRow) return process([]);

                        let rowIndex = selectedRow[0];
                        let selectedCity = hot.getDataAtCell(rowIndex, 6); // Column 6 = RecipientCity
                        let selectedZone = hot.getDataAtCell(rowIndex, 7); // Column 6 = RecipientCity

                        if (!selectedZone) return process([]);

                        fetch(
                                `{{ route('branch.handsontable.area-list') }}?zone_name=${encodeURIComponent(selectedZone)}&city_name=${encodeURIComponent(selectedCity)}`
                            )
                            .then(response => response.json())
                            .then(data => {
                                // Map data to display Name and store ID internally
                                area_names = data.data.data.map(item => item.area_name);
                                process(area_names);
                            })
                            .catch(error => {
                                console.error("Error fetching area:", error);
                                process([]);
                            });
                    },
                    strict: true,
                },
                {},
                {},
                {},
                {},
                {},
            ],
            height: 'auto',
            rowHeaders: true,
            stretchH: 'all',
            contextMenu: true,
            afterChange(changes, source) {
                // load order other data
                if (source === 'edit' || source === 'autocomplete') {
                    changes.forEach(([row, col, oldValue, newValue]) => {
                        if (col === 2 && newValue) { // Column index 2 = "MerchantOrderId"
                            fetch(
                                    `{{ route('branch.handsontable.get-order-details') }}?parcel_invoice=${newValue}`
                                )
                                .then(response => response.json())
                                .then(orderData => {
                                    if (orderData) {
                                        hot.setDataAtRowProp(row, 3, orderData.recipientName);
                                        hot.setDataAtRowProp(row, 4, orderData.recipientPhone);
                                        hot.setDataAtRowProp(row, 5, orderData.recipientAddress);
                                        hot.setDataAtRowProp(row, 9, orderData.amountToCollect);
                                        hot.setDataAtRowProp(row, 10, orderData.itemQuantity);
                                        hot.setDataAtRowProp(row, 11, orderData.itemWeight);
                                        hot.setDataAtRowProp(row, 12, orderData.itemDescription);
                                        hot.setDataAtRowProp(row, 13, orderData.specialInstruction);
                                    } else {
                                        console.error("Invalid order data received", orderData);
                                    }
                                })
                                .catch(error => {
                                    console.error("Error fetching order details:", error);
                                });
                        }
                    });
                }
            },
            autoWrapRow: true,
            autoWrapCol: true,
            rowHeaders: true,
            filters: true,
            dropdownMenu: true,
            licenseKey: 'non-commercial-and-evaluation' // Free license
        });

        // Import CSV Function
        document.getElementById('importCsv').addEventListener('change', function(event) {
            var file = event.target.files[0];
            if (!file) return;

            var reader = new FileReader();
            reader.onload = function(e) {
                var csv = e.target.result;
                var rows = csv.split("\n").map(row => row.split(","));
                hot.loadData(rows);
            };
            reader.readAsText(file);
        });

        // Export CSV Function
        function exportCSV() {
            var data = hot.getData();
            var header = hot.getColHeader(); // Get the header row
            var csvContent = [header.join(",")].concat(data.map(row => row.join(","))).join("\n");
            var blob = new Blob([csvContent], {
                type: "text/csv"
            });
            var link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "table_data.csv";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // 
        hot.addHook('beforeChange', function(changes, source) {
            if (!changes) return;
            changes.forEach(([row, col, oldVal, newVal]) => {
                if (col === 2) { // MerchantOrderID column
                    let allValues = hot.getData().map(row => row[2]); // Get all values in column
                    allValues.splice(row, 2); // Remove the current row value to allow updates
                    if (allValues.includes(newVal)) {
                        alert('Error: Order ID must be unique!');
                        changes.splice(0, changes.length); // Cancel change
                    }
                }
            });
        });

        hot.addHook('afterChange', function(changes, source) {
            if (!changes || source === 'loadData') return;

            changes.forEach(([row, prop, oldValue, newValue]) => {
                if (prop === 6 && oldValue !== newValue) { // Column 6 = RecipientCity
                    hot.setDataAtCell(row, 7, null); // Column 7 = Zone (Clear it)
                    hot.setDataAtCell(row, 8, null); // Column 8 = Area (Clear it)
                }

                if (prop === 7 && oldValue !== newValue) { // Column 7 = Zone
                    hot.setDataAtCell(row, 8, null); // Column 8 = Area (Clear it)
                }
            });
        });


        const save = document.querySelector('#save');

        // Add event listener to save button
        save.addEventListener('click', async () => {
            // button disable and loading
            save.disabled = true;
            save.textContent = 'Saving...';
            save.style.cursor = 'progress';

            try {
                // Save all cell's data
                const response = await fetch(`{{ route('branch.handsontable.confirm-orders') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        data: hot.getData()
                    }),
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                let data = await response.json();

                if (!data.success) {
                    alert(data.message);
                }

                if (data.success) {
                    alert(data.message);

                    // reload page
                    window.location.reload();
                }

                // exampleConsole.innerText = 'Data saved';
                // console.log('Data saved successfully');
            } catch (error) {
                console.error('Error saving data:', error);
                // exampleConsole.innerText = 'Error saving data';
            }

           
        });
    </script>
@endpush

@push('style_css')
    <link href="https://cdn.jsdelivr.net/npm/handsontable@15.0.0/dist/handsontable.full.min.css" rel="stylesheet">
    <style>
        .controls {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }
    </style>
@endpush

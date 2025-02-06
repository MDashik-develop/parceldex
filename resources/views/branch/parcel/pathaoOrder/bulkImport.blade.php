@extends('layouts.branch_layout.branch_layout')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <br>
            <div class="controls">
                <input type="file" id="importCsv" accept=".csv">
                <button onclick="exportCSV()" class="btn btn-primary" style="white-space: nowrap;">Export CSV</button>
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
            height: 'auto',
            rowHeaders: true,
            stretchH: 'all',
            contextMenu: true,
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

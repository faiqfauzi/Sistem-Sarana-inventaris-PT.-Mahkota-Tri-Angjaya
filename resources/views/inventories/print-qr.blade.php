<!DOCTYPE html>
<html>

<head>

    <title>Print QR Sarana Inventaris</title>

    <style>
        body {
            font-family: Arial;
        }

        .item {

            width: 220px;

            display: inline-block;

            margin: 20px;

            text-align: center;

            page-break-inside: avoid;

        }
    </style>

</head>

<body>

    @foreach ($inventories as $inventory)
        <div class="item">

            {!! QrCode::size(170)->generate(route('inventories.document', $inventory)) !!}

            <br>

            <strong>

                {{ $inventory->nomor_inventaris }}

            </strong>

        </div>
    @endforeach


    <script>
        window.print();
    </script>

</body>

</html>

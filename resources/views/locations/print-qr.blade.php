<!DOCTYPE html>
<html>

<head>

    <title>Print QR Lokasi</title>

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

    @foreach ($locations as $location)
        <div class="item">

            {!! QrCode::size(170)->generate(route('locations.document', $location)) !!}

            <br>

            <strong>
                {{ $location->nama_lokasi }}
            </strong>

        </div>
    @endforeach

    <script>
        window.print();
    </script>

</body>

</html>

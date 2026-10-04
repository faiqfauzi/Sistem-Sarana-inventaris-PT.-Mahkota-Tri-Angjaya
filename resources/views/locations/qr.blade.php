@extends('layouts.app')

@section('content')
    <div class="container-xl">

        <div class="card">

            <div class="card-header">
                <h3 class="card-title">
                    QR Code Lokasi
                </h3>
            </div>

            <div class="card-body">

                <div class="qr-container">

                    <div class="qr-code">
                        {!! QrCode::size(350)->generate(route('locations.document', $location)) !!}
                    </div>

                    <div class="qr-info">

                        <div class="qr-title">
                            {{ $location->nama_lokasi }}
                        </div>

                        <div class="qr-subtitle">
                            {{ $inventories->count() }} Sarana Inventaris
                        </div>

                        <button type="button" class="btn btn-primary" onclick="window.print()">
                            <i class="ti ti-printer me-1"></i>
                            Print QR
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <style>
        .qr-container {
            width: 100%;
            min-height: 520px;

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;

            text-align: center;
        }

        .qr-code {
            display: flex;
            align-items: center;
            justify-content: center;

            margin-bottom: 20px;
        }

        .qr-code svg {
            display: block;
            width: 350px;
            height: 350px;
        }

        .qr-info {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .qr-title {
            font-size: 18px;
            font-weight: 600;
            color: #182433;
            margin-bottom: 4px;
        }

        .qr-subtitle {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 18px;
        }

        @media print {

            .page-header,
            .navbar,
            .sidebar,
            .card-header {
                display: none !important;
            }

            .container-xl {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .qr-container {
                min-height: auto;
                padding-top: 40px;
            }

            .qr-code svg {
                width: 350px;
                height: 350px;
            }

            .btn {
                display: none !important;
            }
        }
    </style>
@endsection

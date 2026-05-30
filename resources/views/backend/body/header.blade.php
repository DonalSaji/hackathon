<head>
    <meta charset="utf-8" />
    <title>{{ env('APP_NAME') }} | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base_url" content="{{ url('') }}">
    <meta name="user-id" content="{{ Auth::id() }}">

    <!-- PWA  -->
    <meta name="theme-color" content="#6777ef" >
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
    
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    {{-- <script>
        (function() {
            // Save original setItem method
            const originalSetItem = sessionStorage.setItem;

            // Create a wrapper for sessionStorage.setItem
            sessionStorage.setItem = function(key, value) {
                // If the key is '__HYPER_CONFIG__', log it
                if (key === "__HYPER_CONFIG__") {
                    console.log(`__HYPER_CONFIG__ changed to:`, value);
                    console.log(`Current URL: ${window.location.href}`);
                }

                // Call the original setItem method
                originalSetItem.apply(this, arguments);
            };
        })();
    </script> --}}

    <!-- Daterangepicker css -->
    <link href="{{ asset('assets/vendor/daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css">

    <!-- Vector Map css -->
    <link href="{{ asset('assets/vendor/jsvectormap/jsvectormap.min.css') }}" rel="stylesheet" type="text/css">

    @stack('custom-css')
    <!-- Theme Config Js -->
    <script src="{{ asset('assets/js/hyper-config.js') }}"></script>

    <!-- Vendor css -->
    <link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Plugin css -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/jquery-toast-plugin/jquery.toast.min.css') }}">

    <!-- App css -->
    <link href="{{ asset('assets/css/app-modern.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media(max-width:576px) {
            .backRefillingbtn1 {
                display: none !important;
            }

            .backRefillingbtn2 {
                display: block !important;
            }

            .modal-dialog {
                max-width: 90% !important;
                margin: 1.75rem auto;
            }

            #CheckIn,
            #CheckOut,
            #Complete {
                left: 0 !important;
                width: 100% !important;
                border-radius: 8px !important;
            }
        }

        /* Camera Modal Styles */
        .camera-preview {
            width: 100%;
            height: 300px;
            background-color: #f1f3f5;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .camera-icon {
            font-size: 48px;
            color: #6c757d;
        }

        #capturedImage {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none !important;
        }

        .location-data {
            background-color: #e9ecef;
            padding: 12px;
            border-radius: 8px;
            margin: 15px 0;
            font-size: 14px;
        }

        .metadata-card {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            font-size: 13px;
        }

        .permission-alert {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px;
            border-radius: 4px;
            display: none;
        }

        .timestamp {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .camera-error {
            color: #dc3545;
            text-align: center;
            padding: 20px;
        }

        .camera-controls {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .upload-container {
            margin-top: 15px;
            padding: 15px;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            text-align: center;
        }

        .upload-preview {
            max-width: 100%;
            max-height: 200px;
            margin-top: 10px;
            display: none;
        }

        .map-container {
            height: 200px;
            margin-top: 15px;
            border-radius: 8px;
            overflow: hidden;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .address-info {
            margin-top: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            font-size: 14px;
        }

        .map-placeholder {
            color: #6c757d;
            text-align: center;
            padding: 20px;
        }

        .image-upload-section {
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .btn-action {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        #preview-container {
            border-radius: 8px;
            overflow: hidden;
            position: relative;
        }

        #image-preview {
            width: 100%;
            height: auto;
            max-height: 300px;
            object-fit: contain;
            border-radius: 8px;
        }

        /* Camera modal improvements */
        #camera-modal .modal-dialog {
            max-width: 100%;
            margin: 0;
        }

        #camera-modal .modal-content {
            height: 100vh;
            border-radius: 0;
        }

        #camera-modal .modal-body {
            padding: 0;
            display: flex;
            flex-direction: column;
            height: calc(100% - 120px);
        }

        #video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }



        .capture-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 4px solid #f8f9fa;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .switch-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            color: rgb(134, 132, 132);
            border: none;
        }

        .btn-xs {
            padding: 0.15rem 0.35rem !important;
            font-size: 0.75rem !important;
            line-height: 1 !important;
            border-radius: 0.2rem !important;
        }

        /* Add these new styles to your existing CSS */
        .image-preview-hover {
            position: relative;
            overflow: hidden;
        }

        .image-preview-hover .hover-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .image-preview-hover:hover .hover-overlay {
            opacity: 1;
        }

        .hover-overlay .eye-icon {
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Modal for enlarged image */
        .enlarged-image-modal .modal-dialog {
            max-width: 90%;
            max-height: 90vh;
        }

        .enlarged-image-modal .modal-body {
            text-align: center;
            padding: 0;
        }

        .enlarged-image-modal img {
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
        }

        @media(max-width: 768px) {

            .btn-action {
                width: 100%;
                justify-content: center;
                margin-bottom: 0.5rem;
            }

        }



        @media(min-width: 768px) {
            #camera-modal .modal-dialog {
                max-width: 500px;
                margin: 1.75rem auto;
            }

            #camera-modal .modal-content {
                height: auto;
                border-radius: 0.5rem;
            }

            #camera-modal .modal-body {
                height: auto;
                padding: 1rem;
            }

            .camera-controls {
                position: static;
                margin-top: 1rem;
            }
        }
    </style>

</head>

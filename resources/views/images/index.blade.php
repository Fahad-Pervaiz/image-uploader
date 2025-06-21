{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Image Uploading with Vue + Laravel</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="max-w-lg mx-auto mt-24">
        <h1 class="text-4xl font-bold text-center">Image Uploader</h1>
        <div id="app"></div>
    </div>
</body>
</html> --}}
{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Image Uploader</title>

    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Optional: Smooth transitions */
        .transition-all {
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto px-4 py-10">
        <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-2xl p-8">
            <h1 class="text-3xl md:text-4xl font-extrabold text-center text-blue-600 mb-6">📤 Upload Your Images</h1>

            <!-- Vue App Mount Point -->
            <div id="app" class="mb-10"></div>

            <!-- Footer / Branding -->
            <p class="text-center text-sm text-gray-400">Powered by Vue.js + Laravel + FilePond</p>
        </div>
    </div>
</body>
</html> --}}
<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <meta http-equiv="X-UA-Compatible" content="ie=edge" />
            <meta name="csrf-token" content="{{ csrf_token() }}" />
            <title>📤 Image Uploader</title>

            <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet" />
            @vite(['resources/css/app.css', 'resources/js/app.js'])

            <style>
                body {
                font-family: 'Inter', sans-serif;
                }

                .fade-in {
                animation: fadeIn 0.8s ease-in;
                }

                @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
                }
            </style>
        </head>
        <body class="bg-gradient-to-br from-blue-50 via-white to-gray-100 text-gray-800">
            <div class="min-h-screen flex items-center justify-center p-4">
                <div class="w-full max-w-4xl bg-white shadow-2xl rounded-2xl p-8 fade-in">
                    <h1 class="text-3xl md:text-4xl font-bold text-center text-blue-700 mb-4">📁 Upload Your Images</h1>
                    <p class="text-center text-gray-500 mb-6">Use the uploader below to submit your images. View them in the gallery instantly.</p>

                    <!-- Vue App Mount Point -->
                    @if(Auth::check())
                        <div id="app" class="mb-10"></div>
                    @else
                        <a href="/login">Please login to access your gallery</a>
                    @endif


                    <!-- Footer -->
                    <div class="text-center pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-400">✨ Built with Vue.js + Laravel + Tailwind + FilePond</p>
                    </div>
                </div>
            </div>
        </body>
</html>


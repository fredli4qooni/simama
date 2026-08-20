<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIMAMA') }} - UIN Raden Intan Lampung</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-white">
        
        <div class="flex min-h-screen">
            
            <div class="hidden lg:flex lg:w-3/5 relative items-center justify-center overflow-hidden bg-uin">
                <div class="absolute inset-0 bg-gradient-to-br from-uin-hover to-uin opacity-95"></div>
                
                <div class="relative z-10 px-16 text-white max-w-2xl">
                    <h1 class="text-5xl font-bold mb-6 leading-tight">Sistem Informasi <br> Manajemen Peserta Magang</h1>
                    <p class="text-lg text-blue-100 mb-8 border-l-4 border-blue-400 pl-4">
                        Program Studi Sistem Informasi<br>
                        Universitas Islam Negeri (UIN) Raden Intan Lampung
                    </p>
                    
                    <div class="flex items-center space-x-6 text-sm text-blue-200 font-medium">
                        <span class="flex items-center"><svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> Efisien</span>
                        <span class="flex items-center"><svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> Transparan</span>
                        <span class="flex items-center"><svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> Terintegrasi</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col justify-center w-full lg:w-2/5 px-8 sm:px-16 lg:px-24 py-12 bg-white shadow-2xl z-20">
                <div class="w-full max-w-sm mx-auto">
                    
                    <div class="lg:hidden mb-8 text-center">
                        <h1 class="text-3xl font-bold text-uin tracking-wider">SIMAMA</h1>
                        <p class="text-sm text-gray-500 mt-1">UIN Raden Intan Lampung</p>
                    </div>

                    {{ $slot }}
                    
                    <div class="mt-12 text-center text-xs text-gray-400">
                        &copy; {{ date('Y') }} Prodi Sistem Informasi UIN RIL.
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>
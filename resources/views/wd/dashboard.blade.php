@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h3 class="text-xl font-black text-gray-800 tracking-tight">Dashboard</h3>
</div>

<livewire:statistik.statistik-analysis />

@vite('resources/js/dashboard.js')
@endsection
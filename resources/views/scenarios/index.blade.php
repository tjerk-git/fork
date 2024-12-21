@extends('layouts.app')

@section('title', 'Scenarios')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold tracking-tight">Scenarios</h1>
        <a href="{{ route('scenarios.create') }}" 
           class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground ring-offset-background transition-colors hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
            <i class="fa-solid fa-plus mr-2"></i> Nieuw scenario
        </a>
    </div>

    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row gap-4">
            <input type="search" 
                   id="scenarioSearch" 
                   placeholder="Zoek op naam..." 
                   class="flex h-10 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
            <input type="date" 
                   id="dateFilter" 
                   class="flex h-10 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
            <button onclick="resetFilters()" 
                    class="inline-flex h-10 items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium ring-offset-background transition-colors hover:bg-accent hover:text-accent-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                Reset filters
            </button>
        </div>

        <div class="rounded-md border">
            <table class="w-full">
                <thead>
                    <tr class="border-b bg-muted/50 transition-colors">
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Naam</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Gepubliceerd?</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Aangemaakt op</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Acties</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($scenarios as $scenario)
                        <tr class="scenario-row border-b transition-colors hover:bg-muted/50">
                            <td class="p-4" data-name="{{ strtolower($scenario->name) }}">{{ $scenario->name }}</td>
                            <td class="p-4">
                                @if($scenario->is_public)
                                    <i class="fas fa-eye text-primary"></i>
                                @else
                                    <i class="fas fa-eye-slash text-muted-foreground"></i>
                                @endif
                            </td>
                            <td class="p-4" data-date="{{ $scenario->created_at->format('Y-m-d') }}">
                                {{ $scenario->created_at->format('d-m-Y') }}
                            </td>
                            <td class="p-4">
                                <a href="{{ route('scenarios.show', $scenario) }}" 
                                   class="inline-flex items-center justify-center rounded-md border border-input bg-background px-3 py-2 text-sm font-medium ring-offset-background transition-colors hover:bg-accent hover:text-accent-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                                    <i class="fas fa-eye text-primary mr-2"></i> Bekijken
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($scenarios->isEmpty())
            <p class="text-center text-sm text-muted-foreground">Je hebt nog geen scenario's gemaakt</p>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('scenarioSearch');
    const dateFilter = document.getElementById('dateFilter');
    const rows = document.querySelectorAll('.scenario-row');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedDate = dateFilter.value;

        rows.forEach(row => {
            const name = row.querySelector('td[data-name]').dataset.name;
            const date = row.querySelector('td[data-date]').dataset.date;
            
            const matchesSearch = name.includes(searchTerm);
            const matchesDate = !selectedDate || date === selectedDate;

            row.style.display = matchesSearch && matchesDate ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    dateFilter.addEventListener('change', filterTable);
});

function resetFilters() {
    document.getElementById('scenarioSearch').value = '';
    document.getElementById('dateFilter').value = '';
    document.querySelectorAll('.scenario-row').forEach(row => {
        row.style.display = '';
    });
}
</script>
@endsection

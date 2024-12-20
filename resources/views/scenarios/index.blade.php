@extends('layouts.app')

@section('title', 'Scenarios')

@section('content')
<div class="headings">
    <h1>Scenarios</h1>
    <a href="{{ route('scenarios.create') }}" role="button" class="primary">
        <i class="fa-solid fa-plus"></i> Nieuw scenario
    </a>
</div>

<div class="filters" style="margin-bottom: 1rem;">
    <input type="search" id="scenarioSearch" placeholder="Zoek op naam..." style="margin-bottom: 1rem;">
    <input type="date" id="dateFilter" style="margin-bottom: 1rem;">
    <button onclick="resetFilters()" class="outline">Reset filters</button>
</div>

<div class="table-container" style="overflow-x: auto;">
    <table role="grid">
        <thead>
            <tr>
                <th scope="col">Naam</th>
                <th scope="col">Gepubliceerd?</th>
                <th scope="col">Aangemaakt op</th>
                <th scope="col">Acties</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($scenarios as $scenario)
                <tr class="scenario-row">
                    <td data-name="{{ strtolower($scenario->name) }}">{{ $scenario->name }}</td>
                    <td>
                        @if($scenario->is_public)
                            <i class="fas fa-eye" style="color: var(--pico-primary)"></i>
                        @else
                            <i class="fas fa-eye-slash" style="color: var(--pico-muted-color)"></i>
                        @endif
                    </td>
                    <td data-date="{{ $scenario->created_at->format('Y-m-d') }}">
                        {{ $scenario->created_at->format('d-m-Y') }}
                    </td>
                    <td>
                        <a href="{{ route('scenarios.show', $scenario) }}" role="button" class="outline">  <i class="fas fa-eye" style="color: var(--pico-primary)"></i> Bekijken</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if ($scenarios->isEmpty())
    <p>Je hebt nog geen scenario's gemaakt</p>
@endif

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

<style>
.headings {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.filters {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

@media (min-width: 768px) {
    .filters {
        grid-template-columns: 1fr 1fr auto;
    }
}
</style>
@endsection

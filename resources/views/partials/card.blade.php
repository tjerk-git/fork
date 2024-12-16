<div class="card-item" data-id="{{ $card->id }}">
    <div class="card-header">
        <h5 class="card-title">{{ $card->title }}</h5>
        <div class="dropdown">
            <button class="outline" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-ellipsis-v"></i>
            </button>
            <ul>
                <li><a href="#" onclick="editCard({{ $card->id }})">Bewerken</a></li>
                <li><a href="#" class="delete" onclick="deleteCard({{ $card->id }})">Verwijderen</a></li>
            </ul>
        </div>
    </div>
    @if($card->description)
        <p class="card-text">{{ Str::limit($card->description, 100) }}</p>
    @endif
    @if($card->due_date)
        <small class="due-date" data-date="{{ $card->due_date }}">
            <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($card->due_date)->format('d-m-Y') }}
        </small>
    @endif
</div>

<style>
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
    }

    .card-title {
        margin: 0;
        font-size: 1rem;
        font-weight: 500;
    }

    .card-text {
        margin: 0.5rem 0;
        font-size: 0.9rem;
    }

    .due-date {
        display: block;
        margin-top: 0.5rem;
        color: var(--muted-color);
    }

    .dropdown button {
        padding: 0.25rem 0.5rem;
        margin: -0.25rem -0.5rem 0 0;
    }

    .dropdown ul {
        min-width: 120px;
        padding: 0.5rem;
        margin: 0;
        list-style: none;
        background: var(--card-background-color);
        border-radius: var(--border-radius);
        box-shadow: var(--card-box-shadow);
    }

    .dropdown ul li:not(:last-child) {
        margin-bottom: 0.25rem;
    }
</style>

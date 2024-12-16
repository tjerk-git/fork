@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="grid">
        <div>
            <h1>Planning Board</h1>
            <button class="outline" onclick="openNewBoardModal()">New Board</button>
        </div>
    </div>

    <div class="board-container">
        @foreach($boards as $board)
        <article class="board" data-id="{{ $board->id }}">
            <div class="board-header">
                <h3>{{ $board->title }}</h3>
                <div class="dropdown">
                    <button class="outline" type="button" onclick="toggleDropdown(this)">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul>
                        <li><a href="#" onclick="editBoard({{ $board->id }})">Edit</a></li>
                        <li><a href="#" class="delete" onclick="deleteBoard({{ $board->id }})">Delete</a></li>
                    </ul>
                </div>
            </div>

            <div class="board-content">
                <div class="board-columns">
                    <div class="column">
                        <h4>To Do</h4>
                        <div class="cards-container" data-board-id="{{ $board->id }}" data-status="todo">
                            @foreach($board->cards->where('status', 'todo')->sortBy('order') as $card)
                            <article class="card-item" data-id="{{ $card->id }}">
                                <div class="card-header">
                                    <h5 class="card-title">{{ $card->title }}</h5>
                                    <div class="dropdown">
                                        <button class="outline" type="button" onclick="toggleDropdown(this)">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul>
                                            <li><a href="#" onclick="editCard({{ $card->id }})">Edit</a></li>
                                            <li><a href="#" class="delete" onclick="deleteCard({{ $card->id }})">Delete</a></li>
                                        </ul>
                                    </div>
                                </div>
                                @if($card->description)
                                    <p class="card-text">{{ Str::limit($card->description, 100) }}</p>
                                @endif
                                <span class="priority-badge {{ $card->priority }}">
                                    {{ ucfirst($card->priority) }}
                                </span>
                            </article>
                            @endforeach
                        </div>
                        <button class="outline new-card-btn" onclick="openNewCardModal({{ $board->id }}, 'todo')">Add Card</button>
                    </div>

                    <div class="column">
                        <h4>In Progress</h4>
                        <div class="cards-container" data-board-id="{{ $board->id }}" data-status="backlog">
                            @foreach($board->cards->where('status', 'backlog')->sortBy('order') as $card)
                            <article class="card-item" data-id="{{ $card->id }}">
                                <div class="card-header">
                                    <h5 class="card-title">{{ $card->title }}</h5>
                                    <div class="dropdown">
                                        <button class="outline" type="button" onclick="toggleDropdown(this)">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul>
                                            <li><a href="#" onclick="editCard({{ $card->id }})">Edit</a></li>
                                            <li><a href="#" class="delete" onclick="deleteCard({{ $card->id }})">Delete</a></li>
                                        </ul>
                                    </div>
                                </div>
                                @if($card->description)
                                    <p class="card-text">{{ Str::limit($card->description, 100) }}</p>
                                @endif
                                <span class="priority-badge {{ $card->priority }}">
                                    {{ ucfirst($card->priority) }}
                                </span>
                            </article>
                            @endforeach
                        </div>
                        <button class="outline new-card-btn" onclick="openNewCardModal({{ $board->id }}, 'backlog')">Add Card</button>
                    </div>

                    <div class="column">
                        <h4>Done</h4>
                        <div class="cards-container" data-board-id="{{ $board->id }}" data-status="done">
                            @foreach($board->cards->where('status', 'done')->sortBy('order') as $card)
                            <article class="card-item" data-id="{{ $card->id }}">
                                <div class="card-header">
                                    <h5 class="card-title">{{ $card->title }}</h5>
                                    <div class="dropdown">
                                        <button class="outline" type="button" onclick="toggleDropdown(this)">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul>
                                            <li><a href="#" onclick="editCard({{ $card->id }})">Edit</a></li>
                                            <li><a href="#" class="delete" onclick="deleteCard({{ $card->id }})">Delete</a></li>
                                        </ul>
                                    </div>
                                </div>
                                @if($card->description)
                                    <p class="card-text">{{ Str::limit($card->description, 100) }}</p>
                                @endif
                                <span class="priority-badge {{ $card->priority }}">
                                    {{ ucfirst($card->priority) }}
                                </span>
                            </article>
                            @endforeach
                        </div>
                        <button class="outline new-card-btn" onclick="openNewCardModal({{ $board->id }}, 'done')">Add Card</button>
                    </div>
                </div>
            </div>
        </article>
        @endforeach
    </div>
</div>

<!-- New Board Modal -->
<dialog id="newBoardModal">
    <article>
        <header>
            <h3>New Board</h3>
            <a href="#close" aria-label="Close" class="close" onclick="closeNewBoardModal()"></a>
        </header>
        <form id="newBoardForm" onsubmit="createBoard(event)">
            <div class="grid">
                <label for="boardTitle">
                    Title
                    <input type="text" id="boardTitle" name="title" required>
                </label>
            </div>
            <div class="grid">
                <label for="boardDescription">
                    Description
                    <textarea id="boardDescription" name="description"></textarea>
                </label>
            </div>
            <footer>
                <button type="submit" class="primary">Create</button>
                <button type="button" class="secondary" onclick="closeNewBoardModal()">Cancel</button>
            </footer>
        </form>
    </article>
</dialog>

<!-- Edit Board Modal -->
<dialog id="editBoardModal">
    <article>
        <header>
            <h3>Edit Board</h3>
            <a href="#close" aria-label="Close" class="close" onclick="closeEditBoardModal()"></a>
        </header>
        <form id="editBoardForm" onsubmit="updateBoard(event)">
            <input type="hidden" id="editBoardId">
            <div class="grid">
                <label for="editBoardTitle">
                    Title
                    <input type="text" id="editBoardTitle" name="title" required>
                </label>
            </div>
            <div class="grid">
                <label for="editBoardDescription">
                    Description
                    <textarea id="editBoardDescription" name="description"></textarea>
                </label>
            </div>
            <footer>
                <button type="submit" class="primary">Update</button>
                <button type="button" class="secondary" onclick="closeEditBoardModal()">Cancel</button>
            </footer>
        </form>
    </article>
</dialog>

<!-- New Card Modal -->
<dialog id="newCardModal">
    <article>
        <header>
            <h3>New Card</h3>
            <a href="#close" aria-label="Close" class="close" onclick="closeNewCardModal()"></a>
        </header>
        <form id="newCardForm" onsubmit="createCard(event)">
            <input type="hidden" id="newCardBoardId" name="board_id">
            <input type="hidden" id="newCardStatus" name="status">
            <div class="grid">
                <label for="cardTitle">
                    Title
                    <input type="text" id="cardTitle" name="title" required>
                </label>
            </div>
            <div class="grid">
                <label for="cardDescription">
                    Description
                    <textarea id="cardDescription" name="description"></textarea>
                </label>
            </div>
            <div class="grid">
                <label for="cardPriority">
                    Priority
                    <select id="cardPriority" name="priority" required>
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </label>
            </div>
            <footer>
                <button type="submit" class="primary">Create</button>
                <button type="button" class="secondary" onclick="closeNewCardModal()">Cancel</button>
            </footer>
        </form>
    </article>
</dialog>

<!-- Edit Card Modal -->
<dialog id="editCardModal">
    <article>
        <header>
            <h3>Edit Card</h3>
            <a href="#close" aria-label="Close" class="close" onclick="closeEditCardModal()"></a>
        </header>
        <form id="editCardForm" onsubmit="updateCard(event)">
            <input type="hidden" id="editCardId">
            <div class="grid">
                <label for="editCardTitle">
                    Title
                    <input type="text" id="editCardTitle" name="title" required>
                </label>
            </div>
            <div class="grid">
                <label for="editCardDescription">
                    Description
                    <textarea id="editCardDescription" name="description"></textarea>
                </label>
            </div>
            <div class="grid">
                <label for="editCardPriority">
                    Priority
                    <select id="editCardPriority" name="priority" required>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </label>
            </div>
            <footer>
                <button type="submit" class="primary">Update</button>
                <button type="button" class="secondary" onclick="closeEditCardModal()">Cancel</button>
            </footer>
        </form>
    </article>
</dialog>

<style>
    .container-fluid {
        width: 100%;
        padding: 0 1rem;
        margin: 0;
    }

    .board-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .board-container {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
        padding: 1rem 0;
    }

    .board {
        background: var(--card-background-color);
        padding: 1rem;
        border-radius: var(--border-radius);
        box-shadow: var(--card-box-shadow);
        margin: 0;
    }

    .board-columns {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        min-height: calc(100vh - 200px);
    }

    .column {
        background: var(--card-sectionning-background-color);
        padding: 1rem;
        border-radius: var(--border-radius);
    }

    .column h4 {
        margin: 0 0 1rem 0;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--muted-border-color);
    }

    .cards-container {
        min-height: 200px;
        margin-bottom: 1rem;
    }

    .card-item {
        background: var(--card-sectionning-background-color);
        padding: 1rem;
        margin-bottom: 0.5rem;
        border-radius: var(--border-radius);
        cursor: grab;
    }

    .card-item:active {
        cursor: grabbing;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown ul {
        display: none;
        position: absolute;
        right: 0;
        min-width: 120px;
        background: var(--card-background-color);
        border-radius: var(--border-radius);
        box-shadow: var(--card-box-shadow);
        z-index: 1000;
    }

    .dropdown.active ul {
        display: block;
    }

    .dropdown ul li {
        padding: 0.5rem 1rem;
    }

    .dropdown ul li:hover {
        background: var(--card-sectionning-background-color);
    }

    .sortable-ghost {
        opacity: 0.5;
    }

    .priority-badge {
        display: inline-block;
        padding: 0.2rem 0.5rem;
        border-radius: var(--border-radius);
        font-size: 0.8rem;
        font-weight: 500;
    }

    .priority-badge.high {
        background-color: var(--del-color);
        color: white;
    }

    .priority-badge.medium {
        background-color: var(--primary);
        color: white;
    }

    .priority-badge.low {
        background-color: var(--muted-color);
        color: white;
    }

    @media (max-width: 768px) {
        .board-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Sortable for each cards container
        document.querySelectorAll('.cards-container').forEach(container => {
            new Sortable(container, {
                group: 'cards',
                animation: 150,
                onEnd: function(evt) {
                    const cardId = evt.item.dataset.id;
                    const newBoardId = evt.to.dataset.boardId;
                    const newStatus = evt.to.dataset.status;
                    const cards = evt.to.children;
                    const newOrder = Array.from(cards).indexOf(evt.item);
                    
                    updateCardPosition(cardId, newBoardId, newOrder, newStatus);
                }
            });
        });
    });

    function toggleDropdown(button) {
        const dropdown = button.closest('.dropdown');
        dropdown.classList.toggle('active');

        // Close dropdown when clicking outside
        const closeDropdown = (e) => {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('active');
                document.removeEventListener('click', closeDropdown);
            }
        };
        document.addEventListener('click', closeDropdown);
    }

    // Board Functions
    function openNewBoardModal() {
        document.getElementById('newBoardModal').showModal();
    }

    function closeNewBoardModal() {
        document.getElementById('newBoardModal').close();
        document.getElementById('newBoardForm').reset();
    }

    function createBoard(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);

        fetch('{{ route("planning.boards.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            window.location.reload();
        })
        .catch(error => console.error('Error:', error));
    }

    function editBoard(boardId) {
        fetch(`/planning/boards/${boardId}`)
            .then(response => response.json())
            .then(board => {
                document.getElementById('editBoardId').value = board.id;
                document.getElementById('editBoardTitle').value = board.title;
                document.getElementById('editBoardDescription').value = board.description;
                document.getElementById('editBoardModal').showModal();
            });
    }

    function closeEditBoardModal() {
        document.getElementById('editBoardModal').close();
        document.getElementById('editBoardForm').reset();
    }

    function updateBoard(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);
        const boardId = document.getElementById('editBoardId').value;

        fetch(`/planning/boards/${boardId}`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            window.location.reload();
        })
        .catch(error => console.error('Error:', error));
    }

    function deleteBoard(boardId) {
        if (confirm('Are you sure you want to delete this board?')) {
            fetch(`/planning/boards/${boardId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            })
            .then(response => response.json())
            .then(data => {
                window.location.reload();
            })
            .catch(error => console.error('Error:', error));
        }
    }

    // Card Functions
    function openNewCardModal(boardId, status) {
        document.getElementById('newCardBoardId').value = boardId;
        document.getElementById('newCardStatus').value = status;
        document.getElementById('newCardModal').showModal();
    }

    function closeNewCardModal() {
        document.getElementById('newCardModal').close();
        document.getElementById('newCardForm').reset();
    }

    function createCard(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);

        fetch('{{ route("planning.cards.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            window.location.reload();
        })
        .catch(error => console.error('Error:', error));
    }

    function editCard(cardId) {
        fetch(`/planning/cards/${cardId}`)
            .then(response => response.json())
            .then(card => {
                document.getElementById('editCardId').value = card.id;
                document.getElementById('editCardTitle').value = card.title;
                document.getElementById('editCardDescription').value = card.description;
                document.getElementById('editCardPriority').value = card.priority;
                document.getElementById('editCardModal').showModal();
            });
    }

    function closeEditCardModal() {
        document.getElementById('editCardModal').close();
        document.getElementById('editCardForm').reset();
    }

    function updateCard(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);
        const cardId = document.getElementById('editCardId').value;

        fetch(`/planning/cards/${cardId}`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            window.location.reload();
        })
        .catch(error => console.error('Error:', error));
    }

    function deleteCard(cardId) {
        if (confirm('Are you sure you want to delete this card?')) {
            fetch(`/planning/cards/${cardId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            })
            .then(response => response.json())
            .then(data => {
                window.location.reload();
            })
            .catch(error => console.error('Error:', error));
        }
    }

    function updateCardPosition(cardId, boardId, newOrder, newStatus) {
        fetch(`{{ url('/planning/cards') }}/${cardId}/position`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                board_id: boardId,
                order: newOrder,
                status: newStatus
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Card position updated:', data);
        })
        .catch(error => {
            console.error('Error updating card position:', error);
            // Optionally refresh the page if the update fails
            // window.location.reload();
        });
    }
</script>
@endsection

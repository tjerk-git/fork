<div class="group relative rounded-lg border bg-card p-4 hover:shadow-sm" data-id="{{ $card->id }}">
    <div class="flex items-start justify-between">
        <h5 class="text-base font-medium leading-none">{{ $card->title }}</h5>
        <div class="relative">
            <button class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-input bg-background p-0 text-sm font-medium ring-offset-background transition-colors hover:bg-accent hover:text-accent-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2" 
                    type="button" 
                    onclick="toggleDropdown(event)">
                <i class="fas fa-ellipsis-v"></i>
            </button>
            <ul class="absolute right-0 top-full z-50 mt-1 hidden min-w-[8rem] overflow-hidden rounded-md border bg-popover p-1 text-popover-foreground shadow-md" 
                data-dropdown>
                <li>
                    <a href="#" 
                       onclick="editCard({{ $card->id }})" 
                       class="relative flex cursor-pointer select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none hover:bg-accent hover:text-accent-foreground">
                        Bewerken
                    </a>
                </li>
                <li>
                    <a href="#" 
                       onclick="deleteCard({{ $card->id }})" 
                       class="relative flex cursor-pointer select-none items-center rounded-sm px-2 py-1.5 text-sm text-destructive outline-none hover:bg-destructive hover:text-destructive-foreground">
                        Verwijderen
                    </a>
                </li>
            </ul>
        </div>
    </div>
    @if($card->description)
        <p class="mt-2 text-sm text-muted-foreground">{{ Str::limit($card->description, 100) }}</p>
    @endif
    @if($card->due_date)
        <div class="mt-3 flex items-center text-xs text-muted-foreground">
            <i class="far fa-clock mr-1"></i>
            <span data-date="{{ $card->due_date }}">
                {{ \Carbon\Carbon::parse($card->due_date)->format('d-m-Y') }}
            </span>
        </div>
    @endif
</div>

<script>
function toggleDropdown(event) {
    event.preventDefault();
    const button = event.currentTarget;
    const dropdown = button.nextElementSibling;
    const isVisible = !dropdown.classList.contains('hidden');
    
    // Hide all other dropdowns
    document.querySelectorAll('[data-dropdown]').forEach(d => {
        if (d !== dropdown) d.classList.add('hidden');
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('hidden');
    
    // Close dropdown when clicking outside
    if (!isVisible) {
        const closeDropdown = (e) => {
            if (!dropdown.contains(e.target) && !button.contains(e.target)) {
                dropdown.classList.add('hidden');
                document.removeEventListener('click', closeDropdown);
            }
        };
        setTimeout(() => {
            document.addEventListener('click', closeDropdown);
        }, 0);
    }
}
</script>

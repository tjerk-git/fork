@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <h1 class="text-3xl font-bold tracking-tight">Gebruikers</h1>
            <p class="text-sm text-muted-foreground">Beheer gebruikers en hun toegangsrechten</p>
        </div>
        <a href="{{ route('users.create') }}" 
           class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2">
            <i class="fas fa-plus mr-2"></i> Nieuwe gebruiker
        </a>
    </div>

    @if (session('success'))
        <div class="rounded-md bg-green-50 p-4 text-sm text-green-600">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="ml-3">
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="rounded-md border">
        <div class="relative w-full overflow-auto">
            <table class="w-full caption-bottom text-sm">
                <thead>
                    <tr class="border-b bg-muted/50 transition-colors">
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Email</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Acties</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="border-b transition-colors hover:bg-muted/50">
                            <td class="p-4 align-middle">
                                <div class="flex items-center">
                                    <span class="font-medium">{{ $user->email }}</span>
                                </div>
                            </td>
                            <td class="p-4 align-middle">
                                <div class="flex items-center gap-4">
                                    <a href="{{ route('users.edit', $user) }}" 
                                       class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 px-3">
                                        <i class="fas fa-edit mr-2"></i> Bewerk
                                    </a>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?')"
                                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-destructive text-destructive-foreground hover:bg-destructive/90 h-8 px-3">
                                            <i class="fas fa-trash mr-2"></i> Verwijder
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    @if ($users->isEmpty())
                        <tr>
                            <td colspan="2" class="p-4 text-center text-muted-foreground">
                                Geen gebruikers gevonden
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

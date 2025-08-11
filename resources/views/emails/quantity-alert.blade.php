<x-mail::message>
    <strong> # Quantity Level Alert </strong>

    The following Quantities are running low:

    <x-mail::table>
        {{-- | Item | Stock | Level |
        |:-------------:|:-------------:|:--------:|:-------------:|
        @foreach ($items as $item)
            | {{ $ingredient->name }} | {{ $ingredient->stock }} | {{ $ingredient->level }} |
            {{ number_format(($ingredient->level / $ingredient->stock) * 100, 2) }}% |
        @endforeach --}}
    </x-mail::table>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>

<x-mail::message>
    <strong> # Quantity Alert </strong>

    The following Quantities are running low:

    <x-mail::table>
        | Item | Stock | Quantity |
        |:-------------:|:-------------:|:--------:|:-------------:|
        @foreach ($items as $item)
            | {{ $item->name }} | {{ $item->stock }} | {{ $item->level }}
        @endforeach
    </x-mail::table>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>

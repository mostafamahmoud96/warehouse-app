<x-mail::message>
    <strong> # Quantity Alert </strong>

    The following Quantities are running low:

    <x-mail::table>
        | Item | Quantity |
        |:-------------:|:-------------:|:--------:|:-------------:|
        @foreach ($alertedQuantities as $item)
            | {{ $item->name }} | {{ $item->pivot->quantity }} |
        @endforeach
    </x-mail::table>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>

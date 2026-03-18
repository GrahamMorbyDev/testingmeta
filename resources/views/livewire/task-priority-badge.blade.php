@php
    $classes = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full ';
    if ($priority === 'high') {
        $classes .= 'bg-red-100 text-red-800';
    } elseif ($priority === 'medium') {
        $classes .= 'bg-yellow-100 text-yellow-800';
    } else {
        $classes .= 'bg-green-100 text-green-800';
    }
@endphp

<span class="{{ $classes }}">{{ ucfirst($priority) }}</span>

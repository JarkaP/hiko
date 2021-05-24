@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-md shadow-sm border-gray-300 focus:border-primary-light focus:ring focus:ring-primary-light focus:ring-opacity-50']) !!}>

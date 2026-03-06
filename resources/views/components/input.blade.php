<?php

// resources/views/components/input.blade.php
?>

@props([
    'type' => 'text',
    'name',
    'label' => null,
    'placeholder' => '',
    'required' => false,
])

<div class="flex flex-col gap-2">

    @if($label)
        <label for="{{ $name }}"
            class="text-sm font-medium text-slate-700 dark:text-slate-300">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <input
        id="{{ $name }}"
        type="{{ $type }}"
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        class="w-full rounded-xl
               border border-slate-300 dark:border-white/10
               bg-white dark:bg-white/5
               text-slate-900 dark:text-white
               placeholder:text-slate-400 dark:placeholder:text-slate-500
               p-2
               shadow-sm
               hover:border-primary/50
               focus:border-primary focus:ring-2 focus:ring-primary/40
               outline-none transition-all"
    />

</div>

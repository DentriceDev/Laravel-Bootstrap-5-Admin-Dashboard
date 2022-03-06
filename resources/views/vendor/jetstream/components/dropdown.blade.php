@props(['id'])

<li class="nav-item dropdown">
    <a id="{{ $id }}" {!! $attributes->merge(['class' => 'nav-link']) !!} role="button" data-bs-toggle="dropdown" aria-expanded="false">
        {{ $trigger }}
    </a>

    <ul class="dropdown-menu dropdown-menu-end animate slideIn" aria-labelledby="{{ $id }}">
        {{ $content }}
    </ul>
</li>
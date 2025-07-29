{{-- @php
  $content ??= '';
  $bg = 'gray-700';
  $color = 'white';
@endphp --}}

<div {{ $attributes->merge(['class' => 'group relative top-0 cursor-pointer']) }}>
  <div
    class="absolute bottom-[calc(100%+0.5rem)] top-full shadow-lg left-[50%] -translate-x-[50%] hidden group-hover:block w-auto transition-all duration-300 ease-in-out">
    <div
      class="bottom-full rounded bg-{{ $bg }} px-3 py-1 text-base text-{{ $color }} whitespace-nowrap">
      {{ $content }}
      <svg class="absolute left-0 top-full h-2 w-full text-{{ $bg }}" x="0px" y="0px" viewBox="0 0 255 255"
        xml:space="preserve">
        <polygon class="fill-current" points="0,0 127.5,127.5 255,0" />
      </svg>
    </div>
  </div>
  {{ $slot }}
</div>

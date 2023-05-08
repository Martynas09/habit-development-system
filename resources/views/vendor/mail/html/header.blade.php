@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/storage/logo.png'))) }}" class="logo" alt="Laravel Logo" class="w-20 h-20 fill-current text-gray-500">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>

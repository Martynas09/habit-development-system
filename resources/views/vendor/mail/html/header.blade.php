@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://habitdevsys.me/storage/logo.png" class="logo" alt="Logo" width="80" height="80">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>

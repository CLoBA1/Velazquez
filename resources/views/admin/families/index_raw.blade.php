<h1>Familias RAW</h1>

<p>Total: {{ $families->total() }}</p>

<ul>
@foreach($families as $f)
    <li>{{ $f->name }} ({{ $f->code }})</li>
@endforeach
</ul>
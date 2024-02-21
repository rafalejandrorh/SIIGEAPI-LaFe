<thead>
    <tr role="row">
        @if(!$userIgnore)
            <th>Usuario</th>
        @endif
        <th>Acción</th>
        <th>Fecha</th>
        <th>Valores Modificados</th>
        <th>Acciones</th>
    </tr>
</thead>
<tbody>
    @foreach ($data as $value)
    <tr role="row" class="odd">
        @if (!$userIgnore)
            <td class="sorting_1">{{$value->user->users}}</td>
        @endif
        <td class="sorting_1">{{$value->acciones->valor}}</td>
        <td class="sorting_1">{{ date('d/m/Y H:i:s', strtotime($value->created_at)) }}</td>
        <td class="sorting_1">{{ $value->valores_modificados }}</td>
        <td>
            @can('trazas.show')
                <a class="btn btn-info" Title="Mostrar" href="{{ route($route, $value->id) }}"><i class='fa fa-eye'></i></a>
            @endcan
        </td>
    </tr>
    @endforeach
</tbody>
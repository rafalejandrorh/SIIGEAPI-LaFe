<thead>
    <tr role="row">
        <th>Usuario</th>
        <th>Acción</th>
        <th>Fecha</th>
        <th>Acciones</th>
    </tr>
</thead>
<tbody>
    @foreach ($data as $value)
    <tr role="row" class="odd">
        <td class="sorting_1">{{$value->user->users}}</td>
        <td class="sorting_1">{{$value->acciones->valor}}</td>
        <td class="sorting_1">{{ date('d/m/Y H:i:s', strtotime($value->created_at)) }}</td>
        <td>
            @can('trazas.show')
                <a class="btn btn-info" Title="Mostrar" href="{{ route($route, $value->id) }}"><i class='fa fa-eye'></i></a>
            @endcan
        </td>
    </tr>
    @endforeach
</tbody>
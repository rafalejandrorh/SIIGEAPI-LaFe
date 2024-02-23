@extends('layouts.app')
@extends('tokens.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading"><b>Tokens</b></h3>
        </div>

            <div class="row">
                <div class="col-lg-12" style="position:relative;z-index:1000">
                    <div class="card card-primary">
                        <div class="card-body">
                                {!! Form::open(array('route' => 'tokens.index','method' => 'GET')) !!}
                                <div class="row">
                                    <div class="col-xs-4 col-sm-4 col-md-4">
                                        <div class="form-group">
                                            {!! Form::select('tipo_busqueda', ['' => 'Ver todos',
                                            'dependencia' => 'empresas'], 
                                            'Seleccionar', array('class' => 'form-control select2')) !!}
                                        </div>
                                    </div>
                                    <div class="col-xs-3 col-sm-3 col-md-3">
                                        <div class="form-group">
                                            {!! Form::text('buscador', null, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                    <div class="col-xs-3 col-sm-3 col-md-3">
                                        {!! Form::button('<i class="fa fa-search"> Buscar</i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                                    </div>
                                </div>
                                {!! Form::close() !!}

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            {{-- @can('tokens.create') --}}
                                                <a class="btn btn-success" href="{{ route('tokens.create') }}"><i class="fa fa-plus"></i> Crear</a>                        
                                            {{-- @endcan --}}
                                        </div>
                                    </div>
                                        <table class="table table-striped mt-2 display dataTable table-hover">
                                            <thead>
                                                <tr role="row">
                                                    <th>Empresa</th>
                                                    <th>Fecha de Generación</th>
                                                    <th>Fecha de Expiración</th>
                                                    <th>Último vez usado</th>
                                                    <th>Estatus</th>
                                                    <th>Acciones</th>
                                                </tr>    
                                            </thead>
                                                @foreach ($tokens as $token)
                                                <tr role="row" class="odd">
                                                    <td class="sorting_1">{{ $token->empresas->nombre }}</td>
                                                    <td class="sorting_1">{{ date('d/m/Y H:i:s', strtotime($token->created_at)) }}</td>
                                                    
                                                    @if ($token->expired_at >= date('Y-m-d H:i:s'))
                                                        <td class="sorting_1">{{ date('d/m/Y H:i:s', strtotime($token->expired_at)) }}</td>
                                                    @else 
                                                        <td>{{ date('d/m/Y H:i:s', strtotime($token->expired_at)) }} <span class="badge badge-pill badge-danger">Expirado</span></td>
                                                    @endif
                                                    
                                                    <td class="sorting_1" align="center">{{ $token->last_used_at ? date('d/m/Y H:i:s', strtotime($token->last_used_at)) : 'No Utilizado' }}</td>

                                                    {{-- @can('tokens.update_status') --}}
                                                        <td class="sorting_1">
                                                            {!! Form::model($token, ['method' => 'PATCH','route' => ['tokens.update_status', $token->id]]) !!} 
                                                                {!! Form::button($token->estatus ? 'Activo' : 'Inactivo', ['type' => 'submit', 'class' => $token->estatus ? 'btn btn-info' : 'btn btn-danger']) !!}
                                                            {!! Form::close() !!} 
                                                    {{-- @elsecan('tokens.index') --}}
                                                        {{-- <td class="sorting_1">
                                                            <span class="badge badge-pill badge-{{$token->estatus ? 'info' : 'danger'}}">{{ $token->estatus ? 'Activo' : 'Inactivo' }}</span>
                                                        </td> --}}
                                                    {{-- @endcan --}}

                                                    <td align="center">
                                                        {{-- @can('tokens.show') --}}
                                                            <a class="btn btn-info" href="{{ route('tokens.show', $token->id) }}"><i class='fa fa-eye'></i></a>
                                                        {{-- @endcan --}}
                                                        {{-- @can('tokens.edit') --}}
                                                            @if ($token->expired_at <= date('Y-m-d H:i:s'))
                                                                <a class="btn btn-primary" href="{{ route('tokens.edit', $token->id) }}"><i class='fa fa-edit'></i></a> 
                                                            @endif
                                                        {{-- @endcan --}}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    <div class="pagination justify-content-end">  
                                        {{ $tokens->appends(request()->input())->links() }}          
                                    </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 

    </section>
@endsection

@section('scripts')

    @if (session('eliminar') == 'Ok')
        <script>
            Swal.fire(
                'Eliminado!',
                'La Reseña ha sido Eliminada.',
                'success'
            )
        </script>
    @endif

    <script>

        $('.eliminar').submit(function(e){
            e.preventDefault();

            Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Eliminar!'
            }).then((result) => {
            if (result.value) {
                this.submit();
            }
            })
        });

    </script>

@endsection
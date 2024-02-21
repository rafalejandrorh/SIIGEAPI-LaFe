@extends('layouts.app')
@extends('roles.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading text-primary"><b>Roles</b></h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-body">
        
                            <div class="row">
                                <div class="col-xs-11 col-sm-11 col-md-11">
                                    <div class="form-group">
                                        {{-- @can('roles.create') --}}
                                        <a class="btn btn-success" href="{{ route('roles.create') }}"><i class="fas fa-plus"></i> Crear</a>                        
                                        {{-- @endcan --}}
                                    </div>
                                </div>
                                <div class="col-xs-1 col-sm-1 col-md-1 text-right">
                                    <div class="form-group">
                                        @can('roles.excel')
                                        <a class="btn btn-success" href="{{ route('roles.export.excel') }}"><i class="fas fa-file-excel"></i></a>                        
                                        @endcan
                                    </div>
                                </div>
                            </div>
                            
                            <table class="table table-striped mt-2 display dataTable table-hover">
                                <thead>                                         
                                    <th>Rol</th>
                                    <th>Acciones</th>
                                </thead>  
                                <tbody>
                                @foreach ($roles as $role)
                                <tr>                           
                                    <td>{{ $role->name }}</td>
                                    <td>                                
                                        @can('roles.edit')
                                            <a class="btn btn-primary" Title="Editar" href="{{ route('roles.edit', $role->id) }}"><i class='fa fa-edit'></i></a>
                                        @endcan
                                        
                                        @can('roles.destroy')
                                            {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline', 'class' => 'eliminar']) !!}
                                                {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'Title' => 'Eliminar']) !!}
                                            {!! Form::close() !!}
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>               
                            </table>

                            <!-- Centramos la paginacion a la derecha -->
                            <div class="pagination justify-content-end">
                                {{ $roles->appends(request()->input())->links() }} 
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
                'El Rol ha sido Eliminado.',
                'success'
            )
        </script>
    @endif

    <script src="{{ asset('public/js/roles/index.js')}}"></script>
@endsection

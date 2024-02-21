@extends('layouts.app')
@extends('configuraciones.partials.header')
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading text-primary"><b>Configuraciones - APP Móvil</b></h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-xs-2 col-sm-2 col-md-2">
                                    <a href="{{ route('configuraciones.index') }}" class="btn btn-danger"><i class="fa fa-reply"></i> Regresar</a>
                                </div>
                            </div>
                            <br>

                                <div class="col-xs-2 col-sm-2 col-md-2">
                                    <div class="form-group">
                                        @can('backup.create')
                                            @if (count($apps) == null)                       
                                                <a href="#!" class="btn btn-success" data-toggle="modal" data-target="#cargarApk"><i class="fa fa-plus"></i> Cargar APK</a>
                                            @endif
                                        @endcan
                                    </div>
                                </div>
                                <table class="table table-striped mt-2 display dataTable table-hover">
                                    <thead>
                                        <tr role="row">
                                            <th>Ruta</th>
                                            <th>Acciones</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($apps as $app)
                                            <tr role="row" class="odd">
                                                <td class="sorting_1">{{$app['path']}}</td>
                                                <td>
                                                    @can('backup.destroy')
                                                        {!! Form::open(['method' => 'DELETE', 'route' => [
                                                                'apk.destroy', 
                                                                $app['oneDirectory'], 
                                                                $app['filename']
                                                            ], 'style'=>'display:inline', 'class' => 'eliminar']) 
                                                        !!}
                                                            {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger']) !!}
                                                        {!! Form::close() !!}  
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('configuraciones.modals.apk')

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
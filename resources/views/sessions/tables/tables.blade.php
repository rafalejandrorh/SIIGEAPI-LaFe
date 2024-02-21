<div class="card card-primary">
    <div class="card-header">
        <h5 class="text-primary"><i class="fa fa-history"></i> Historial de Acciones</h5>
    </div>
    <div class="card-body">
        <div class="card-body box-profile">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link  active show" id="resennas-tab" data-toggle="tab" href="#resennas" role="tab" aria-controls="resennas" aria-selected="true"><p class="text-red">Reseñas</p></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="funcionarios-tab" data-toggle="tab" href="#funcionarios" role="tab" aria-controls="funcionarios" aria-selected="false"><p class="text-red">Funcionarios</p></a>
                    </li>  
                    <li class="nav-item">
                        <a class="nav-link " id="usuarios-tab" data-toggle="tab" href="#usuarios" role="tab" aria-controls="usuarios" aria-selected="false"><p class="text-red">Usuarios</p></a>                          
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " id="roles-tab" data-toggle="tab" href="#roles" role="tab" aria-controls="roles" aria-selected="false"><p class="text-red">Roles</p></a>                          
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="historialSesion-tab" data-toggle="tab" href="#historialSesion" role="tab" aria-controls="historialSesion" aria-selected="false"><p class="text-red">Historial de Sesión</p></a>
                    </li>                  
                </ul>
                <div class="tab-content" id="myTabContent">

                    <div class="tab-pane fade active show" id="resennas" role="tabpanel" aria-labelledby="resennas-tab">
                        <div class="row justify-content-md-center">
                            <div class="col-sm-12">
                                <div class="card-block">
                                    <div class>
                                        <div id="zero-configuration_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <table id="tableresennas" class="table table-striped mt-2 display dataTable table-hover" role="grid" aria-describedby="zero-configuration_info">
                                                        @include('sessions.tables.tableTraza', ['data' => $data['resennas'], 'route' => 'traza_resenna.show', 'userIgnore' => $userIgnore])
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="usuarios" role="tabpanel" aria-labelledby="usuarios-tab">
                        <div class="row justify-content-md-center">
                            <div class="col-sm-12">
                                <div class="card-block">
                                    <div class>
                                        <div id="zero-configuration_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <table id="tableusuarios" class="table table-striped mt-2 display dataTable table-hover" role="grid" aria-describedby="zero-configuration_info">
                                                        @include('sessions.tables.tableTraza', ['data' => $data['users'], 'route' => 'traza_user.show', 'userIgnore' => $userIgnore])
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </div> 
                    
                    <div class="tab-pane fade" id="funcionarios" role="tabpanel" aria-labelledby="funcionarios-tab">
                        <div class="row justify-content-md-center">
                            <div class="col-sm-12">
                                <div class="card-block">
                                    <div class>
                                        <div id="zero-configuration_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <table id="tablefuncionarios" class="table table-striped mt-2 display dataTable table-hover" role="grid" aria-describedby="zero-configuration_info">
                                                        @include('sessions.tables.tableTraza', ['data' => $data['funcionarios'], 'route' => 'traza_funcionarios.show', 'userIgnore' => $userIgnore])
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </div> 
                    
                    <div class="tab-pane fade" id="roles" role="tabpanel" aria-labelledby="roles-tab">
                        <div class="row justify-content-md-center">
                            <div class="col-sm-12">
                                <div class="card-block">
                                    <div class>
                                        <div id="zero-configuration_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <table id="tableroles" class="table table-striped mt-2 display dataTable table-hover" role="grid" aria-describedby="zero-configuration_info">
                                                        @include('sessions.tables.tableTraza', ['data' => $data['roles'], 'route' => 'traza_roles.show', 'userIgnore' => $userIgnore])
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </div> 
                    
                    <div class="tab-pane fade" id="historialSesion" role="tabpanel" aria-labelledby="historialSesion-tab">
                        <div class="row justify-content-md-center">
                            <div class="col-sm-12">
                                <div class="card-block">
                                    <div class>
                                        <div id="zero-configuration_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <table id="tablehistorialSesion" class="table table-striped mt-2 display dataTable table-hover" role="grid" aria-describedby="zero-configuration_info">
                                                        <thead>
                                                            <tr role="row">
                                                                <th>Usuario</th>
                                                                <th>Funcionario Asignado</th>
                                                                <th>Inicio de Sesión</th>
                                                                <th>Cierre de Sesión</th>
                                                                <th>Tipo de Cierre</th>
                                                                <th>MAC</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($data['historialSesion'] as $historial)
                                                            <tr role="row" class="odd">
                                                                <td class="sorting_1">{{$historial->user->users}}</td>
                                                                <td class="sorting_1">{{$historial->user->funcionario->person->primer_nombre.' '.$historial->user->funcionario->person->primer_apellido}}</td>
                                                                <td class="sorting_1">{{ date('d/m/Y H:i:s', strtotime($historial->login)) }}</td>
                                                                @if ($historial->logout)
                                                                    <td class="sorting_1">{{ date('d/m/Y H:i:s', strtotime($historial->logout)) }}</td>
                                                                @else
                                                                    <td class="sorting_1"> - </td>
                                                                @endif
                                                                
                                                                @if ($historial->tipo_logout == 1)
                                                                    <td class="sorting_1">Finalizada por el Usuario</td>
                                                                @elseif ($historial->tipo_logout == 2)
                                                                    <td class="sorting_1">Finalizada por el Sistema</td>
                                                                @elseif ($historial->tipo_logout == 3)
                                                                    <td class="sorting_1">Finalizada por un Tercero</td>
                                                                @else
                                                                    <td class="sorting_1">Sin Finalizar</td>
                                                                @endif
                                                                <td class="sorting_1">{{$historial->MAC}}</td>
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
                    </div> 


                </div>
            </div>
        </div>
    </div>
</div>
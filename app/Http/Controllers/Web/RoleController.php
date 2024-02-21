<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
Use Alert;
use App\Events\TrazasEvent;
use App\Exports\RolesExport;
use App\Http\Constants;
use App\Traits\RolesTrait;
use Maatwebsite\Excel\Facades\Excel;

class RoleController extends Controller
{
    use RolesTrait;

    private $role;
    private $permission;

    function __construct(Role $role, Permission $permission)
    {
        // $this->middleware('can:roles.index')->only('index');
        // $this->middleware('can:roles.create')->only('create');
        // $this->middleware('can:roles.show')->only('show');
        // $this->middleware('can:roles.edit')->only('edit', 'update');
        // $this->middleware('can:roles.destroy')->only('destroy');
        // $this->middleware('can:roles.excel')->only('exportExcel');
        $this->role = $role;
        $this->permission = $permission;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $id_user = Auth::user()->id;
        $id_Accion = Constants::INGRESO_MODULO; 
        $valores_modificados = 'N/A';
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Roles'));   
         
        $roles = $this->role->paginate(10);
        $permission = $this->permission->get();
        return view('roles.index',compact('roles', 'permission'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = $this->permission->get();
        return view('roles.create',compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);
    
        $role = $this->role->create(['name' => $request->input('name')]);
        $role->syncPermissions($request['permission']);
        $roles = $this->splitArrayWithComma($request['permission']);

        $id_user = Auth::user()->id;
        $id_Accion = Constants::REGISTRO; 
        $valores_modificados = 'Rol: '.$request['name'].' || Permisos: '.$roles;
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Roles'));

        Alert()->success('Rol Creado Satisfactoriamente','Ahora puedes asignar el siguiente rol: '.$request->input('name'));
        return redirect()->route('roles.index');                        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = $this->role->find($id);
        $permission = $this->permission->get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
        ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
        ->all();
    
        return view('roles.edit',compact('role','permission','rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);
    
        $role = $this->role->find($id);
        $role->name = $request->input('name');
        $role->save();
        $role->syncPermissions($request->input('permission'));
        $roles = $this->splitArrayWithComma($request['permission']);

        $id_user = Auth::user()->id;
        $id_Accion = Constants::ACTUALIZACION; 
        $valores_modificados = 'Rol: '.$request['name'].' || Permisos: '.$roles;
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Roles'));

        Alert()->success('Rol de '.$request->input('name'),  'Actualizado Satisfactoriamente.');
        return redirect()->route('roles.index');                        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $permisos = DB::table("role_has_permissions")->where('role_id',$role->id)
        ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
        ->pluck('permission_id')->all();
        $roles = $this->splitArrayWithComma($permisos);

        $id_user = Auth::user()->id;
        $id_Accion = Constants::ELIMINACION; 
        $valores_modificados = 'Rol: '.$role->name.' || Permisos: '.$roles;
        event(new TrazasEvent($id_user, $id_Accion, $valores_modificados, 'Traza_Roles'));

        $role->delete();

        return redirect()->route('roles.index')->with('eliminar', 'Ok');                        
    }

    public function exportExcel()
    {
        return Excel::download(new RolesExport, 'roles_'.date('Ymd-his').'.xlsx');
    }
}

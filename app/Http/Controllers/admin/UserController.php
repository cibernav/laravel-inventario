<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use File;
use App\User;
class UserController extends Controller
{

    public function __construct(){
        $this->middleware(['roles:Administrador']);
    }

    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::all();
        $roles = Role::all();

        return view('admin.user.index')->with(compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->input(), User::$rules);
        if($validator->fails()){
            return response()->json([
                'error' => true,
                'messages' => $validator->errors()
            ], 422);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->estado = 1;

        $photo = $request->file('photo');
        if($photo){
            $namefile = 'user/' .time() . $photo->getClientOriginalName();
            $result = Storage::disk('images')->put($namefile, File::get($photo));
            $user->foto = Storage::disk('images')->url($namefile);
        }
        $user->save();
        $user->assignRole($request->role);

        //return redirect()->action('Admin\UserController@index');

        return response()->json([
            'error' => false,
            'redirect'  => route('admin.user.index'),
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $user->foto = $user->photo;
        $user->role = $user->getRoleNames()->first();

        $this->authorize('edit',$user);
        return response()->json(['data'=>$user], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,int $id)
    {
        //

        $validator = Validator::make($request->all(), User::rulesUpdate($id));
        if($validator->fails()){
            return response()->json([
                'error' => true,
                'messages' => $validator->errors()
            ], 422);
        }

        if ($id === 1){
            return response()->json([
                'error' => true,
                'message'  => 'El usuario administrador no se puede editar.',
            ], 200);
        }


        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if($request->password)
            $user->password = bcrypt($request->password);
        //$user->estado = 1;

        //dd($user->getRoleNames()->toArray());
        if(!$user->hasRole($request->role)){
            $roles = $user->getRoleNames();
            if ($roles->count() > 0)
                $user->removeRole($roles[0]);
            $user->assignRole($request->role);
        }

        $photo = $request->file('photo');
        if($photo){
            //delete photo fisico

            if($user->foto){
                $photoPath = str_replace('storage', 'public', $user->foto);
                Storage::delete($photoPath);
            }

            //forma 1
            //$photo = request()->file('photo')->store('public');
            //$photo = Storage::url($photo);

            //forma 2
            $namefile = 'user/'. time() . $photo->getClientOriginalName();
            $result = Storage::disk('images')->put($namefile, File::get($photo));
            $user->foto = Storage::disk('images')->url($namefile);
        }

        $user->save();
        /*
        \Mail::send('emails.contact', ['msj'=>$message], function($m) use($message){
            $m->to($message->email, $message->name)->subject('Tu mensaje fue recibido');
        });
        */

        return response()->json([
            'error' => false,
            'redirect'  => '',
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        //

        if ($id === 1){
            return response()->json([
                'error' => true,
                'message'  => 'El usuario administrador no se puede eliminar.',
            ], 200);
        }

        $user = User::find($id);

        $this->authorize('destroy', $user);

        $user->delete();

        $roles = $user->getRoleNames();

        foreach ($roles as $key => $value) {
            $user->removeRole($value);
        }
        $photoPath = str_replace('storage', 'public', $user->foto);
        Storage::delete($photoPath);

        return response()->json([
            'error' => false,
            'message' => 'El usuario se elimino con exito',
            'redirect'  => '',
        ], 200);

    }

    public function active(Request $request, $id){

        $user = User::find($id);
        $user->estado = !($request->active);

        $user->save();


        return response()->json([
            'error' => false,
            'message' => $user->estado ? 1 : 0
        ], 200);
    }
}

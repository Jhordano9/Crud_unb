<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Users extends Component
{
    public $users, $nombre, $apellido, $telefono, $email, $password,  $id;
    public $isOpen = 0;
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function render()
    {
        
        $this->users = User::all();
        return view('livewire.users');
    }
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function openModal()
    {
        $this->isOpen = true;
    }
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetErrorBag();
        $this->resetValidation();
    }
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    private function resetInputFields(){
        $this->nombre = '';
        $this->apellido = '';
        $this->telefono = '';
        $this->email = '';
        $this->password = '';
        $this->id = '';
    }
     
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function store()
    {
        $validate = [
            'nombre' => 'required',
            'apellido' => 'required',
            'telefono' => 'required',
            'email' => 'required'
        ];
        
        if(!isset($this->id) || $this->id == ''){
            $validate['password'] = 'required';
        }
        
        $this->validate($validate);
        
        $arr = [
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'telefono' => $this->telefono,
            'email' => $this->email,
        ];
        
        if($this->id && $this->password != ''){
            $pass = Hash::make($this->password);
            $arr['password'] = $pass;
        }
   
        User::updateOrCreate(['id' => $this->id], $arr);
  
        session()->flash('message', 
            $this->id ? 'Usuario editado correctamente.' : 'Usuario creado correctamente!');
  
        $this->closeModal();
        $this->resetInputFields();
    }
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->id = $id;
        $this->nombre = $user->nombre;
        $this->apellido = $user->apellido;
        $this->telefono = $user->telefono;
        $this->email = $user->email;
        $this->password = '';
        
        
        $this->openModal();
    }
     
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function delete($id)
    {
        User::find($id)->delete();
        session()->flash('message', 'Post Deleted Successfully.');
    }
}

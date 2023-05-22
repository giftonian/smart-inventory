<?php

namespace App\Http\Livewire\Category;

use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    public User $user;
    protected $rules = [
        'user.name' => 'max:40|min:3',
        'user.email' => 'email:rfc,dns',
        'user.phone' => 'max:10',
        'user.about' => 'max:200',
        'user.location' => 'min:3'
    ];

    public function render()
    {
        return view('livewire.category.listing');
    }

    public function mount() // temp code
    {
        $this->user = auth()->user();
    }

    public function createForm()
    {        
        $this->user = auth()->user();
        $data = [
            'user' => $this->user            
        ];

        return view('livewire.category.add-category', ['user' => $this->user]);
        //->with($data);
    }

    public function save()
    {
        $this->validate();

        if (env('IS_DEMO') && auth()->user()->id == 1) {
            if (auth()->user()->email == $this->user->email) {
                $this->user->save();
                return back()->with('status', "Your profile information have been successfuly saved!");
            }

            return back()->with('demo', "You are in a demo version. You are not allowed to change the email for default users.");
        }

        $this->user->save();

        return back()->with('status', "Your profile information have been successfully saved!");
    }

    
}

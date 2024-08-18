<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UpdateSocialLinks extends Component
{
    public $links = [
        'instagram' => '',
        'tiktok' => '',
        'facebook' => '',
    ];

    public function updateSocialLinks()
    {
        $this->resetErrorBag();

        User::findOrFail(Auth::user()->id)->update([
            'links' => json_encode($this->links),
        ]);

        $this->dispatch('saved');

    }
    public function render()
    {
        return view('livewire.profile.update-social-links');
    }
}

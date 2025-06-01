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

    public function mount()
    {
        $user = auth()->user();

        $this->links = json_decode($user->links ?? '{}', true) ?: [
            'instagram' => '',
            'tiktok' => '',
            'facebook' => '',
        ];
    }

    public function updateSocialLinks()
    {
        $this->resetErrorBag();

        // Trim all URLs before saving
        foreach ($this->links as $key => $url) {
            $this->links[$key] = preg_replace('#^https?://(www\.)?#', '', $url);
        }

        User::findOrFail(auth()->id())->update([
            'links' => json_encode($this->links),
        ]);

        $this->dispatch('saved');
    }

    public function render()
    {
        return view('livewire.profile.update-social-links');
    }
}

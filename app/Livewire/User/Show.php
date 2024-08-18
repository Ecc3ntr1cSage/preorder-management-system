<?php

namespace App\Livewire\User;

use App\Models\Campaign;
use App\Models\Question;
use App\Models\Visitor;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    public Campaign $campaign;

    #[Rule('required', message: 'You can\'t submit nothing.')]
    public $question;

    public $quantity = 1;

    #[Rule('required', message: 'Please select a variant.')]
    public $selectedVariations = [];

    public function mount()
    {
        session()->forget('preorder');
        // Track page visit via session
        $sessionId = Session::getId();
        $visitor = Visitor::where('session_id', $sessionId)
            ->where('campaign_id', $this->campaign->id)
            ->first();

        if (!$visitor) {
            Visitor::create([
                'session_id' => $sessionId,
                'campaign_id' => $this->campaign->id,
            ]);
        }
    }

    public function enquiry()
    {
        $this->validate([
            'question' => 'required',
        ]);

        Question::create([
            'campaign_id' => $this->campaign->id,
            'question' => $this->question,
        ]);

        $this->campaign = Campaign::find($this->campaign->id);
        $this->dispatch('success', message: 'Question Posted');
        $this->reset('question');
        return redirect()->back();
    }

    public function hasVariations($variations)
    {
        $variations = json_decode($variations, true);
        foreach($variations as $variation)
        {
            if (!empty($variation['name']) || !empty($variation['values'])) {
                return true; // At least one variation contains values
            }
        }
        return false;
    }

    public function preorder()
    {
        session()->forget('preorder');

        if($this->hasVariations($this->campaign->variations))
        {
            $this->validate([
                'selectedVariations' => 'required',
            ]);
        }
       
        $variation = implode(', ', array_values($this->selectedVariations));
        session([
            'preorder' => [
                'campaign_id' => $this->campaign->id,
                'quantity' => $this->quantity,
                'variations' => $variation,
            ],
        ]);

        return $this->redirect('payment', navigate: true);
    }

    #[Layout('layouts.guest')]
    public function render()
    {
        return view('livewire.user.show');
    }
}

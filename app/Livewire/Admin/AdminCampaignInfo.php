<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use App\Models\Question;
use Livewire\Component;

class AdminCampaignInfo extends Component
{
    public Campaign $campaign;

    public $deleteQuestionModal = false;

    public function deleteQuestion($question_id)
    {
        Question::findOrFail($question_id)->delete();
        $this->dispatch('success', message: 'Question Deleted');
        $this->deleteQuestionModal = false;
    }

    public function render()
    {
        return view('livewire.admin.admin-campaign-info');
    }
}

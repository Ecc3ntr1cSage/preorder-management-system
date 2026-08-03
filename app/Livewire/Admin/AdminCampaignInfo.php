<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use App\Models\Question;
use Livewire\Component;

class AdminCampaignInfo extends Component
{
    public Campaign $campaign;

    public $deleteQuestionModal = false;
    public ?int $selectedQuestionId = null;
    public string $selectedQuestionText = '';

    public function mount(Campaign $campaign): void
    {
        $this->campaign = $campaign->load(['user', 'images', 'questions.reply', 'coupon']);
    }

    public function confirmDeleteQuestion(int $questionId): void
    {
        $question = $this->campaign->questions->firstWhere('id', $questionId);

        if (! $question) {
            return;
        }

        $this->selectedQuestionId = $question->id;
        $this->selectedQuestionText = $question->question;
        $this->deleteQuestionModal = true;
    }

    public function deleteQuestion(): void
    {
        if (! $this->selectedQuestionId) {
            return;
        }

        Question::query()
            ->where('campaign_id', $this->campaign->id)
            ->findOrFail($this->selectedQuestionId)
            ->delete();

        $this->campaign->load('questions.reply');
        $this->dispatch('success', message: 'Question deleted');
        $this->deleteQuestionModal = false;
        $this->selectedQuestionId = null;
        $this->selectedQuestionText = '';
    }

    public function render()
    {
        return view('livewire.admin.admin-campaign-info');
    }
}

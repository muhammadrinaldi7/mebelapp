<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

#[Layout('layouts.app')]
class ActivityLogIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $type = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Activity::with('causer', 'subject')
            ->latest();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                    ->orWhereHas('causer', function ($sq) {
                        $sq->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->type) {
            $query->where('subject_type', $this->type);
        }

        $logs = $query->paginate($this->perPage);

        return view('livewire.activity-log-index', [
            'logs' => $logs,
            'types' => Activity::select('subject_type')->distinct()->pluck('subject_type'),
        ]);
    }

    public function getLogDetails($id)
    {
        $logData = Activity::with('causer', 'subject')->find($id);
        // dd($logData);
        return $logData ? $logData->properties : null;
    }
}

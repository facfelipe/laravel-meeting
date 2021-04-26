<?php

namespace SimpleEducation\Meeting\Events;

use Illuminate\Queue\SerializesModels;
use SimpleEducation\Meeting\Models\Participant;

class ParticipationCanceled
{
    use SerializesModels;

    public Participant $participant;

    /**
     * Create a new event instance.
     *
     * @param \SimpleEducation\Meeting\Models\Participant $participant
     */
    public function __construct(Participant $participant)
    {
        $this->participant = $participant;
    }
}

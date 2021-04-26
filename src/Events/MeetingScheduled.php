<?php

namespace SimpleEducation\Meeting\Events;

use Illuminate\Queue\SerializesModels;
use SimpleEducation\Meeting\Models\Meeting;

class MeetingScheduled
{
    use SerializesModels;

    public Meeting $meeting;

    /**
     * Create a new event instance.
     *
     * @param \SimpleEducation\Meeting\Models\Meeting $meeting
     */
    public function __construct(Meeting $meeting)
    {
        $this->meeting = $meeting;
    }
}

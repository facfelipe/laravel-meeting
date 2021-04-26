<?php

namespace SimpleEducation\Meeting\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use SimpleEducation\Meeting\MeetingAdder;
use SimpleEducation\Meeting\Models\Meeting;
use SimpleEducation\Meeting\Models\Traits\VerifiesAvailability;

/**
 * Provides default implementation of Scheduler contract.
 */
trait SchedulesMeetings
{
    use VerifiesAvailability;

    /**
     * Get the MorphMany Relation with the Meeting Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function meetings(): MorphMany
    {
        return $this->morphMany(Meeting::class, 'scheduler')->with('presenter', 'host');
    }

    /**
     * Undocumented function
     *
     * @param string|null $provider
     * @return \SimpleEducation\Meeting\MeetingAdder
     */
    public function scheduleMeeting(?string $provider = null): MeetingAdder
    {
        return app(MeetingAdder::class)->withProvider($provider)->scheduledBy($this);
    }
}

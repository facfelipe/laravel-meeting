<?php


namespace SimpleEducation\Meeting\Providers;


use SimpleEducation\Meeting\Events\MeetingCanceled;
use SimpleEducation\Meeting\Events\MeetingUpdated;
use SimpleEducation\Meeting\Events\ParticipationCanceled;
use SimpleEducation\Meeting\Models\Meeting;
use SimpleEducation\Meeting\Models\Participant as ParticipantPivot;

class MeetProvider
{
    /**
     *
     * @param \SimpleEducation\Meeting\Models\Meeting $meeting
     * @return void
     */
    public function canceled(Meeting $meeting): void
    {
        event(new MeetingCanceled($meeting));
    }

    /**
     *
     * @param \SimpleEducation\Meeting\Models\Meeting $meeting
     * @return void
     */
    public function updated(Meeting $meeting): void
    {
        event(new MeetingUpdated($meeting));
    }

    /**
     *
     * @param \SimpleEducation\Meeting\Models\Meeting $meeting
     * @return void
     */
    public function starting(Meeting $meeting): void
    {
    }

    /**
     *
     * @param \SimpleEducation\Meeting\Models\Meeting $meeting
     * @return void
     */
    public function started(Meeting $meeting): void
    {
    }

    /**
     *
     * @param \SimpleEducation\Meeting\Models\Meeting $meeting
     * @return void
     */
    public function ending(Meeting $meeting): void
    {
    }

    /**
     *
     * @param \SimpleEducation\Meeting\Models\Meeting $meeting
     * @return void
     */
    public function ended(Meeting $meeting): void
    {
    }

    /**
     *
     * @param \SimpleEducation\Meeting\Models\Participant $participant
     * @return void
     */
    public function participationCanceled(ParticipantPivot $participant): void
    {
        event(new ParticipationCanceled($participant));
    }

    /**
     *
     * @param \SimpleEducation\Meeting\Models\Participant $participant
     * @return void
     */
    public function participantJoining(ParticipantPivot $participant): void
    {
    }

    /**
     *
     * @param \SimpleEducation\Meeting\Models\Participant $participant
     * @return void
     */
    public function participantJoined(ParticipantPivot $participant): void
    {
    }

    /**
     *
     * @param \SimpleEducation\Meeting\Models\Participant $participant
     * @return void
     */
    public function participantLeaving(ParticipantPivot $participant): void
    {
    }

    /**
     *
     * @param \SimpleEducation\Meeting\Models\Participant $participant
     * @return void
     */
    public function participantLeft(ParticipantPivot $participant): void
    {
    }
}

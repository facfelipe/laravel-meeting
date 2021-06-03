<?php


namespace SimpleEducation\Meeting\Providers\Google;


use SimpleEducation\Meeting\Contracts\Participant;
use SimpleEducation\Meeting\Contracts\Provider;
use SimpleEducation\Meeting\MeetingAdder;
use SimpleEducation\Meeting\Models\Meeting;
use SimpleEducation\Meeting\Models\Participant as ParticipantPivot;
use SimpleEducation\Meeting\Providers\MeetProvider;

class GoogleProvider extends MeetProvider implements Provider
{

    public function getFacadeAccessor(): string
    {
        // TODO: Implement getFacadeAccessor() method.
    }

    public function scheduling(MeetingAdder $meeting): void
    {
        // TODO: Implement scheduling() method.
    }

    public function scheduled(Meeting $meeting): void
    {
        // TODO: Implement scheduled() method.
    }

    public function updating(Meeting $meeting): void
    {
        // TODO: Implement updating() method.
    }

    public function canceling(Meeting $meeting): void
    {
        // TODO: Implement canceling() method.
    }

    public function participantAdding(Participant $participant, Meeting $meeting, string $uuid): void
    {
        // TODO: Implement participantAdding() method.
    }

    public function participantAdded(ParticipantPivot $participant): void
    {
        // TODO: Implement participantAdded() method.
    }

    public function participationCanceling(ParticipantPivot $participant): void
    {
        // TODO: Implement participationCanceling() method.
    }

    public function getPresenterAccess(Meeting $meeting)
    {
        // TODO: Implement getPresenterAccess() method.
    }

    public function getParticipantAccess(Meeting $meeting, Participant $participant)
    {
        // TODO: Implement getParticipantAccess() method.
    }
}


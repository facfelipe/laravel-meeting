<?php

namespace SimpleEducation\Meeting\Exceptions;

use SimpleEducation\Meeting\Contracts\Participant;
use SimpleEducation\Meeting\Models\Meeting;

class ParticipantAlreadyAdded extends \Exception
{

    /**
     * @var \SimpleEducation\Meeting\Contracts\Participant
     */
    protected Participant $participant;

    /**
     * Provides a static method to create a new instance of ParticipantAlreadyAdded Exception
     *
     * @param \SimpleEducation\Meeting\Contracts\Participant $participant
     * @param \SimpleEducation\Meeting\Models\Meeting $meeting
     * @return self
     */
    public static function create(Participant $participant, Meeting $meeting): self
    {
        return new static(
            'The provided participant `%s:%d` is already registered in `%s:%d`',
            $participant,
            $meeting
        );
    }

    /**
     * Create a new instance of ParticipantAlreadyAdded exception
     *
     * @param string $message
     * @param \SimpleEducation\Meeting\Contracts\Participant $participant
     * @param \SimpleEducation\Meeting\Models\Meeting $meeting
     */
    public function __construct(string $message, Participant $participant, Meeting $meeting)
    {
        $this->message = sprintf(
            $message,
            get_class($participant),
            $participant->id,
            $meeting->getMorphClass(),
            $meeting->id
        );

        $this->code = $meeting->id;
        $this->participant = $participant;
    }

    /**
     * Get the meeting id
     *
     * @return int
     */
    public function getMeetingId(): int
    {
        return $this->code;
    }

    /**
     * Get the already registered participant
     *
     * @return \SimpleEducation\Meeting\Contracts\Participant
     */
    public function getParticipant(): Participant
    {
        return $this->participant;
    }
}

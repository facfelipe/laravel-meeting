<?php

namespace SimpleEducation\Meeting\Exceptions;

use SimpleEducation\Meeting\MeetingAdder;
use SimpleEducation\Meeting\Models\Meeting;

class NoZoomRoomAvailable extends \Exception
{

    /**
     * @var \SimpleEducation\Meeting\MeetingAdder
     */
    protected MeetingAdder $meeting;

    /**
     * Undocumented function
     *
     * @param \SimpleEducation\Meeting\MeetingAdder $meeting
     * @return self
     */
    public static function create(MeetingAdder $meeting): self
    {
        return new static(
            'There is no Zoom Room available between `%s` and `%s` to host the meeting with topic `%s`',
            $meeting
        );
    }

    /**
     * Undocumented function
     *
     * @param \SimpleEducation\Meeting\Models\Meeting $meeting
     * @return self
     */
    public static function createFromModel(Meeting $meeting): self
    {
        $meetingAdder = (new MeetingAdder)
            ->scheduledBy($meeting->scheduler)
            ->presentedBy($meeting->presenter)
            ->hostedBy($meeting->host)
            ->withTopic($meeting->topic)
            ->startingAt($meeting->start_time)
            ->during($meeting->duration);

        return static::create($meetingAdder);
    }

    /**
     * Create a new instance of NoZoomRoomAvailable exception
     *
     * @param string $message
     * @param \SimpleEducation\Meeting\MeetingAdder $meeting
     */
    public function __construct(string $message, MeetingAdder $meeting)
    {
        $this->meeting = $meeting;
        $this->message = sprintf(
            $message,
            $meeting->startTime->format('Y-m-d H:i:se'),
            (clone $meeting->startTime)->addMinutes($meeting->duration)->format('Y-m-d H:i:se'),
            $meeting->topic
        );
    }

    /**
     * Get the meeting that regeneted the exception
     *
     * @return \SimpleEducation\Meeting\MeetingAdder
     */
    public function getMeeting(): MeetingAdder
    {
        return $this->meeting;
    }
}

<?php

namespace SimpleEducation\Meeting\Providers\Zoom\Concerns;

use SimpleEducation\Meeting\Contracts\Participant;
use SimpleEducation\Meeting\Events\MeetingCanceled;
use SimpleEducation\Meeting\Events\MeetingScheduled;
use SimpleEducation\Meeting\Events\MeetingUpdated;
use SimpleEducation\Meeting\Events\ParticipantAdded;
use SimpleEducation\Meeting\Events\ParticipationCanceled;
use SimpleEducation\Meeting\Exceptions\NoZoomRoomAvailable;
use SimpleEducation\Meeting\MeetingAdder;
use SimpleEducation\Meeting\Models\Meeting;
use SimpleEducation\Meeting\Models\MeetingRoom;
use SimpleEducation\Meeting\Models\Participant as ParticipantPivot;

trait InteractsWithMeetings
{
    use InteractsWithZoom;
    use ProvidesSettings;

    /**
     *
     * @param \SimpleEducation\Meeting\MeetingAdder $meeting
     * @return void
     * @throws  \SimpleEducation\Meeting\Exceptions\NoZoomRoomAvailable
     */
    public function scheduling(MeetingAdder $meeting): void
    {
        if ($this->shareRooms()) {
            $endTime = (clone $meeting->startTime)->addMinutes($meeting->duration);
            if (!$host = MeetingRoom::findAvailable($meeting->startTime, $endTime)) {
                throw NoZoomRoomAvailable::create($meeting);
            }
            $meeting->hostedBy($host);
        }
        $zoomMeeting = $this->createZoomMeeting(
            $meeting->host->uuid,
            $meeting->topic,
            $meeting->startTime,
            $meeting->duration
        );

        $meeting->withMetaAttributes([
            'zoom_id' => $zoomMeeting->id,
        ]);
    }

    /**
     *
     * @param \SimpleEducation\Meeting\Models\Meeting $meeting
     * @return void
     */
    public function scheduled(Meeting $meeting): void
    {
        event(new MeetingScheduled($meeting));
    }

    /**
     *
     * @param \SimpleEducation\Meeting\Models\Meeting $meeting
     * @return void
     */
    public function updating(Meeting $meeting): void
    {
        if ($meeting->isDirty()) {
            if ($meeting->isDirty('start_time')) {
                if ($this->shareRooms() && $meeting->host->isBusyBetween($meeting->start_time, $meeting->end_time, $meeting)) {
                    //Search for another host if the current is not available for the new start_time and duration
                    if (!$host = MeetingRoom::findAvailable($meeting->start_time, $meeting->end_time)) {
                        throw NoZoomRoomAvailable::createFromModel($meeting);
                    }
                    $meeting->updateHost($host);
                }
            }

            //If the host was changed, create a new zoom meeting and delete the previous one.
            if ($meeting->isDirty('host_id')) {
                $originalZoomMeetingId = $meeting->getMetaValue('zoom_id');

                //Create a new zoom meeting hosted by the new user (room)
                $zoomMeeting = $this->createZoomMeeting(
                    $meeting->host->uuid,
                    $meeting->topic,
                    $meeting->start_time,
                    $meeting->duration
                );

                //Update the zoom id referente and register the participants in the new zoom meeting
                $meeting->setMeta('zoom_id')->asInteger($zoomMeeting->id);

                $meeting->participantsPivot->each(function ($participant) use ($meeting) {
                    $meeting->cancelParticipation($participant->participant);
                    $meeting->addParticipant($participant->participant);
                });

                //Delete the original zoom meeting
                $this->api->deleteMeeting($originalZoomMeetingId);
            } else {

                //Update the zoom meeting without changing user (room)
                $this->updateZoomMeeting(
                    $meeting->meta->zoom_id,
                    $meeting->topic,
                    $meeting->start_time,
                    $meeting->duration
                );
            }
        }
    }

    /**
     *
     * @param \SimpleEducation\Meeting\Models\Meeting $meeting
     * @return void
     */
    public function canceling(Meeting $meeting): void
    {
        $this->api->deleteMeeting($meeting->meta->zoom_id);
    }

    /**
     *
     * @param \SimpleEducation\Meeting\Contracts\Participant $participant
     * @param \SimpleEducation\Meeting\Models\Meeting $meeting
     * @param string $uuid
     * @return void
     */
    public function participantAdding(Participant $participant, Meeting $meeting, string $uuid): void
    {
        $registrant = $this->api->addMeetingParticipant($meeting->meta->zoom_id, [
            'email' => $participant->getParticipantEmailAddress(),
            'first_name' => $participant->getParticipantFirstName(),
            'last_name' => $participant->getParticipantLastName(),
        ]);

        $meeting->setMeta($uuid)->asObject($registrant);
    }

    /**
     *
     * @param \SimpleEducation\Meeting\Models\Participant $participant
     * @return void
     */
    public function participantAdded(ParticipantPivot $participant): void
    {
        if ($metaUuid = $participant->meeting->getMeta($participant->uuid)) {
            $participant->setMeta('registrantId')->asString($metaUuid->value->registrantId);
            $participant->setMeta('joinUrl')->asString($metaUuid->value->joinUrl);
            $participant->setMeta('email')->asString($participant->participant->getParticipantEmailAddress());

            $metaUuid->delete();
        }

        event(new ParticipantAdded($participant));
    }

    /**
     *
     * @param \SimpleEducation\Meeting\Models\Participant $participant
     * @return void
     */
    public function participationCanceling(ParticipantPivot $participant): void
    {
        $registrant = [
            'id' => $participant->meta->registrantId,
            'email' => $participant->meta->email,
        ];

        $this->api->updateMeetingParticipantStatus($participant->meeting->meta->zoom_id, [
            'action' => 'cancel',
            'registrants' => [$registrant],
        ]);

        $participant->clearMetas();
    }


}

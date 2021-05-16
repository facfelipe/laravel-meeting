<?php


namespace SimpleEducation\Meeting\Providers\Google;


use SimpleEducation\Meeting\Contracts\Provider;
use SimpleEducation\Meeting\Providers\MeetProvider;

abstract class GoogleProvider extends MeetProvider implements Provider
{
    use Concerns\InteractsWithMeetings;

}


<?php

namespace App\Tests\Talk;

use App\TwigExtension\Talk\TalksExtension;
use DateTime;
use PHPUnit\Framework\TestCase;
use Tightenco\Collect\Support\Collection;

class RetrievingTalksTest extends TestCase
{
    /**
     * @var TalksExtension
     */
    private $extension;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        $this->extension = new TalksExtension();
    }

    /** @test */
    public function talks_given_multiple_times_are_only_returned_once()
    {
        $talkA = [
            'title' => 'Talk A',
            'events' => [
                ['event' => 'event_a', 'date' => (new DateTime('-1 days'))->getTimestamp()],
                ['event' => 'event_b', 'date' => (new DateTime('+1 days'))->getTimestamp()],
            ],
        ];

        $talkB = [
            'title' => 'Talk B',
            'events' => [
                ['event' => 'event_a', 'date' => (new DateTime('-3 days'))->getTimestamp()],
            ],
        ];

        $this->assertCount(2, $this->extension->getTalks([$talkA, $talkB]));
    }

    /** @test */
    public function talks_are_ordered_by_the_most_recent_event_date()
    {
        $talkA = [
            'title' => 'Talk A',
            'events' => [
                ['event' => 'event_a', 'date' => (new DateTime('-5 days'))->getTimestamp()],
            ],
        ];

        $talkB = [
            'title' => 'Talk B',
            'events' => [
                ['event' => 'event_a', 'date' => (new DateTime('-20 days'))->getTimestamp()],
            ],
        ];

        $talkC = [
            'title' => 'Talk C',
            'events' => [
                ['event' => 'event_a', 'date' => (new DateTime('-3 days'))->getTimestamp()],
                ['event' => 'event_b', 'date' => (new DateTime('-10 days'))->getTimestamp()],
            ],
        ];

        $unorderedTalks = [$talkC, $talkA, $talkB];
        $orderedTalks = $this->extension->getTalks($unorderedTalks);

        $this->assertEquals([$talkC, $talkA, $talkB], $orderedTalks->toArray());
    }

    /** @test */
    public function only_past_talks_can_be_retrieved()
    {
        $pastTalk = [
            'title' => 'Past talk',
            'events' => [
                ['date' => (new DateTime('-1 day'))->getTimestamp()],
            ]
        ];

        $futureTalk = [
            'title' => 'Future talk',
            'events' => [
                ['date' => (new DateTime('+1 day'))->getTimestamp()],
            ],
        ];

        $talks = $this->extension->getTalks([$pastTalk, $futureTalk]);
        $filtered = $this->extension->filterPastTalks($talks);

        $this->assertCount(1, $filtered);
        $this->assertSame($pastTalk, $filtered->first());
    }

    /** @test */
    public function only_current_and_future_talks_can_be_retrieved()
    {
        $pastTalk = [
            'title' => 'Past talk',
            'events' => [
                ['date' => (new DateTime('-1 day'))->getTimestamp()],
            ]
        ];

        $todayTalk = [
            'title' => 'A talk that it happening today',
            'events' => [
                ['date' => (new DateTime('now'))->getTimestamp()],
            ],
        ];

        $futureTalk = [
            'title' => 'Future talk',
            'events' => [
                ['date' => (new DateTime('+1 day'))->getTimestamp()],
            ],
        ];

        $talks = $this->extension->getTalks([$pastTalk, $todayTalk, $futureTalk]);
        $filtered = $this->extension->filterUpcomingTalks($talks);

        $this->assertSame(2, $filtered->count());
        $this->assertSame([$todayTalk, $futureTalk], $filtered->toArray());
    }

    /** @test */
    public function if_a_talk_is_both_upcoming_and_past_then_it_is_only_shown_as_upcoming()
    {
        $talk = [
            'title' => 'An upcoming talk that has been given before',
            'events' => [
                ['date' => (new DateTime('-1 week'))->getTimestamp()],
                ['date' => (new DateTime('+1 week'))->getTimestamp()],
            ],
        ];

        $talks = $this->extension->getTalks([$talk]);

        $this->assertCount(1, $this->extension->filterUpcomingTalks($talks));
        $this->assertEmpty($this->extension->filterPastTalks($talks));
    }

    /** @test */
    public function get_events_from_talks()
    {
        $talkA = [
            'title' => 'Talk A',
            'events' => [
                ['event' => 'event_a', 'date' => (new DateTime('-1 days'))->getTimestamp()],
                ['event' => 'event_b', 'date' => (new DateTime('+1 days'))->getTimestamp()],
            ],
        ];

        $talkB = [
            'title' => 'Talk B',
            'events' => [
                ['event' => 'event_a', 'date' => (new DateTime('-3 days'))->getTimestamp()],
            ],
        ];

        $talks = new Collection([$talkA, $talkB]);

        tap($this->extension->getAllEvents($talks), function (Collection $events) {
            $this->assertCount(3, $events);

            $this->assertSame(
                ['event_a', 'event_b', 'event_a'],
                $events->pluck('event')->toArray()
            );

            $this->assertSame(3, $events->pluck('date')->unique()->count());
        });
    }
}

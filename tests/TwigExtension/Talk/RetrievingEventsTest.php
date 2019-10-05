<?php

namespace App\Tests\Event;

use App\TwigExtension\Talk\TalksExtension;
use DateTime;
use PHPUnit\Framework\TestCase;
use Tightenco\Collect\Support\Collection;

class RetrievingEventsTest extends TestCase
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
    public function get_past_events()
    {
        $talkA = [
            'title' => 'Test Driven Drupal',
            'events' => [
                [
                    'event' => 'php_south_wales',
                    'date' => (new DateTime('+1 days'))->getTimestamp(),
                ],
                [
                    'event' => 'drupalcamp_london',
                    'date' => (new DateTime('-1 days'))->getTimestamp(),
                ],
            ],
        ];

        $talkB = [
            'title' => 'Taking Flight with Tailwind CSS',
            'events' => [
                [
                    'event' => 'blue_conf_2019',
                    'date' => (new DateTime('-2 days'))->getTimestamp(),
                ],
            ],
        ];

        $talks = $this->extension->getTalks([$talkA, $talkB]);
        $events = $this->extension->filterPastEvents($talks);

        $this->assertInstanceOf(Collection::class, $talks);
        $this->assertInstanceOf(Collection::class, $events);

        $this->assertCount(2, $events);
    }
    /** @test */
    public function get_current_or_upcoming_events()
    {
        $talkA = [
            'title' => 'Test Driven Drupal',
            'events' => [
                [
                    'event' => 'php_south_wales',
                    'date' => (new DateTime('+0 days'))->getTimestamp(),
                ],
                [
                    'event' => 'drupalcamp_london',
                    'date' => (new DateTime('-1 days'))->getTimestamp(),
                ],
            ],
        ];

        $talkB = [
            'title' => 'Taking Flight with Tailwind CSS',
            'events' => [
                [
                    'event' => 'blue_conf_2019',
                    'date' => (new DateTime('+2 days'))->getTimestamp(),
                ],
            ],
        ];


        $talks = $this->extension->getTalks([$talkA, $talkB]);
        $events = $this->extension->filterUpcomingEvents($talks);

        $this->assertInstanceOf(Collection::class, $talks);
        $this->assertInstanceOf(Collection::class, $events);

        $this->assertCount(2, $events);
    }
}

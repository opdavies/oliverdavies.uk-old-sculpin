<?php

namespace App\Talks\TwigExtension;

use Sculpin\Contrib\ProxySourceCollection\ProxySourceCollection;
use Tightenco\Collect\Support\Collection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TalksExtension extends AbstractExtension
{
    /**
     * @var int The current date.
     */
    private $today;

    public function __construct()
    {
        $this->today = (new \DateTime())
          ->modify('today')
          ->setTimezone(new \DateTimeZone('Europe/London'))
          ->getTimestamp();
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('getTalks', [$this, 'getTalks']),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('pastEvents', [$this, 'filterPastEvents']),
            new TwigFilter('pastTalks', [$this, 'filterPastTalks']),
            new TwigFilter('upcomingEvents', [$this, 'filterUpcomingEvents']),
            new TwigFilter('upcomingTalks', [$this, 'filterUpcomingTalks']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app.talks';
    }

    /**
     * Get all upcoming and previous talks.
     *
     * @param ProxySourceCollection|array $talks All talk nodes.
     *
     * @return Collection A sorted collection of talks.
     */
    public function getTalks($talks): Collection
    {
        return collect($talks)->sortBy(function ($talk) {
            return $this->getLastDate($talk);
        });
    }

    public function filterUpcomingTalks(Collection $talks): array
    {
        return $talks->filter(function ($talk): bool {
            return $this->getLastDate($talk) >= $this->today;
        })->values()->toArray();
    }

    public function filterPastTalks(Collection $talks): array
    {
        return $talks->filter(function ($talk): bool {
            return $this->getLastDate($talk) < $this->today;
        })->values()->toArray();
    }

    private function getLastDate($talk): string
    {
        return $this->eventsFromTalks(collect([$talk]))
            ->pluck('date')->max();
    }

    public function filterUpcomingEvents($talks): array
    {
        return $this->eventsFromTalks($talks)->filter(function ($event): bool {
            return $event['date'] >= $this->today;
        })->sortBy('date')->toArray();
    }

    public function filterPastEvents($talks): array
    {
        return $this->eventsFromTalks($talks)->filter(function ($event): bool {
            return $event['date'] < $this->today;
        })->sortBy('date')->toArray();
    }

    private function eventsFromTalks($talks): Collection
    {
        return collect($talks)->flatMap(function ($talk): array {
            return $talk['events'];
        });
    }

    public function getAllEvents($talks): array
    {
        return $this->eventsFromTalks($talks)->toArray();
    }
}

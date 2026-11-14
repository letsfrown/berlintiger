<?php

return function ($page, $site) {

    // Alle Kinder-Zitate in zufälliger Reihenfolge; der Hero blendet sie nacheinander ein
    $quotes = $page->quotes()->toFiles()->shuffle();

    $news = collection('news');
    $maxAge = $page->newsMaxAge()->value();

    if ($maxAge !== 'none' && $maxAge !== '' && $maxAge !== null) {
        $cutoff = (new DateTimeImmutable('today'))
            ->modify('-' . (int)$maxAge . ' months')
            ->format('Y-m-d');

        $news = $news->filterBy('date', '>=', $cutoff);
    }

    return [
        'quotes'   => $quotes,
        'homeNews' => $news->limit(1),
    ];
};

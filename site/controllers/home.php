<?php

return function ($page, $site) {

    $quoteFile = $page->quotes()->toFiles()->shuffle()->first();

    $news = collection('news');
    $maxAge = $page->newsMaxAge()->value();

    if ($maxAge !== 'none' && $maxAge !== '' && $maxAge !== null) {
        $cutoff = (new DateTimeImmutable('today'))
            ->modify('-' . (int)$maxAge . ' months')
            ->format('Y-m-d');

        $news = $news->filterBy('date', '>=', $cutoff);
    }

    return [
        'quote'    => $quoteFile ? $quoteFile->resize(null, 300) : null,
        'homeNews' => $news->limit(1),
    ];
};

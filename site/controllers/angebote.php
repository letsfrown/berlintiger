<?php

return function ($page) {

    $teams              =    $page->grandChildren('team')->listed();
    $hasCategory        =    $teams->filterBy('category', '!=', null);
    $groupedTeams       =    $hasCategory->sortBy('category', 'desc')->groupBy('category');
    $hasNoCategory      =    $teams->filterBy('category', '==', null);
    $unGroupedTeams     =    $hasNoCategory->sortBy('title', 'desc');

    return [
        'teams'           =>   $teams,
        'groupedTeams'    =>   $groupedTeams,
        'hasNoCategory'  =>   $hasNoCategory,
    ];
};

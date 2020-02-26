<?php

namespace frontend\models\interfaces;

interface SearchResultItem
{
    public function titleStr();
    public function imageUrl();
    public function categoryArr();
    public function audienceArr();
    public function dateStr();
    public function priceBlock();
    public function durationStr();
    public function themeArray();
    public function containsImages();
    public function containsVideos();
    public function guideName();
    public function guideAvatar();
    public function startCityName();
    public function linkTo();
    public function idStr();
    public function editBlock();
}

<?php

use Mavimo\Sculpin\Bundle\RedirectBundle\SculpinRedirectBundle;
use Opdavies\Sculpin\Bundle\ContentGeneratorBundle\SculpinContentGeneratorBundle;
use Opdavies\Sculpin\Bundle\GistEmbedBundle\SculpinGistEmbedBundle;
use Sculpin\Bundle\SculpinBundle\HttpKernel\AbstractKernel;
use Tsphethean\Sculpin\Bundle\RelatedPostsBundle\SculpinRelatedPostsBundle;

/**
 * Class SculpinKernel
 */
class SculpinKernel extends AbstractKernel
{
    /**
     * {@inheritdoc}
     */
    protected function getAdditionalSculpinBundles()
    {
        return [
            SculpinContentGeneratorBundle::class,
            SculpinGistEmbedBundle::class,
            SculpinRelatedPostsBundle::class,
            SculpinRedirectBundle::class
        ];
    }
}

<?php

namespace Log1x\Plugin\NofollowPrettyLinks;

use Symfony\Component\DomCrawler\Crawler;
use Tightenco\Collect\Support\Collection;

class NofollowPrettyLinks
{
    /**
     * The plugin directory path.
     *
     * @var string
     */
    protected $path;

    /**
     * The plugin directory URI.
     *
     * @var string
     */
    protected $uri;

    /**
     * The Pretty Link instance.
     *
     * @var \PrliLink
     */
    protected $prettyLink;

    /**
     * Initialize the plugin.
     *
     * @param  string $path
     * @param  string $uri
     * @return void
     */
    public function __construct($path, $uri)
    {
        $this->path = $path;
        $this->uri = $uri;

        if (! class_exists('\PrliLink')) {
            return;
        }

        $this->prettyLink = new \PrliLink();

        $this->filterContent();
    }

    /**
     * Ensure any URL's controlled by Pretty Links are set to
     * `rel="nofollow"` and `target="_blank"` when displayed in the
     * content or excerpt.
     *
     * @return void
     */
    public function filterContent()
    {
        foreach (['the_content', 'the_excerpt'] as $hook) {
            add_filter($hook, function ($content) {
                if (is_admin() || empty(trim($content))) {
                    return $content;
                }

                $crawler = new Crawler($content);

                $links = $this->collect(
                    $crawler->filterXpath('//a[@href]')
                )->filter(function ($value) {
                    return ! empty(
                        $this->prettyLink->is_pretty_link(
                            untrailingslashit($value->getAttribute('href')),
                            false
                        )
                    );
                });

                if ($links->isEmpty()) {
                    return $content;
                }

                $links->each(function ($value) {
                    $rel = $this->collect(
                        explode(' ', $value->getAttribute('rel'))
                    )->push('nofollow')->unique()->implode(' ');

                    $target = $this->collect(
                        explode(' ', $value->getAttribute('target'))
                    )->push('_blank')->unique()->implode(' ');

                    $value->setAttribute('rel', trim($rel));
                    $value->setAttribute('target', trim($target));
                });

                return $crawler->count() ?
                    $crawler->outerHtml() :
                    $content;
            });
        }
    }

    /**
     * Create a new collection instance.
     *
     * @param  mixed $items
     * @return \Tightenco\Collect\Support\Collection
     */
    protected function collect($items = [])
    {
        return new Collection($items);
    }
}

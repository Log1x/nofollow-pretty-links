<?php

namespace Log1x\Plugin\NofollowPrettyLinks;

use DOMElement;
use Symfony\Component\DomCrawler\Crawler;

class NofollowPrettyLinks
{
    /**
     * The Pretty Link instance.
     *
     * @var \PrliLink
     */
    protected $prettyLink;

    /**
     * Initialize the plugin.
     */
    public function __construct(protected string $path, protected string $uri)
    {
        //
    }

    /**
     * Boot the plugin.
     */
    public function boot(): void
    {
        if (! class_exists('\PrliLink')) {
            return;
        }

        $this->prettyLink = new \PrliLink;

        $this->filterContent();
    }

    /**
     * Add `nofollow` and `_blank` attributes to Pretty Links.
     */
    public function filterContent(): void
    {
        foreach (['the_content', 'the_excerpt'] as $hook) {
            add_filter($hook, function ($content) {
                if (is_admin() || empty(trim($content))) {
                    return $content;
                }

                $crawler = new Crawler($content);

                $links = $crawler->filterXpath('//a[@href]');

                if (! $links->count()) {
                    return $content;
                }

                $modified = false;

                foreach ($links as $link) {
                    $href = untrailingslashit($link->getAttribute('href'));

                    if (empty($this->prettyLink->is_pretty_link($href, false))) {
                        continue;
                    }

                    $modified = true;

                    $this->addAttributes($link);
                }

                return $modified
                    ? $crawler->outerHtml()
                    : $content;
            });
        }
    }

    /**
     * Add nofollow and _blank attributes to a link.
     */
    protected function addAttributes(DOMElement $link): void
    {
        $rel = $link->getAttribute('rel');

        $rels = $rel
            ? array_filter(explode(' ', $rel))
            : [];

        $rels = array_merge($rels, ['nofollow', 'noopener']);

        $link->setAttribute('rel', implode(' ', array_unique($rels)));

        $target = $link->getAttribute('target');

        $targets = $target
            ? array_filter(explode(' ', $target))
            : [];

        $targets[] = '_blank';

        $link->setAttribute('target', implode(' ', array_unique($targets)));
    }
}

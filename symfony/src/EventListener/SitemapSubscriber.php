<?php


namespace App\EventListener;


use App\Repository\ArticleRepository;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SitemapSubscriber implements EventSubscriberInterface
{
    private $articleRepository;
    private $urlGenerator;

    public function __construct(ArticleRepository $articleRepository, UrlGeneratorInterface $urlGenerator)
    {
        $this->articleRepository = $articleRepository;
        $this->urlGenerator = $urlGenerator;
    }

    public static function getSubscribedEvents()
    {
        return [
            SitemapPopulateEvent::ON_SITEMAP_POPULATE => 'populate',
        ];
    }

    /**
     * @param SitemapPopulateEvent $event
     */
    public function populate(SitemapPopulateEvent $event): void
    {
        $this->registerBlogPostsUrls($event->getUrlContainer());
    }

    /**
     * @param UrlContainerInterface $urls
     */
    public function registerBlogPostsUrls(UrlContainerInterface $urls): void
    {
        $posts = $this->articleRepository->findBy(['archived' => false]);

        foreach ($posts as $post) {
            $urls->addUrl(
                new UrlConcrete(
                    $this->urlGenerator->generate(
                        'blog_show',
                        ['slug' => $post->getSlug()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    )
                ),
                'blog'
            );
        }
    }
}
<?php

namespace App\Services;
use App\Entity\Article;
use App\Entity\User;
use App\Entity\Visit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class VisitManager
{
    private $em;
    private $request;

    public function __construct(EntityManagerInterface $em, RequestStack $request)
    {
        $this->em = $em;
        $this->request = $request;
    }

    public function addVisit(Article $article, ?User $user)
    {
        $ip = $this->getIp();
        if ($this->em->getRepository(Visit::class)->findBy(compact('ip', 'article')) || ($user && $user->isAdmin())) {
            return;
        }

        $visit = new Visit();
        $visit->setArticle($article);
        $visit->setIp($ip);
        $visit->setAgent($this->getUserAgent());
        $visit->setType($this->agentToOs($this->getUserAgent()));


        $this->em->persist($visit);
        $this->em->flush();
    }

    public function getIp(): ?string
    {
        if (null !== $currentRequest = $this->request->getCurrentRequest()) {
            return $currentRequest->getClientIp();
        }

        return null;
    }

    public function agentToOs(string $ua): string
    {
        if (false !== strpos($ua, 'Android')) {
            return 'android';
        }

        if (false !== strpos($ua, 'iPhone') || false !== strpos($ua, 'iPod')) {
            return 'ios';
        }

        return 'desktop';
    }

    public function getUserAgent(): ?string
    {
        if (null !== $currentRequest = $this->request->getCurrentRequest()) {
            return $currentRequest->server->get('HTTP_USER_AGENT');
        }

        return null;
    }
}

?>
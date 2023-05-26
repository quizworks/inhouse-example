<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Article;
use Authorization\IdentityInterface;

class ArticlePolicy
{
    /**
     * @param \Authorization\IdentityInterface $user
     * @param \App\Model\Entity\Article $article
     * @return true
     */
    public function canAdd(IdentityInterface $user, Article $article)
    {
        // All logged in users can create articles.
        return true;
    }

    /**
     * @param \Authorization\IdentityInterface $user
     * @param \App\Model\Entity\Article $article
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Article $article)
    {
        // logged in users can edit their own articles.
        return $this->isAuthor($user, $article);
    }

    /**
     * @param \Authorization\IdentityInterface $user
     * @param \App\Model\Entity\Article $article
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Article $article)
    {
        // logged in users can delete their own articles.
        return $this->isAuthor($user, $article);
    }

    /**
     * @param \Authorization\IdentityInterface $user
     * @param \App\Model\Entity\Article $article
     * @return bool
     */
    protected function isAuthor(IdentityInterface $user, Article $article)
    {
        return $article->user_id === $user->getIdentifier();
    }
}

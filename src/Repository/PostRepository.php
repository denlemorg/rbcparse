<?php

namespace App\Repository;

use App\Entity\Post;
use App\Service\NewsCollector\Parsers\MainNewsItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    private $checkPosts = [];
    /**
     * PostRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function findLastNews()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.position', 'DESC')
            ->setMaxResults(15)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param MainNewsItem[] $news
     */
    public function saveNewsWithChecking($news): void
    {
        $this->checkPosts = $this->findLastNews();
        foreach ($news as $currentNewsItem) {
            $post = $this->checkAndCreateNewsEntity($currentNewsItem->getLink());
            $post = $this->updatePostFromMainItem($currentNewsItem, $post);
            $this->getEntityManager()->persist($post);
        }
        $this->getEntityManager()->flush();
    }

    /**
     * @param string $link
     * @return Post
     */
    private function checkAndCreateNewsEntity(string $link): Post
    {
        $postObj = new Post();
        if (count($this->checkPosts) > 0) {
            foreach ($this->checkPosts as $checkPost) {
                if ($checkPost->getLink() == $link) {
                    $postObj = $checkPost;
                    break;
                }
            }
        }
        return $postObj;
    }

    /**
     * @param MainNewsItem $currentNewsItem
     * @param Post $post
     * @return Post
     */
    public function updatePostFromMainItem(MainNewsItem $currentNewsItem, Post $post): Post
    {
        $post->setTitle($currentNewsItem->getTitle());
        $post->setBody($currentNewsItem->getBody());
        $post->setImage($currentNewsItem->getImage());
        $post->setLink($currentNewsItem->getLink());
        $post->setCreatedAt($currentNewsItem->getDate());
        $post->setPosition($currentNewsItem->getPosition());
        return $post;
    }



    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

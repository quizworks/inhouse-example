<?php
declare(strict_types=1);

// src/Controller/ArticlesController.php

namespace App\Controller;

/**
 * @property-read \App\Model\Table\ArticlesTable $Articles
 * @property-read \Authorization\AuthorizationServiceInterface $Authorization
 */
class ArticlesController extends AppController
{
    /**
     * @return void
     */
    public function index(): void
    {
        $this->Authorization->skipAuthorization();
        $articles = $this->paginate($this->Articles->find());
        $this->set(compact('articles'));
    }

    /**
     * @param $slug
     * @return void
     */
    public function view($slug): void
    {
        $this->Authorization->skipAuthorization();
        $article = $this->Articles
            ->findBySlug($slug)
            ->contain('Tags')
            ->firstOrFail();
        $this->set(compact('article'));
    }

    /**
     * @return \Cake\Http\Response|void|null
     */
    public function add()
    {
        $article = $this->Articles->newEmptyEntity();
        $this->Authorization->authorize($article);

        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());

            // Added: Set the user_id from the session.
            $article->user_id = $this->request->getAttribute('identity')->getIdentifier();

            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add your article.'));
        }
        $this->set('article', $article);
    }

    /**
     * @param $slug
     * @return \Cake\Http\Response|void|null
     */
    public function edit($slug)
    {
        $article = $this->Articles
            ->findBySlug($slug)
            ->contain('Tags') // load associated Tags
            ->firstOrFail();
        $this->Authorization->authorize($article);

        if ($this->request->is(['post', 'put'])) {
            $this->Articles->patchEntity($article, $this->request->getData(), [
                // Added: Disable modification of user_id.
                'accessibleFields' => ['user_id' => false],
            ]);
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been updated.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update your article.'));
        }

        // Get a list of tags.
        $tags = $this->Articles->Tags->find('list');

        // Set article & tags to the view context
        $this->set('tags', $tags);
        $this->set('article', $article);
    }

    /**
     * @param $slug
     * @return \Cake\Http\Response|void|null
     */
    public function delete($slug)
    {
        $this->request->allowMethod(['post', 'delete']);

        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        $this->Authorization->authorize($article);

        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('The {0} article has been deleted.', $article->title));

            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * @return void
     */
    public function tags(): void
    {
        $this->Authorization->skipAuthorization();

        // The 'pass' key is provided by CakePHP and contains all
        // the passed URL path segments in the request.
        $tags = $this->request->getParam('pass');

        // Use the ArticlesTable to find tagged articles.
        $articles = $this->Articles->find('tagged', [
            'tags' => $tags,
        ]);

        // Pass variables into the view template context.
        $this->set([
            'articles' => $articles,
            'tags' => $tags,
        ]);
    }
}

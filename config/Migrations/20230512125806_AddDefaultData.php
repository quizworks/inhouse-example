<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddDefaultData extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $this->table('users')->insert([
            'email' => 'cakephp@example.com',
            'password' => 'secret',
            'created' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s')
        ])->save();

        $this->table('articles')->insert([
            'user_id' => 1,
            'title' => 'First Post',
            'slug' => 'first-post',
            'body' => 'This is the first post.',
            'published' => true,
            'created' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s')
        ])->save();
    }
}

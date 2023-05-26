<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\UsersController Test Case
 */
class UsersControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Users',
    ];

    public function testLoginShow()
    {
        $this->get('/users/login');
        $this->assertResponseOk();
        $this->assertResponseContains('login');
    }

    public function testLoginFailed()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->post('/users/login', [
            'email' => 'myname@example.com',
            'password' => 'wrongpassword',
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('Invalid username or password');
    }

    public function testLoginSucceed()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->post('/users/login?redirect=%2Farticles', [
            'email' => 'myname@example.com',
            'password' => 'password',
        ]);
        $this->assertResponseNotContains('Invalid username or password');
        $this->assertRedirect('/articles');
    }

    public function testLogout()
    {
        $this->session(['Auth.User.id' => 1]);

        $this->get('/users/logout');

        $this->assertRedirect('/users/login');
    }
}

<?php

use PHPUnit\Framework\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    public function test_create_user()
    {
        $data = [
            'name' => 'Jhon Cash',
            'email' => 'emailtest@test.com',
            'password' => 'secret'
        ];

        $result = User::createUser($data);

        $this->assertEquals('success', $result['status']);
        $this->assertEquals('Usuário inserido com sucesso.', $result['message']);
    }

    public function test_get_user()
    {
        $result = User::getUserByEmail('emailtest@test.com');

        $this->assertEquals('success', $result['status']);
        $this->assertEquals('Jhon Cash', $result['data']['name']);
    }

    public function test_update_user()
    {
        $user = User::getUserByEmail('emailtest@test.com');
        $data = [
            'id' => $user['data']['id'],
            'name' => 'John Doe Updated'
        ];

        $result = User::updateUser($data);
        $this->assertEquals('success', $result['status']);
        $this->assertEquals('Usuário atualizado com sucesso.', $result['message']);
    }

    public function test_list_users()
    {
        $result = User::listUsers();

        $this->assertEquals('success', $result['status']);
        $this->assertTrue(count($result['data']) >= 1);
    }

    public function test_delete_user()
    {
        $user = User::getUserByEmail('emailtest@test.com');
        $result = User::deleteUser($user['data']['id'], true);

        $this->assertEquals('success', $result['status']);
        $this->assertEquals('Usuário excluído permanentemente com sucesso.', $result['message']);
    }
}

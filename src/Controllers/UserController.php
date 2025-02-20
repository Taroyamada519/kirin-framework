<?php
namespace App\Controllers;

use App\Models\User;

class UserController extends BaseController {
    private $userModel;

    public function __construct($request, $response) {
        parent::__construct($request, $response);
        $this->userModel = new User();
    }

    public function index() {
        $users = $this->userModel->all();
        $this->response->success($users);
    }

    public function show() {
        $id = $this->request->getParam('id');
        $user = $this->userModel->find($id);

        if (!$user) {
            $this->response->notFound('User not found');
        }

        $this->response->success($user);
    }

    public function store() {
        $data = $this->request->getBody();
        
        // バリデーション
        $errors = $this->validate($data, [
            'name' => 'required|min:2|max:50',
            'email' => 'required|email'
        ]);

        if (!empty($errors)) {
            $this->response->error(['validation' => $errors], 422);
        }

        // メールアドレスの重複チェック
        if (!$this->userModel->validateUnique($data['email'])) {
            $this->response->error(['email' => 'This email is already registered'], 422);
        }

        // ユーザーの作成
        $userId = $this->userModel->create($data);
        $user = $this->userModel->find($userId);
        
        $this->response->success($user, 'User created successfully');
    }

    public function update() {
        $id = $this->request->getParam('id');
        $data = $this->request->getBody();

        // ユーザーの存在確認
        $user = $this->userModel->find($id);
        if (!$user) {
            $this->response->notFound('User not found');
        }

        // バリデーション
        $errors = $this->validate($data, [
            'name' => 'required|min:2|max:50',
            'email' => 'required|email'
        ]);

        if (!empty($errors)) {
            $this->response->error(['validation' => $errors], 422);
        }

        // メールアドレスの重複チェック（現在のユーザーを除外）
        if (!$this->userModel->validateUnique($data['email'], $id)) {
            $this->response->error(['email' => 'This email is already registered'], 422);
        }

        // ユーザーの更新
        $this->userModel->update($id, $data);
        $updatedUser = $this->userModel->find($id);
        
        $this->response->success($updatedUser, 'User updated successfully');
    }

    public function delete() {
        $id = $this->request->getParam('id');
        
        // ユーザーの存在確認
        $user = $this->userModel->find($id);
        if (!$user) {
            $this->response->notFound('User not found');
        }

        // ユーザーの削除
        $this->userModel->delete($id);
        $this->response->success(null, 'User deleted successfully');
    }
}

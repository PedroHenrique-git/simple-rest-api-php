<?php
    namespace api;

    class UserController {
        private UserRepository | NULL $repository = null;

        public function __construct(UserRepository $repository) {
            $this->repository = $repository;
        }

        public function delete($id) {
            \header('Content-Type: application/json');
            
            try {
                $this->repository->delete($id);

                $response = new \stdClass();
                $response->deleted = true;

                echo json_encode($response);
            } catch(AppException $err) {
                $error = new \stdClass();

                $error->message = $err->getMessage();

                http_response_code($err->getCode());

                echo json_encode($error);
            }
        }

        public function getAll() {
            \header('Content-Type: application/json');
            
            try {
                $users = $this->repository->getAll();
                $emptyUsers = [];
            
                foreach ($users as $user) {
                    $emptyUser = new \stdClass();
    
                    $emptyUser->id = $user->getId();
                    $emptyUser->name = $user->getName();
                    $emptyUser->email = $user->getEmail();
                    $emptyUser->phone = $user->getPhone();
    
                    $emptyUsers []= $emptyUser;
                }
    
                echo json_encode($emptyUsers);
            } catch(AppException $err) {
                $error = new \stdClass();

                $error->message = $err->getMessage();

                http_response_code($err->getCode());

                echo json_encode($error);
            }
        }

        public function update($id) {
            \header('Content-Type: application/json');
            
            try {
                $user = json_decode(file_get_contents('php://input'), true);

                $this->repository->update(
                    new User(
                        $id,
                        $user['name'],
                        $user['email'],
                        $user['phone']
                    )
                );

                $response = new \stdClass();
                $response->updated = true;

                echo json_encode($response);
            } catch(AppException $err) {
                $error = new \stdClass();

                $error->message = $err->getMessage();

                http_response_code($err->getCode());

                echo json_encode($error);
            }
        }

        public function create() {
            \header('Content-Type: application/json');
            
            try {
                $user = json_decode(file_get_contents('php://input'), true);

                $this->repository->create(
                    new User(
                        0,
                        $user['name'],
                        $user['email'],
                        $user['phone']
                    )
                );

                $response = new \stdClass();
                $response->created = true;

                echo json_encode($response);
            } catch(AppException $err) {
                $error = new \stdClass();

                $error->message = $err->getMessage();

                http_response_code($err->getCode());

                echo json_encode($error);
            }
        }
    }
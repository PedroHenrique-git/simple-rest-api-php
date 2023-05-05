<?php
    namespace api;

    require 'shared/exceptions/AppException.php';
    require 'modules/user/user.php';
    require 'user-repository.php';

    class UserRepositoryMysql implements UserRepository {
        private \PDO | NULL $db = null;

        public function __construct(\PDO | NULL $pdo) {
            $this->db = $pdo;
        }

        public function delete($id) {
            try {
                $sql = 'DELETE FROM user where id = :id';

                $ps = $this->db->prepare($sql);

                $ps->execute([
                    'id' => $id,
                ]);

                if($ps->rowCount() < 1) {
                    throw new AppException('User not found', 404);
                }

                return true;
            } catch(\PDOException $err) {
                throw new AppException('Error when trying to delete a new user', 500, $err);
            } 
        }

        public function getAll() {
            try {
                $sql = 'SELECT id, name, email, phone from user';
                $users = [];

                $ps = $this->db->prepare($sql);
                $ps->setFetchMode(\PDO::FETCH_ASSOC);
                $ps->execute();

                foreach($ps as $reg) {
                    $users []= new User(
                        $reg['id'],
                        $reg['name'],
                        $reg['email'],
                        $reg['phone']
                    );
                }

                return $users;
            } catch(\PDOException $err) {
                throw new AppException('Error when trying to get all users', 500, $err);
            }  
        }

        public function update($user) {
            try {
                $sql = 'UPDATE user SET name = :name, email = :email, phone = :phone where id = :id';

                $ps = $this->db->prepare($sql);

                $ps->execute([
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'phone' => $user->getPhone()
                ]);

                if($ps->rowCount() < 1) {
                    throw new AppException('User not found', 404);
                }

                return true;
            } catch(\PDOException $err) {
                throw new AppException('Error when trying to update a new user', 500, $err);
            } 
        }

        public function create($user) {
            try {
                $sql = 'INSERT INTO user(name, email, phone) values(:name, :email, :phone)';

                $ps = $this->db->prepare($sql);

                $ps->execute([
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'phone' => $user->getPhone()
                ]);

                return true;
            } catch(\PDOException $err) {
                throw new AppException('Error when trying to create a new user', 500, $err);
            } 
        }
    }
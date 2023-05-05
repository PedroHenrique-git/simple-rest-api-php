<?php
    namespace api;

    interface UserRepository {
        public function create($user);
        public function update($user);
        public function getAll();
        public function delete($id);
    }
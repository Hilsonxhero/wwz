<?php

namespace Modules\Comment\Repository;

interface CommentRepositoryInterface
{
    public function find($id);
    public function get();
    public function all();
    public function allActive();
    public function create($commentable, $data);
    public function update($id, $data);
    public function show($id);
    public function delete($id);
}

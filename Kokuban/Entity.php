<?php
namespace Kokuban;

class Entity
{
    public $id;
    public $registered_at;
    public $updated_at;

    public function __construct($id)
    {
        $this->id = $id;
        $this->registered_at = time();
        $this->updated_at = time();
    }
}

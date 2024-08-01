<?php

namespace Models;

class Task
{
    private $id;
    private $title;
    private $body;
    private $status_id;
    private $creator_id;
    private $assignee_id;
    public function __construct($id, $title, $body, $status_id, $creator_id, $assignee_id)
    {
        $this->id = $id;
        $this->title = $title;
        $this->body = $body;
        $this->status_id = $status_id;
        $this->creator_id = $creator_id;
        $this->assignee_id = $assignee_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function getStatus_id()
    {
        return $this->status_id;
    }

    public function getCreator_id()
    {
        return $this->creator_id;
    }

    public function getAssignee_id()
    {
        return $this->assignee_id;
    }

}
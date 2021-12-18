<?php

namespace Mixcharts;

class Mix {
    private $slug;
    private $name;
    private $published;
    private $play_count;
    private $listener_count;
    private $favorite_count;
    private $repost_count;
    private $comment_count;
    
    public static function fromDBRow($row) {
        $mix = new Mix();
        $mix->setFromDBRow($row);
        return $mix;
    }

    public static function fromJson($slug, $mix_data) {
        $mix = new Mix();
        $mix->setFromJson($slug, $mix_data);
        return $mix;
    }
    
    public function setFromDBRow($row) {
        $this->slug             = $row['slug'];
        $this->name             = $row['name'];
        $this->published        = $row['published'];
        $this->play_count       = $row['play_count'];
        $this->listener_count   = $row['listener_count'];
        $this->favorite_count   = $row['favorite_count'];
        $this->repost_count     = $row['repost_count'];
        $this->comment_count    = $row['comment_count'];
    }

    public function setFromJson($slug, $mix_data) {
        
        $this->slug             = $slug; // although there is a field 'slug' in the mix_data, we actually store a full API URL...
        
        $this->name             = $mix_data->name;
        $this->published        = $mix_data->created_time;
        $this->play_count       = $mix_data->play_count;
        $this->listener_count   = $mix_data->listener_count;
        $this->favorite_count   = $mix_data->favorite_count;
        $this->repost_count     = $mix_data->repost_count;
        $this->comment_count    = $mix_data->comment_count;
    }
    
    public function getSlug()
    {
        return $this->slug;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPublished()
    {
        return $this->published;
    }

    public function getPlayCount()
    {
        return $this->play_count;
    }

    public function getListenerCount()
    {
        return $this->listener_count;
    }

    public function getFavoriteCount()
    {
        return $this->favorite_count;
    }

    public function getRepostCount()
    {
        return $this->repost_count;
    }

    public function getCommentCount()
    {
        return $this->comment_count;
    }

    public function __toString() {
        return "Mix[{$this->name} :: p:{$this->play_count} l:{$this->listener_count} f:{$this->favorite_count} r:{$this->repost_count} c:{$this->comment_count}]";
    }
}

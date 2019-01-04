<?php

namespace Mixcharts;

class MixcloudDB {
    
    public function __construct($path) {
        $this->path = $path;
    }
    
    /**
     * PDO instance
     * @var \PDO
     */
    private $pdo;
    
    /**
     * return in instance of the PDO object that connects to the SQLite database
     * @return \PDO
     */
    public function connect() {
        if ($this->pdo == null) {
            $this->pdo = new \PDO("sqlite:" . $this->path);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        return $this->pdo;
    }
    
    public function getMix($slug) {
        $stmt = $this->pdo->prepare('SELECT slug, name FROM mix WHERE slug = :slug');
        $stmt->execute([':slug' => $slug]);
        $mixes = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $mixes[] = [
                'slug' => $row['slug'],
                'name' => $row['name'],
            ];
        }
        if (count($mixes) > 0) {
            return $mixes[0];
        }
        return null;
    }

    public function getMixesLike($term) {
        $stmt = $this->pdo->prepare('SELECT slug, name FROM mix WHERE name LIKE ? ORDER BY published DESC');
        $stmt->bindValue(1, "%$term%", \PDO::PARAM_STR);
        $stmt->execute();
        $mixes = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $mixes[] = [
                'slug' => $row['slug'],
                'name' => $row['name'],
            ];
        }
        return $mixes;
    }
    
    public function getMixesWithTracksLike($term) {
        $stmt = $this->pdo->prepare(
            'SELECT slug, name, published, tim.artist AS artist, tim.title AS title 
             FROM mix 
             LEFT JOIN track_in_mix tim ON mix.slug=tim.mix 
             WHERE (artist LIKE ? OR title LIKE ?) 
               AND tim.artist IS NOT NULL
             ORDER BY published DESC, slug, artist, title'
        );
        $stmt->bindValue(1, "%$term%", \PDO::PARAM_STR);
        $stmt->bindValue(2, "%$term%", \PDO::PARAM_STR);
        $stmt->execute();
        $tracks_in_mixes = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $tracks_in_mixes[] = [
                'slug' => $row['slug'],
                'name' => $row['name'],
                'published' => $row['published'],
                'artist' => $row['artist'],
                'title' => $row['title'],
            ];
        }
        return $tracks_in_mixes;
    }

    public function getMixesWithNestedTracks($term) {
        $tracks_in_mixes = $this->getMixesWithTracksLike($term);
        $mixes = [];
        foreach ($tracks_in_mixes as $track_in_mix) {
            if(!isset($mixes[$track_in_mix['name']])) {
                $mixes[$track_in_mix['name']] = [
                    'slug' => $track_in_mix['slug'],
                    'published' => $track_in_mix['published'],
                    'tracks' => [],
                ];
            }
            $mixes[$track_in_mix['name']]['tracks'][] = [
                'artist' => $track_in_mix['artist'],
                'title' => $track_in_mix['title'],
            ];
        }
        return $mixes;
    }

    public function getDistinctMixesWithTracksLike($term) {
        $stmt = $this->pdo->prepare(
            'SELECT DISTINCT slug, name
             FROM mix 
             LEFT JOIN track_in_mix tim ON mix.slug=tim.mix 
             WHERE (artist LIKE ? OR title LIKE ?) 
               AND tim.artist IS NOT NULL
             ORDER BY published DESC, slug'
        );
        $stmt->bindValue(1, "%$term%", \PDO::PARAM_STR);
        $stmt->bindValue(2, "%$term%", \PDO::PARAM_STR);
        $stmt->execute();
        $tracks_in_mixes = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $tracks_in_mixes[] = [
                'slug' => $row['slug'],
                'name' => $row['name'],
            ];
        }
        return $tracks_in_mixes;
    }    
    public function isTrackOnMix(Track $track, $slug) {
        $stmt = $this->pdo->prepare('SELECT COUNT(1) FROM track_in_mix WHERE artist = :artist AND title = :title AND mix = :slug');
        $stmt->execute([':slug' => $slug, ':artist' => $track->getArtist(), ':title' => $track->getTitle()]);
        $count = $stmt->fetchColumn();
        return $count > 0;
    }
    
    public function addMix($slug, $name, $published) {
        $stmt = $this->pdo->prepare('INSERT OR IGNORE INTO mix VALUES (:slug, :name, :published)');
        $stmt->execute([':slug' => $slug, ':name' => $name, ':published' => $published]);
    }
    
    public function addTrackToMix($slug, Track $track) {
        $stmt = $this->pdo->prepare('INSERT OR IGNORE INTO track_in_mix VALUES (:artist, :title, :mix)');
        $stmt->execute([':artist' => $track->getArtist(), ':title' => $track->getTitle(), ':mix' => $slug]);
    }
}
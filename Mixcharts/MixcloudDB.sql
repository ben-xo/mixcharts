CREATE TABLE IF NOT EXISTS mix (
    slug TEXT PRIMARY KEY,
    name TEXT NOT NULL,
    published DATE NOT NULL,
    play_count INTEGER NOT NULL,
    listener_count INTEGER NOT NULL,
    favorite_count INTEGER NOT NULL,
    repost_count INTEGER NOT NULL,
    comment_count INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS track_in_mix (
    artist TEXT NOT NULL,
    title TEXT NOT NULL,
    mix TEXT NOT NULL,
    PRIMARY KEY (artist, title, mix),
    FOREIGN KEY (mix) REFERENCES mix(slug)
                      ON UPDATE CASCADE
                      ON DELETE CASCADE
);
 

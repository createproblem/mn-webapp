<?php

namespace g5\MovieBundle\Document;



/**
 * g5\MovieBundle\Document\Movie
 */
class Movie
{
    /**
     * @var MongoId $id
     */
    protected $id;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var hash $meta
     */
    protected $meta;


    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set meta
     *
     * @param hash $meta
     * @return self
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;
        return $this;
    }

    /**
     * Get meta
     *
     * @return hash $meta
     */
    public function getMeta()
    {
        return $this->meta;
    }
    /**
     * @var int $tmdbid
     */
    protected $tmdbid;


    /**
     * Set tmdbid
     *
     * @param int $tmdbid
     * @return self
     */
    public function setTmdbid($tmdbid)
    {
        $this->tmdbid = $tmdbid;
        return $this;
    }

    /**
     * Get tmdbid
     *
     * @return int $tmdbid
     */
    public function getTmdbid()
    {
        return $this->tmdbid;
    }
}
